<?php

if ( isset( $_SERVER['SERVER_NAME'] ) && ( $_SERVER['SERVER_NAME'] == 'wp.ok' ) ) {
	$apiEndPoint = 'http://pdfrender.ok/v1';
} else {
	$apiEndPoint = 'http://pdf.brainvial.com/v1';
}
