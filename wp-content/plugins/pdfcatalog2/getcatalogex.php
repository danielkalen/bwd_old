<?php

if ( ! defined( 'PDFCATALOG' ) ) {
	echo 'this file should not be accessed directly';
	exit;
}
define( 'PDF_TEST', false );
define( 'PDF_LOG', true );

$startTime = microtime( true );

if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
	include_once 'vendor/autoload.php';
}

if ( strlen( trim( get_option( 'pdfcat_purchasecode' ) ) ) < 16 ) {
	PDFCatalogGenerator::toLog( 'No Purchase Code Specified' );
	echo 'No Purchase Code Specified. Please enter your Envato purchase code for this plugin in the PDF Catalog Settings.';
	exit;
}

if ( ! PDFCatalog::canViewCatalog() ) {
	exit;
}
$wpml = defined( 'ICL_LANGUAGE_CODE' );
$lang = '';

if ( $wpml ) {
	$lang = '-' . ICL_LANGUAGE_CODE;
}

PDFCatalogGenerator::toLog( 'Started getCatalogEx' );

set_time_limit( 0 );

$HTMLout = ( get_option( 'pdfcat_html' ) == 1 );
$redirectToPDF = ( get_option( 'pdfcat_redirectdownload' ) == 1 );


$useCache = ( get_option( 'pdfcat_cache' ) == 1 );
$fromCache = false;
$generator = new PDFCatalogGenerator();

if ( isset ( $_GET['cm'] ) ) {
	PDFCatalogGenerator::toLog( 'getCatalogEx: cm: ' . $_GET['cm'] );
	$catIDs = PDFCatalogGenerator::sanitizeCatIDs( explode( '-', $_GET['cm'] ) );

	if ( count( $catIDs ) > 0 ) {
		$res = $generator->allCategories( $lang, $useCache, $catIDs );
		$html = $res[0];
		$slug = $res[1];
		$cacheFile = $res[2];
		$cacheHit = $res[3];
	} else {
		echo 'No categories specified';
	}

} else if ( isset( $_GET['all'] ) ) {
	$res = $generator->allCategories( $lang, $useCache );
	$html = $res[0];
	$slug = $res[1];
	$cacheFile = $res[2];
	$cacheHit = $res[3];

} else {
// SINGLE CATEGORY ----------------------------------------------------------
	if ( isset( $_GET['c'] ) ) {
		$catID = (int) $_GET['c'];
	} else {
		$catID = 0;
		echo 'No parameters specified.';
		exit;
	}

	$res = $generator->singleCategory( $catID, $lang, $useCache );


	$html = $res[0];
	$slug = $res[1];
	$cacheFile = $res[2];
	$cacheHit = $res[3];

}


if ( ! $HTMLout ) {


	$failed = false;
	$failReason = '';
	if ( ! $cacheHit ) {
		PDFCatalogGenerator::toLog( 'Cache miss, sending HTML for ' . $slug );
		$result = PDFCatalogGenerator::send( $html, $slug );


		if ( $result->success ) {
			PDFCatalogGenerator::downloadTo( $result->url, $cacheFile );
		} else {
			$failed = true;
			$failReason = '(' . $result->errorCode . ') ';
			switch ( $result->errorCode ) {
				case '3':
					$failReason .= 'Problem generating catalog';
					break;
				case '2':
					$failReason .= 'Invalid Purchase Code - please enter your purchase code in plugin\'s settings page.';
					break;
				case '1':
					$failReason .= 'Invalid URL';
					break;
			}
		}
	} else {
		PDFCatalogGenerator::toLog( 'Cache HIT, using cached file for ' . $slug . ' cacheFile:' . $cacheFile );
	}


	if ( PDF_TEST ) {
		echo 'generated? ' . $cacheFile;
	} else {

		if ( $failed ) {
			echo 'Failure: ' . $failReason;
			die( 0 );
		} else if ( $redirectToPDF ) {
			// redirect to PDF file instead of sending the file data via PHP.
			$pi = pathinfo( $cacheFile );
			$url = PDFCatalogGenerator::getCacheURL( 'categories/' . $pi['basename'] );
			header( 'Location: ' . $url );
		} else {
			headers( $slug, $lang );
			readfile( $cacheFile );
		}
	}
} else {
	echo $html;

	$endTime = microtime( true );

	echo 'elapsed time: ' . ( $endTime - $startTime );
}

/*
$xhprof_data = xhprof_disable();


$XHPROF_ROOT = "/var/www/sites/default/xhprof";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "GetCatalog-final");
*/


function headers( $slug, $lang ) {

	$blogName = get_bloginfo( 'name', 'raw' );
	$filename = preg_replace( '/[^a-z0-9]/ui', '', $blogName ) . '-' . preg_replace( '/[^a-z0-9]/ui', '', $slug );

	header( 'Content-type: application/pdf' );

	if ( get_option( 'pdfcat_downloadfile' ) == 1 ) {
		header( 'Content-Disposition: attachment; filename="' . $filename . '.pdf"' );
	} else {
		header( 'Content-Disposition: inline; filename="' . $filename . '.pdf"' );
	}

}