jQuery(function($){if("undefined"==typeof wc_checkout_params)return!1;$.blockUI.defaults.overlayCSS.cursor="default";var e={updateTimer:!1,dirtyInput:!1,xhr:!1,$order_review:$("#order_review"),$checkout_form:$("form.checkout"),init:function(){$("body").bind("update_checkout",this.reset_update_checkout_timer),$("body").bind("update_checkout",this.update_checkout),$("body").bind("init_checkout",this.init_checkout),this.$order_review.on("click","input[name=payment_method]",this.payment_method_selected),this.$checkout_form.on("submit",this.submit),this.$checkout_form.on("blur input change",".input-text, select",this.validate_field),this.$checkout_form.on("input change","select.shipping_method, input[name^=shipping_method], #ship-to-different-address input, .update_totals_on_change select, .update_totals_on_change input[type=radio]",this.trigger_update_checkout),this.$checkout_form.on("change",".address-field input.input-text, .update_totals_on_change input.input-text",this.maybe_input_changed),this.$checkout_form.on("input change",".address-field select",this.input_changed),this.$checkout_form.on("input keydown",".address-field input.input-text, .update_totals_on_change input.input-text",this.queue_update_checkout),this.$checkout_form.on("change","#ship-to-different-address input",this.ship_to_different_address),this.$order_review.find("input[name=payment_method]:checked").trigger("click"),this.$checkout_form.find("#ship-to-different-address input").change(),"1"===wc_checkout_params.is_checkout&&$("body").trigger("init_checkout"),"yes"===wc_checkout_params.option_guest_checkout&&$("input#createaccount").change(this.toggle_create_account).change()},toggle_create_account:function(e){$("div.create-account").hide(),$(this).is(":checked")&&$("div.create-account").slideDown()},init_checkout:function(e){$("#billing_country, #shipping_country, .country_to_state").change(),$("body").trigger("update_checkout")},maybe_input_changed:function(t){e.dirtyInput&&e.input_changed()},input_changed:function(t){e.dirtyInput=this,e.maybe_update_checkout()},queue_update_checkout:function(t){var o=t.keyCode||t.which||0;return 9===o?!0:(e.dirtyInput=this,e.reset_update_checkout_timer(),void(e.updateTimer=setTimeout(e.maybe_update_checkout,"1000")))},trigger_update_checkout:function(t){e.reset_update_checkout_timer(),e.dirtyInput=!1,$("body").trigger("update_checkout")},maybe_update_checkout:function(){var t=!0;$(e.dirtyInput).size()&&($required_inputs=$(e.dirtyInput).closest("div").find(".address-field.validate-required"),$required_inputs.size()&&$required_inputs.each(function(e){""===$(this).find("input.input-text").val()&&(t=!1)})),t&&e.trigger_update_checkout()},ship_to_different_address:function(e){$("div.shipping_address").hide(),$(this).is(":checked")&&$("div.shipping_address").slideDown()},payment_method_selected:function(e){if($(".payment_methods input.input-radio").length>1){var t=$("div.payment_box."+$(this).attr("ID"));$(this).is(":checked")&&!t.is(":visible")&&($("div.payment_box").filter(":visible").slideUp(250),$(this).is(":checked")&&$("div.payment_box."+$(this).attr("ID")).slideDown(250))}else $("div.payment_box").show();$(this).data("order_button_text")?$("#place_order").val($(this).data("order_button_text")):$("#place_order").val($("#place_order").data("value"))},reset_update_checkout_timer:function(){clearTimeout(e.updateTimer)},validate_field:function(e){var t=$(this),o=t.closest(".form-row"),c=!0;if(o.is(".validate-required")&&""===t.val()&&(o.removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-required-field"),c=!1),o.is(".validate-email")&&t.val()){var i=new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);i.test(t.val())||(o.removeClass("woocommerce-validated").addClass("woocommerce-invalid woocommerce-invalid-email"),c=!1)}c&&o.removeClass("woocommerce-invalid woocommerce-invalid-required-field").addClass("woocommerce-validated")},update_checkout:function(){e.reset_update_checkout_timer(),e.updateTimer=setTimeout(e.update_checkout_action,"5")},update_checkout_action:function(){if(e.xhr&&e.xhr.abort(),0!==$("form.checkout").size()){var t=[];$("select.shipping_method, input[name^=shipping_method][type=radio]:checked, input[name^=shipping_method][type=hidden]").each(function(e,o){t[$(this).data("index")]=$(this).val()});var o=$("#order_review input[name=payment_method]:checked").val(),c=$("#billing_country").val(),i=$("#billing_state").val(),u=$("input#billing_postcode").val(),r=$("#billing_city").val(),a=$("input#billing_address_1").val(),n=$("input#billing_address_2").val(),d,s,p,_,h,m;$("#ship-to-different-address input").is(":checked")?(d=$("#shipping_country").val(),s=$("#shipping_state").val(),p=$("input#shipping_postcode").val(),_=$("#shipping_city").val(),h=$("input#shipping_address_1").val(),m=$("input#shipping_address_2").val()):(d=c,s=i,p=u,_=r,h=a,m=n),$(".woocommerce-checkout-payment, .woocommerce-checkout-review-order-table").block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var l={action:"woocommerce_update_order_review",security:wc_checkout_params.update_order_review_nonce,shipping_method:t,payment_method:o,country:c,state:i,postcode:u,city:r,address:a,address_2:n,s_country:d,s_state:s,s_postcode:p,s_city:_,s_address:h,s_address_2:m,post_data:$("form.checkout").serialize()};e.xhr=$.ajax({type:"POST",url:wc_checkout_params.ajax_url,data:l,success:function(e){if(e&&e.fragments&&$.each(e.fragments,function(e,t){$(e).replaceWith(t),$(e).unblock()}),"failure"==e.result){var t=$("form.checkout");if("true"===e.reload)return void window.location.reload();$(".woocommerce-error, .woocommerce-message").remove(),e.messages?t.prepend(e.messages):t.prepend(e),t.find(".input-text, select").blur(),$("html, body").animate({scrollTop:$("form.checkout").offset().top-100},1e3)}0===$(".woocommerce-checkout").find("input[name=payment_method]:checked").size()&&$(".woocommerce-checkout").find("input[name=payment_method]:eq(0)").attr("checked","checked"),$(".woocommerce-checkout").find("input[name=payment_method]:checked").eq(0).trigger("click"),$("body").trigger("updated_checkout")}})}},submit:function(t){e.reset_update_checkout_timer();var o=$(this);if(o.is(".processing"))return!1;if(o.triggerHandler("checkout_place_order")!==!1&&o.triggerHandler("checkout_place_order_"+$("#order_review input[name=payment_method]:checked").val())!==!1){o.addClass("processing");var c=o.data();1!=c["blockUI.isBlocked"]&&o.block({message:null,overlayCSS:{background:"#fff",opacity:.6}}),$.ajax({type:"POST",url:wc_checkout_params.checkout_url,data:o.serialize(),success:function(e){var t="";try{if(e.indexOf("<!--WC_START-->")>=0&&(e=e.split("<!--WC_START-->")[1]),e.indexOf("<!--WC_END-->")>=0&&(e=e.split("<!--WC_END-->")[0]),t=$.parseJSON(e),"success"!==t.result)throw"failure"===t.result?"Result failure":"Invalid response";-1!=t.redirect.indexOf("https://")||-1!=t.redirect.indexOf("http://")?window.location=t.redirect:window.location=decodeURI(t.redirect)}catch(c){if("true"===t.reload)return void window.location.reload();$(".woocommerce-error, .woocommerce-message").remove(),t.messages?o.prepend(t.messages):o.prepend(e),o.removeClass("processing").unblock(),o.find(".input-text, select").blur(),$("html, body").animate({scrollTop:$("form.checkout").offset().top-100},1e3),"true"===t.refresh&&$("body").trigger("update_checkout"),$("body").trigger("checkout_error")}},dataType:"html"})}return!1}},t={init:function(){$("body").on("click","a.showcoupon",this.show_coupon_form),$("body").on("click",".woocommerce-remove-coupon",this.remove_coupon),$("form.checkout_coupon").hide().submit(this.submit)},show_coupon_form:function(e){return $(".checkout_coupon").slideToggle(400,function(e){$(".checkout_coupon").find(":input:eq(0)").focus()}),!1},submit:function(e){var t=$(this);if(t.is(".processing"))return!1;t.addClass("processing").block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var o={action:"woocommerce_apply_coupon",security:wc_checkout_params.apply_coupon_nonce,coupon_code:t.find("input[name=coupon_code]").val()};return $.ajax({type:"POST",url:wc_checkout_params.ajax_url,data:o,success:function(e){$(".woocommerce-error, .woocommerce-message").remove(),t.removeClass("processing").unblock(),e&&(t.before(e),t.slideUp(),$("body").trigger("update_checkout"))},dataType:"html"}),!1},remove_coupon:function(e){e.preventDefault();var t=$(this).parents(".woocommerce-checkout-review-order"),o=$(this).data("coupon");t.addClass("processing").block({message:null,overlayCSS:{background:"#fff",opacity:.6}});var c={action:"woocommerce_remove_coupon",security:wc_checkout_params.remove_coupon_nonce,coupon:o};$.ajax({type:"POST",url:wc_checkout_params.ajax_url,data:c,success:function(e){$(".woocommerce-error, .woocommerce-message").remove(),t.removeClass("processing").unblock(),e&&($("form.woocommerce-checkout").before(e),$("body").trigger("update_checkout"),$("form.checkout_coupon").find('input[name="coupon_code"]').val(""))},error:function(e,t,o){wc_checkout_params.debug_mode&&console.log(e.responseText)},dataType:"html"})}},o={init:function(){$("body").on("click","a.showlogin",this.show_login_form)},show_login_form:function(e){return $("form.login").slideToggle(),!1}};e.init(),t.init(),o.init()});