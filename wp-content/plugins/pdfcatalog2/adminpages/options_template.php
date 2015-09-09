<?php  require dirname(__FILE__)."/options_header.php"; ?>

    <form method="post" action="options.php">
        <?php  settings_fields('pdfgen-page1');
        do_settings_sections('pdfgensettings');
        ?>
        <?php  submit_button(); ?>
    </form>

    <hr>

</div>