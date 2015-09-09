<?php  require dirname(__FILE__) . "/options_header.php"; ?>

<form method="post" action="options.php">
    <?php  settings_fields('pdfgen-page3');
    do_settings_sections('pdfheadsettings');
    ?>
    <?php  submit_button(); ?>
</form>

<hr>

</div>

<script>
    jQuery(function () {
        console.log('here');
        jQuery('#fld_pdflogo_button').click(function (e) {
            console.log('here2');
            e.preventDefault();
            var frame = wp.media.frames.customHeader = wp.media({

                title: "Select Header Logo for PDF Catalog",
                library: {
                    type: 'image'
                },
                button: {
                    //Button text
                    text: "Use as Logo"
                },
                multiple: false
            });

            frame.on('select', function () {
                var d = frame.state().get('selection').first().toJSON();
                jQuery('#pdfcat_logo').val(d.id);
                jQuery('#pdfcat_logo_preview').attr('src', d.url);
            });
            frame.open();
        });
    });
</script>