<?php define( 'WP_USE_THEMES', false );

//define( 'K_PATH_CACHE', '');
require( dirname( __FILE__ ) . '/../../../wp-blog-header.php' );

/**
 * Created by PhpStorm.
 * User: Kyriakos
 * Date: 07/10/2014
 * Time: 20:45
 */

$t = new PDFCatalogGenerator();
$a = [];

$b = new stdClass();
$b->title = 'Main 1';
$b->children = [];

$c = new stdClass();
$c->title = 'First Child of Main 1';
$c->children = [];

$b->children[] = $c;

$c = new stdClass();
$c->title = 'Second Child of Main 1';
$c->children = [];

$b->children[] = $c;

$d = new stdClass();
$d->title = 'First Child of 2nd Child of Main 1';
$d->children = [];

$c->children[] = $d;

$a[] = $b;

$b = new stdClass();
$b->title = 'Main 2';
$b->children = [];

$a[] = $b;

var_dump( $t->flattenTree($a));