jQuery(document).ready(function() {
    jQuery("p.datepicker").find('.input-text').datepicker();
    jQuery("p.timepicker").find('.input-text').timepicker();
    jQuery("p.colorpicker").find('.input-text').spectrum({
    	preferredFormat: "hex",
    	showInput: true,
    	showPalette: true,
        palette: [
            ['black', 'white', 'gray'],
            ['red', 'blue', 'yellow', 'green', 'orange', 'brown']
        ]
    });
});