<?php 
$templates = array();

$dirs = glob(dirname(__FILE__).DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);

foreach($dirs as $dir) {
    if (file_exists($dir.DIRECTORY_SEPARATOR.'template.php')) {
        include $dir.DIRECTORY_SEPARATOR.'template.php';
    }
}