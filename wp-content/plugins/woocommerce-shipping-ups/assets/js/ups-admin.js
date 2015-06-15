jQuery(window).load(function(){
	/*jshint multistr: true */
	/*global wcups */

	( function($) {

		// @todo - use single JSON

		var ups_enabled = '#woocommerce_ups_enabled';

		// All UPS API Settings
		var ups_settings_api = [
			'#woocommerce_ups_api',
			'#woocommerce_ups_user_id',
			'#woocommerce_ups_password',
			'#woocommerce_ups_access_key',
			'#woocommerce_ups_shipper_number'
		];

		// All UPS Settings (not API)
		var ups_settings_all = [
			//'#woocommerce_ups_simple_advanced',
			'#woocommerce_ups_title',
			'#woocommerce_ups_availability',
			'#woocommerce_ups_debug',
			'#woocommerce_ups_origin_addressline',
			'#woocommerce_ups_origin_city',
			'#woocommerce_ups_origin_country_state',
			'#woocommerce_ups_origin_postcode',
			'#woocommerce_ups_units',
			'#woocommerce_ups_negotiated',
			'#woocommerce_ups_insuredvalue',
			'#woocommerce_ups_signature',
			'#woocommerce_ups_pickup',
			'#woocommerce_ups_residential',
			'.ups_services',
			'#woocommerce_ups_offer_rates',
			'#woocommerce_ups_fallback',
			'#woocommerce_ups_packing_method',
			'#woocommerce_ups_ups_packaging',
			'.ups_boxes'
		];

		// UPS Advanced Settings
		var ups_settings_everything = $.merge( $.merge( [], ups_settings_api ), ups_settings_all );
		
		
		// Show / Hide everything based on enabled checkbox
		$.each(ups_settings_everything, function(index, item) {

			var item_each_everything = $( item );
		  	var item_parent_everything = $( item_each_everything.parents("tr") );

	  		if ( $( ups_enabled ).is(':checked') ) {

				// Initial Showing
				$( item_parent_everything ).show();
				$( '.ups-section-title' ).next( 'p' ).show();
				$( '.ups-section-title' ).show();

				// Show / Hide everything else based on API settings
				$.each(ups_settings_all, function(index, item) {

					var item_each = $( item );
				  	var item_parent = $( item_each.parents("tr") );

					if ( $( '#woocommerce_ups_user_id' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_ups_password' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_ups_access_key' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_ups_shipper_number' ).filter(function() { return $(this).val(); }).length <= 0 ) {

						// Initial Hiding
						$( item_parent ).hide();
						$( '.ups-section-title' ).next( 'p' ).hide();
						$( '.ups-section-title' ).hide();
						$( '.ups-api-title' ).next( 'p' ).show();
						$( '.ups-api-title' ).show();

					}

				});

			} else {

				// Initial Hiding
				$( item_parent_everything ).hide();
				$( '.ups-section-title' ).next( 'p' ).hide();
				$( '.ups-section-title' ).hide();

			}

			$( ups_enabled ).click(function() {

				// Toggle Hiding
				if ( $( '#woocommerce_ups_user_id' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_ups_password' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_ups_access_key' ).filter(function() { return $(this).val(); }).length <= 0 || $( '#woocommerce_ups_shipper_number' ).filter(function() { return $(this).val(); }).length <= 0 ) {

					$.each(ups_settings_api, function(index, item) {

						var item_each = $( item );
				  		var item_parent = $( item_each.parents("tr") );

						$( item_parent ).toggle(this.checked);
						$( '.ups-section-title.ups-api-title' ).next( 'p' ).toggle(this.checked);
						$( '.ups-section-title.ups-api-title' ).toggle(this.checked);

					});

				} else {

					$( item_parent_everything ).toggle(this.checked);
					$( '.ups-section-title' ).next( 'p' ).toggle(this.checked);
					$( '.ups-section-title' ).toggle(this.checked);

				}		
				

			});

		});

		// On init, show/hide packaging options if needed
		$('select#woocommerce_ups_packing_method option[value="per_item"]').each(function() {
		    if(this.selected) {
		        $( '#woocommerce_ups_ups_packaging, .ups_boxes' ).parents('tr').hide();
		    }
		});

		// When packing method changes, show/hide packaging options
		$("select#woocommerce_ups_packing_method").change(function(){
			if($(this).val() === 'per_item') {
				$( '#woocommerce_ups_ups_packaging, .ups_boxes' ).parents('tr').hide();
			}
			if($(this).val() === 'box_packing') {
				$( '#woocommerce_ups_ups_packaging, .ups_boxes' ).parents('tr').show();
			}
		});

	})(jQuery);

	jQuery('.ups_boxes .insert').click( function() {
		var $tbody = jQuery('.ups_boxes').find('tbody');
		var size = $tbody.find('tr').size();
		var code = '<tr class="new">\
				<td class="check-column"><input type="checkbox" /></td>\
				<td><input type="text" size="5" name="boxes_outer_length[' + size + ']" />' + wcups.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_outer_width[' + size + ']" />' + wcups.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_outer_height[' + size + ']" />' + wcups.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_inner_length[' + size + ']" />' + wcups.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_inner_width[' + size + ']" />' + wcups.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_inner_height[' + size + ']" />' + wcups.dim_unit + '</td>\
				<td><input type="text" size="5" name="boxes_box_weight[' + size + ']" />' + wcups.weight_unit + '</td>\
				<td><input type="text" size="5" name="boxes_max_weight[' + size + ']" />' + wcups.weight_unit + '</td>\
			</tr>';

		$tbody.append( code );

		return false;
	} );

	jQuery('.ups_boxes .remove').click(function() {
		var $tbody = jQuery('.ups_boxes').find('tbody');

		$tbody.find('.check-column input:checked').each(function() {
			jQuery(this).closest('tr').hide().find('input').val('');
		});

		return false;
	});

	// Ordering
	jQuery('.ups_services tbody').sortable({
		items:'tr',
		cursor:'move',
		axis:'y',
		handle: '.sort',
		scrollSensitivity:40,
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'wc-metabox-sortable-placeholder',
		start:function(event,ui){
			ui.item.css('baclbsround-color','#f6f6f6');
		},
		stop:function(event,ui){
			ui.item.removeAttr('style');
			ups_services_row_indexes();
		}
	});

	function ups_services_row_indexes() {
		jQuery('.ups_services tbody tr').each(function(index, el){
			jQuery('input.order', el).val( parseInt( jQuery(el).index('.ups_services tr') ) );
		});
	}

});