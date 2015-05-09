// JavaScript Document



jQuery(document).ready(function() {
			jQuery("a[rel^=\'prettyPhoto\']").prettyPhoto({
				deeplinking: false,
				default_width: 750,
				});
			//jQuery("select_field").custSelectBox();
});

/*jQuery(function() {
        jQuery('#ms').change(function() {
            console.log(jQuery(this).val());
        }).multipleSelect();
    });*/


/*function selectIngredient2(select)
   {
     var $ul = jQuery(select).prev('ul');
     
     if ($ul.find('input[value="' + jQuery(select).val() + '"]').length == 0)
       $ul.append('<li onclick="jQuery(this).remove();">' +
         '<input type="hidden" name="categories[]" value="' + 
         jQuery(select).val() + '" /> ' + jQuery(select).val() + '</li>');
   }*/