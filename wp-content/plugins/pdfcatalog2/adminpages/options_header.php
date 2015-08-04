<?php 
$url = admin_url().'options-general.php?page=pdfgensettings&tab=';
?>
<div class="wrap">
    <h2>PDF Catalog Generator for WooCommerce Options</h2>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo $url.'template'; ?>" class="nav-tab<?php echo  ($tab == 'template') ? ' nav-tab-active' : ''; ?>">Template</a>
        <a href="<?php echo $url.'colors'; ?>" class="nav-tab<?php echo  ($tab == 'colors') ? ' nav-tab-active' : ''; ?>">Appearance & Colors</a>
        <a href="<?php echo $url.'headfoot'; ?>" class="nav-tab<?php echo  ($tab == 'headfoot') ? ' nav-tab-active' : ''; ?>">Header / Footer</a>
        <a href="<?php echo $url.'categories'; ?>" class="nav-tab<?php echo  ($tab == 'categories') ? ' nav-tab-active' : ''; ?>">Categories & Products</a>
        <a href="<?php echo $url.'cache'; ?>" class="nav-tab<?php echo  ($tab == 'cache') ? ' nav-tab-active' : ''; ?>">Cache</a>

    </h2>