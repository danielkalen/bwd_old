<?php 
if ($showCategoryCatalogButton) {
    ?>
    <div class="PDFCatalogButtons PDFWidget categoryCatalog">
        <a href="<?php echo  $url; ?>"><?php echo $categoryButtonText; ?></a>
    </div>
<?php 
}

if ($showFullCatalogButton) {
    ?>
    <div class="PDFCatalogButtons PDFWidget fullCatalog">
        <a href="<?php echo  $urlall; ?>"><?php echo $storeButtonText; ?></a>
    </div>
<?php 
}
?>
