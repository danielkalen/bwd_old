// tipsy, facebook style tooltips for jquery
// version 1.0.0a
// (c) 2008-2010 jason frame [jason@onehackoranother.com]
// released under the MIT license

(function($) {
    
    function maybeCall(thing, ctx) {
        return (typeof thing == 'function') ? (thing.call(ctx)) : thing;
    };
    
    function isElementInDOM(ele) {
      while (ele = ele.parentNode) {
        if (ele == document) return true;
      }
      return false;
    };
    
    function Tipsy(element, options) {
        this.$element = $(element);
        this.options = options;
        this.enabled = true;
        this.fixTitle();
    };
    
    Tipsy.prototype = {
        show: function() {
            var title = this.getTitle();
            if (title && this.enabled) {
                var $tip = this.tip();
                
                $tip.find('.tipsy-inner')[this.options.html ? 'html' : 'text'](title);
                $tip[0].className = 'tipsy'; // reset classname in case of dynamic gravity
                $tip.remove().css({top: 0, left: 0, visibility: 'hidden', display: 'block'}).prependTo(document.body);
                
                var pos = $.extend({}, this.$element.offset(), {
                    width: this.$element[0].offsetWidth,
                    height: this.$element[0].offsetHeight
                });
                
                var actualWidth = $tip[0].offsetWidth,
                    actualHeight = $tip[0].offsetHeight,
                    gravity = maybeCall(this.options.gravity, this.$element[0]);
                
                var tp;
                switch (gravity.charAt(0)) {
                    case 'n':
                        tp = {top: pos.top + pos.height + this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 's':
                        tp = {top: pos.top - actualHeight - this.options.offset, left: pos.left + pos.width / 2 - actualWidth / 2};
                        break;
                    case 'e':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth - this.options.offset};
                        break;
                    case 'w':
                        tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width + this.options.offset};
                        break;
                }
                
                if (gravity.length == 2) {
                    if (gravity.charAt(1) == 'w') {
                        tp.left = pos.left + pos.width / 2 - 15;
                    } else {
                        tp.left = pos.left + pos.width / 2 - actualWidth + 15;
                    }
                }
                
                $tip.css(tp).addClass('tipsy-' + gravity);
                $tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
                if (this.options.className) {
                    $tip.addClass(maybeCall(this.options.className, this.$element[0]));
                }
                
                if (this.options.fade) {
                    $tip.stop().css({opacity: 0, display: 'block', visibility: 'visible'}).animate({opacity: this.options.opacity});
                } else {
                    $tip.css({visibility: 'visible', opacity: this.options.opacity});
                }
            }
        },
        
        hide: function() {
            if (this.options.fade) {
                this.tip().stop().fadeOut(function() { $(this).remove(); });
            } else {
                this.tip().remove();
            }
        },
        
        fixTitle: function() {
            var $e = this.$element;
            if ($e.attr('title') || typeof($e.attr('original-title')) != 'string') {
                $e.attr('original-title', $e.attr('title') || '').removeAttr('title');
            }
        },
        
        getTitle: function() {
            var title, $e = this.$element, o = this.options;
            this.fixTitle();
            var title, o = this.options;
            if (typeof o.title == 'string') {
                title = $e.attr(o.title == 'title' ? 'original-title' : o.title);
            } else if (typeof o.title == 'function') {
                title = o.title.call($e[0]);
            }
            title = ('' + title).replace(/(^\s*|\s*$)/, "");
            return title || o.fallback;
        },
        
        tip: function() {
            if (!this.$tip) {
                this.$tip = $('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');
                this.$tip.data('tipsy-pointee', this.$element[0]);
            }
            return this.$tip;
        },
        
        validate: function() {
            if (!this.$element[0].parentNode) {
                this.hide();
                this.$element = null;
                this.options = null;
            }
        },
        
        enable: function() { this.enabled = true; },
        disable: function() { this.enabled = false; },
        toggleEnabled: function() { this.enabled = !this.enabled; }
    };
    
    $.fn.tipsy = function(options) {
        
        if (options === true) {
            return this.data('tipsy');
        } else if (typeof options == 'string') {
            var tipsy = this.data('tipsy');
            if (tipsy) tipsy[options]();
            return this;
        }
        
        options = $.extend({}, $.fn.tipsy.defaults, options);
        
        function get(ele) {
            var tipsy = $.data(ele, 'tipsy');
            if (!tipsy) {
                tipsy = new Tipsy(ele, $.fn.tipsy.elementOptions(ele, options));
                $.data(ele, 'tipsy', tipsy);
            }
            return tipsy;
        }
        
        function enter() {
            var tipsy = get(this);
            tipsy.hoverState = 'in';
            if (options.delayIn == 0) {
                tipsy.show();
            } else {
                tipsy.fixTitle();
                setTimeout(function() { if (tipsy.hoverState == 'in') tipsy.show(); }, options.delayIn);
            }
        };
        
        function leave() {
            var tipsy = get(this);
            tipsy.hoverState = 'out';
            if (options.delayOut == 0) {
                tipsy.hide();
            } else {
                setTimeout(function() { if (tipsy.hoverState == 'out') tipsy.hide(); }, options.delayOut);
            }
        };
        
        if (!options.live) this.each(function() { get(this); });
        
        if (options.trigger != 'manual') {
            var binder   = options.live ? 'live' : 'bind',
                eventIn  = options.trigger == 'hover' ? 'mouseenter' : 'focus',
                eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur';
            this[binder](eventIn, enter)[binder](eventOut, leave);
        }
        
        return this;
        
    };
    
    $.fn.tipsy.defaults = {
        className: null,
        delayIn: 0,
        delayOut: 0,
        fade: false,
        fallback: '',
        gravity: 'n',
        html: false,
        live: false,
        offset: 0,
        opacity: 0.8,
        title: 'title',
        trigger: 'hover'
    };
    
    $.fn.tipsy.revalidate = function() {
      $('.tipsy').each(function() {
        var pointee = $.data(this, 'tipsy-pointee');
        if (!pointee || !isElementInDOM(pointee)) {
          $(this).remove();
        }
      });
    };
    
    // Overwrite this method to provide options on a per-element basis.
    // For example, you could store the gravity in a 'tipsy-gravity' attribute:
    // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
    // (remember - do not modify 'options' in place!)
    $.fn.tipsy.elementOptions = function(ele, options) {
        return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
    };
    
    $.fn.tipsy.autoNS = function() {
        return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
    };
    
    $.fn.tipsy.autoWE = function() {
        return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
    };
    
    /**
     * yields a closure of the supplied parameters, producing a function that takes
     * no arguments and is suitable for use as an autogravity function like so:
     *
     * @param margin (int) - distance from the viewable region edge that an
     *        element should be before setting its tooltip's gravity to be away
     *        from that edge.
     * @param prefer (string, e.g. 'n', 'sw', 'w') - the direction to prefer
     *        if there are no viewable region edges effecting the tooltip's
     *        gravity. It will try to vary from this minimally, for example,
     *        if 'sw' is preferred and an element is near the right viewable 
     *        region edge, but not the top edge, it will set the gravity for
     *        that element's tooltip to be 'se', preserving the southern
     *        component.
     */
     $.fn.tipsy.autoBounds = function(margin, prefer) {
		return function() {
			var dir = {ns: prefer[0], ew: (prefer.length > 1 ? prefer[1] : false)},
			    boundTop = $(document).scrollTop() + margin,
			    boundLeft = $(document).scrollLeft() + margin,
			    $this = $(this);

			if ($this.offset().top < boundTop) dir.ns = 'n';
			if ($this.offset().left < boundLeft) dir.ew = 'w';
			if ($(window).width() + $(document).scrollLeft() - $this.offset().left < margin) dir.ew = 'e';
			if ($(window).height() + $(document).scrollTop() - $this.offset().top < margin) dir.ns = 's';

			return dir.ns + (dir.ew ? dir.ew : '');
		}
	};
    
})(jQuery);


/* Chosen v1.0.0 | (c) 2011-2013 by Harvest | MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md */
!function(){var a,AbstractChosen,Chosen,SelectParser,b,c={}.hasOwnProperty,d=function(a,b){function d(){this.constructor=a}for(var e in b)c.call(b,e)&&(a[e]=b[e]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};SelectParser=function(){function SelectParser(){this.options_index=0,this.parsed=[]}return SelectParser.prototype.add_node=function(a){return"OPTGROUP"===a.nodeName.toUpperCase()?this.add_group(a):this.add_option(a)},SelectParser.prototype.add_group=function(a){var b,c,d,e,f,g;for(b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:this.escapeExpression(a.label),children:0,disabled:a.disabled}),f=a.childNodes,g=[],d=0,e=f.length;e>d;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},SelectParser.prototype.add_option=function(a,b,c){return"OPTION"===a.nodeName.toUpperCase()?(""!==a.text?(null!=b&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1):void 0},SelectParser.prototype.escapeExpression=function(a){var b,c;return null==a||a===!1?"":/[\&\<\>\"\'\`]/.test(a)?(b={"<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},c=/&(?!\w+;)|[\<\>\"\'\`]/g,a.replace(c,function(a){return b[a]||"&amp;"})):a},SelectParser}(),SelectParser.select_to_array=function(a){var b,c,d,e,f;for(c=new SelectParser,f=a.childNodes,d=0,e=f.length;e>d;d++)b=f[d],c.add_node(b);return c.parsed},AbstractChosen=function(){function AbstractChosen(a,b){this.form_field=a,this.options=null!=b?b:{},AbstractChosen.browser_is_supported()&&(this.is_multiple=this.form_field.multiple,this.set_default_text(),this.set_default_values(),this.setup(),this.set_up_html(),this.register_observers())}return AbstractChosen.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.result_single_selected=null,this.allow_single_deselect=null!=this.options.allow_single_deselect&&null!=this.form_field.options[0]&&""===this.form_field.options[0].text?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.disable_search=this.options.disable_search||!1,this.enable_split_word_search=null!=this.options.enable_split_word_search?this.options.enable_split_word_search:!0,this.group_search=null!=this.options.group_search?this.options.group_search:!0,this.search_contains=this.options.search_contains||!1,this.single_backstroke_delete=null!=this.options.single_backstroke_delete?this.options.single_backstroke_delete:!0,this.max_selected_options=this.options.max_selected_options||1/0,this.inherit_select_classes=this.options.inherit_select_classes||!1,this.display_selected_options=null!=this.options.display_selected_options?this.options.display_selected_options:!0,this.display_disabled_options=null!=this.options.display_disabled_options?this.options.display_disabled_options:!0},AbstractChosen.prototype.set_default_text=function(){return this.default_text=this.form_field.getAttribute("data-placeholder")?this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.options.placeholder_text_multiple||this.options.placeholder_text||AbstractChosen.default_multiple_text:this.options.placeholder_text_single||this.options.placeholder_text||AbstractChosen.default_single_text,this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||AbstractChosen.default_no_result_text},AbstractChosen.prototype.mouse_enter=function(){return this.mouse_on_container=!0},AbstractChosen.prototype.mouse_leave=function(){return this.mouse_on_container=!1},AbstractChosen.prototype.input_focus=function(){var a=this;if(this.is_multiple){if(!this.active_field)return setTimeout(function(){return a.container_mousedown()},50)}else if(!this.active_field)return this.activate_field()},AbstractChosen.prototype.input_blur=function(){var a=this;return this.mouse_on_container?void 0:(this.active_field=!1,setTimeout(function(){return a.blur_test()},100))},AbstractChosen.prototype.results_option_build=function(a){var b,c,d,e,f;for(b="",f=this.results_data,d=0,e=f.length;e>d;d++)c=f[d],b+=c.group?this.result_add_group(c):this.result_add_option(c),(null!=a?a.first:void 0)&&(c.selected&&this.is_multiple?this.choice_build(c):c.selected&&!this.is_multiple&&this.single_set_selected_text(c.text));return b},AbstractChosen.prototype.result_add_option=function(a){var b,c;return a.search_match?this.include_option_in_results(a)?(b=[],a.disabled||a.selected&&this.is_multiple||b.push("active-result"),!a.disabled||a.selected&&this.is_multiple||b.push("disabled-result"),a.selected&&b.push("result-selected"),null!=a.group_array_index&&b.push("group-option"),""!==a.classes&&b.push(a.classes),c=""!==a.style.cssText?' style="'+a.style+'"':"",'<li class="'+b.join(" ")+'"'+c+' data-option-array-index="'+a.array_index+'">'+a.search_text+"</li>"):"":""},AbstractChosen.prototype.result_add_group=function(a){return a.search_match||a.group_match?a.active_options>0?'<li class="group-result">'+a.search_text+"</li>":"":""},AbstractChosen.prototype.results_update_field=function(){return this.set_default_text(),this.is_multiple||this.results_reset_cleanup(),this.result_clear_highlight(),this.result_single_selected=null,this.results_build(),this.results_showing?this.winnow_results():void 0},AbstractChosen.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},AbstractChosen.prototype.results_search=function(){return this.results_showing?this.winnow_results():this.results_show()},AbstractChosen.prototype.winnow_results=function(){var a,b,c,d,e,f,g,h,i,j,k,l,m;for(this.no_results_clear(),e=0,g=this.get_search_text(),a=g.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),d=this.search_contains?"":"^",c=new RegExp(d+a,"i"),j=new RegExp(a,"i"),m=this.results_data,k=0,l=m.length;l>k;k++)b=m[k],b.search_match=!1,f=null,this.include_option_in_results(b)&&(b.group&&(b.group_match=!1,b.active_options=0),null!=b.group_array_index&&this.results_data[b.group_array_index]&&(f=this.results_data[b.group_array_index],0===f.active_options&&f.search_match&&(e+=1),f.active_options+=1),(!b.group||this.group_search)&&(b.search_text=b.group?b.label:b.html,b.search_match=this.search_string_match(b.search_text,c),b.search_match&&!b.group&&(e+=1),b.search_match?(g.length&&(h=b.search_text.search(j),i=b.search_text.substr(0,h+g.length)+"</em>"+b.search_text.substr(h+g.length),b.search_text=i.substr(0,h)+"<em>"+i.substr(h)),null!=f&&(f.group_match=!0)):null!=b.group_array_index&&this.results_data[b.group_array_index].search_match&&(b.search_match=!0)));return this.result_clear_highlight(),1>e&&g.length?(this.update_results_content(""),this.no_results(g)):(this.update_results_content(this.results_option_build()),this.winnow_results_set_highlight())},AbstractChosen.prototype.search_string_match=function(a,b){var c,d,e,f;if(b.test(a))return!0;if(this.enable_split_word_search&&(a.indexOf(" ")>=0||0===a.indexOf("["))&&(d=a.replace(/\[|\]/g,"").split(" "),d.length))for(e=0,f=d.length;f>e;e++)if(c=d[e],b.test(c))return!0},AbstractChosen.prototype.choices_count=function(){var a,b,c,d;if(null!=this.selected_option_count)return this.selected_option_count;for(this.selected_option_count=0,d=this.form_field.options,b=0,c=d.length;c>b;b++)a=d[b],a.selected&&(this.selected_option_count+=1);return this.selected_option_count},AbstractChosen.prototype.choices_click=function(a){return a.preventDefault(),this.results_showing||this.is_disabled?void 0:this.results_show()},AbstractChosen.prototype.keyup_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices_count()>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:if(a.preventDefault(),this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},AbstractChosen.prototype.container_width=function(){return null!=this.options.width?this.options.width:""+this.form_field.offsetWidth+"px"},AbstractChosen.prototype.include_option_in_results=function(a){return this.is_multiple&&!this.display_selected_options&&a.selected?!1:!this.display_disabled_options&&a.disabled?!1:a.empty?!1:!0},AbstractChosen.browser_is_supported=function(){return"Microsoft Internet Explorer"===window.navigator.appName?document.documentMode>=8:/iP(od|hone)/i.test(window.navigator.userAgent)?!1:/Android/i.test(window.navigator.userAgent)&&/Mobile/i.test(window.navigator.userAgent)?!1:!0},AbstractChosen.default_multiple_text="Select Some Options",AbstractChosen.default_single_text="Select an Option",AbstractChosen.default_no_result_text="No results match",AbstractChosen}(),a=jQuery,a.fn.extend({chosen:function(b){return AbstractChosen.browser_is_supported()?this.each(function(){var c,d;c=a(this),d=c.data("chosen"),"destroy"===b&&d?d.destroy():d||c.data("chosen",new Chosen(this,b))}):this}}),Chosen=function(c){function Chosen(){return b=Chosen.__super__.constructor.apply(this,arguments)}return d(Chosen,c),Chosen.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.current_selectedIndex=this.form_field.selectedIndex,this.is_rtl=this.form_field_jq.hasClass("chosen-rtl")},Chosen.prototype.set_up_html=function(){var b,c;return b=["chosen-container"],b.push("chosen-container-"+(this.is_multiple?"multi":"single")),this.inherit_select_classes&&this.form_field.className&&b.push(this.form_field.className),this.is_rtl&&b.push("chosen-rtl"),c={"class":b.join(" "),style:"width: "+this.container_width()+";",title:this.form_field.title},this.form_field.id.length&&(c.id=this.form_field.id.replace(/[^\w]/g,"_")+"_chosen"),this.container=a("<div />",c),this.is_multiple?this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>'):this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>'),this.form_field_jq.hide().after(this.container),this.dropdown=this.container.find("div.chosen-drop").first(),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chosen-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chosen-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chosen-search").first(),this.selected_item=this.container.find(".chosen-single").first()),this.results_build(),this.set_tab_index(),this.set_label_behavior(),this.form_field_jq.trigger("chosen:ready",{chosen:this})},Chosen.prototype.register_observers=function(){var a=this;return this.container.bind("mousedown.chosen",function(b){a.container_mousedown(b)}),this.container.bind("mouseup.chosen",function(b){a.container_mouseup(b)}),this.container.bind("mouseenter.chosen",function(b){a.mouse_enter(b)}),this.container.bind("mouseleave.chosen",function(b){a.mouse_leave(b)}),this.search_results.bind("mouseup.chosen",function(b){a.search_results_mouseup(b)}),this.search_results.bind("mouseover.chosen",function(b){a.search_results_mouseover(b)}),this.search_results.bind("mouseout.chosen",function(b){a.search_results_mouseout(b)}),this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",function(b){a.search_results_mousewheel(b)}),this.form_field_jq.bind("chosen:updated.chosen",function(b){a.results_update_field(b)}),this.form_field_jq.bind("chosen:activate.chosen",function(b){a.activate_field(b)}),this.form_field_jq.bind("chosen:open.chosen",function(b){a.container_mousedown(b)}),this.search_field.bind("blur.chosen",function(b){a.input_blur(b)}),this.search_field.bind("keyup.chosen",function(b){a.keyup_checker(b)}),this.search_field.bind("keydown.chosen",function(b){a.keydown_checker(b)}),this.search_field.bind("focus.chosen",function(b){a.input_focus(b)}),this.is_multiple?this.search_choices.bind("click.chosen",function(b){a.choices_click(b)}):this.container.bind("click.chosen",function(a){a.preventDefault()})},Chosen.prototype.destroy=function(){return a(document).unbind("click.chosen",this.click_test_action),this.search_field[0].tabIndex&&(this.form_field_jq[0].tabIndex=this.search_field[0].tabIndex),this.container.remove(),this.form_field_jq.removeData("chosen"),this.form_field_jq.show()},Chosen.prototype.search_field_disabled=function(){return this.is_disabled=this.form_field_jq[0].disabled,this.is_disabled?(this.container.addClass("chosen-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus.chosen",this.activate_action),this.close_field()):(this.container.removeClass("chosen-disabled"),this.search_field[0].disabled=!1,this.is_multiple?void 0:this.selected_item.bind("focus.chosen",this.activate_action))},Chosen.prototype.container_mousedown=function(b){return this.is_disabled||(b&&"mousedown"===b.type&&!this.results_showing&&b.preventDefault(),null!=b&&a(b.target).hasClass("search-choice-close"))?void 0:(this.active_field?this.is_multiple||!b||a(b.target)[0]!==this.selected_item[0]&&!a(b.target).parents("a.chosen-single").length||(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(document).bind("click.chosen",this.click_test_action),this.results_show()),this.activate_field())},Chosen.prototype.container_mouseup=function(a){return"ABBR"!==a.target.nodeName||this.is_disabled?void 0:this.results_reset(a)},Chosen.prototype.search_results_mousewheel=function(a){var b,c,d;return b=-(null!=(c=a.originalEvent)?c.wheelDelta:void 0)||(null!=(d=a.originialEvent)?d.detail:void 0),null!=b?(a.preventDefault(),"DOMMouseScroll"===a.type&&(b=40*b),this.search_results.scrollTop(b+this.search_results.scrollTop())):void 0},Chosen.prototype.blur_test=function(){return!this.active_field&&this.container.hasClass("chosen-container-active")?this.close_field():void 0},Chosen.prototype.close_field=function(){return a(document).unbind("click.chosen",this.click_test_action),this.active_field=!1,this.results_hide(),this.container.removeClass("chosen-container-active"),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},Chosen.prototype.activate_field=function(){return this.container.addClass("chosen-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},Chosen.prototype.test_active_click=function(b){return this.container.is(a(b.target).closest(".chosen-container"))?this.active_field=!0:this.close_field()},Chosen.prototype.results_build=function(){return this.parsing=!0,this.selected_option_count=null,this.results_data=SelectParser.select_to_array(this.form_field),this.is_multiple?this.search_choices.find("li.search-choice").remove():this.is_multiple||(this.single_set_selected_text(),this.disable_search||this.form_field.options.length<=this.disable_search_threshold?(this.search_field[0].readOnly=!0,this.container.addClass("chosen-container-single-nosearch")):(this.search_field[0].readOnly=!1,this.container.removeClass("chosen-container-single-nosearch"))),this.update_results_content(this.results_option_build({first:!0})),this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.parsing=!1},Chosen.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){if(this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight(),b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(f>c)return this.search_results.scrollTop(c)}},Chosen.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},Chosen.prototype.results_show=function(){return this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.container.addClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:showing_dropdown",{chosen:this}),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results())},Chosen.prototype.update_results_content=function(a){return this.search_results.html(a)},Chosen.prototype.results_hide=function(){return this.results_showing&&(this.result_clear_highlight(),this.container.removeClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:hiding_dropdown",{chosen:this})),this.results_showing=!1},Chosen.prototype.set_tab_index=function(){var a;return this.form_field.tabIndex?(a=this.form_field.tabIndex,this.form_field.tabIndex=-1,this.search_field[0].tabIndex=a):void 0},Chosen.prototype.set_label_behavior=function(){var b=this;return this.form_field_label=this.form_field_jq.parents("label"),!this.form_field_label.length&&this.form_field.id.length&&(this.form_field_label=a("label[for='"+this.form_field.id+"']")),this.form_field_label.length>0?this.form_field_label.bind("click.chosen",function(a){return b.is_multiple?b.container_mousedown(a):b.activate_field()}):void 0},Chosen.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices_count()<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},Chosen.prototype.search_results_mouseup=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c.length?(this.result_highlight=c,this.result_select(b),this.search_field.focus()):void 0},Chosen.prototype.search_results_mouseover=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c?this.result_do_highlight(c):void 0},Chosen.prototype.search_results_mouseout=function(b){return a(b.target).hasClass("active-result")?this.result_clear_highlight():void 0},Chosen.prototype.choice_build=function(b){var c,d,e=this;return c=a("<li />",{"class":"search-choice"}).html("<span>"+b.html+"</span>"),b.disabled?c.addClass("search-choice-disabled"):(d=a("<a />",{"class":"search-choice-close","data-option-array-index":b.array_index}),d.bind("click.chosen",function(a){return e.choice_destroy_link_click(a)}),c.append(d)),this.search_container.before(c)},Chosen.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),b.stopPropagation(),this.is_disabled?void 0:this.choice_destroy(a(b.target))},Chosen.prototype.choice_destroy=function(a){return this.result_deselect(a[0].getAttribute("data-option-array-index"))?(this.show_search_field_default(),this.is_multiple&&this.choices_count()>0&&this.search_field.val().length<1&&this.results_hide(),a.parents("li").first().remove(),this.search_field_scale()):void 0},Chosen.prototype.results_reset=function(){return this.form_field.options[0].selected=!0,this.selected_option_count=null,this.single_set_selected_text(),this.show_search_field_default(),this.results_reset_cleanup(),this.form_field_jq.trigger("change"),this.active_field?this.results_hide():void 0},Chosen.prototype.results_reset_cleanup=function(){return this.current_selectedIndex=this.form_field.selectedIndex,this.selected_item.find("abbr").remove()},Chosen.prototype.result_select=function(a){var b,c,d;return this.result_highlight?(b=this.result_highlight,this.result_clear_highlight(),this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.is_multiple?b.removeClass("active-result"):(this.result_single_selected&&(this.result_single_selected.removeClass("result-selected"),d=this.result_single_selected[0].getAttribute("data-option-array-index"),this.results_data[d].selected=!1),this.result_single_selected=b),b.addClass("result-selected"),c=this.results_data[b[0].getAttribute("data-option-array-index")],c.selected=!0,this.form_field.options[c.options_index].selected=!0,this.selected_option_count=null,this.is_multiple?this.choice_build(c):this.single_set_selected_text(c.text),(a.metaKey||a.ctrlKey)&&this.is_multiple||this.results_hide(),this.search_field.val(""),(this.is_multiple||this.form_field.selectedIndex!==this.current_selectedIndex)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[c.options_index].value}),this.current_selectedIndex=this.form_field.selectedIndex,this.search_field_scale())):void 0},Chosen.prototype.single_set_selected_text=function(a){return null==a&&(a=this.default_text),a===this.default_text?this.selected_item.addClass("chosen-default"):(this.single_deselect_control_build(),this.selected_item.removeClass("chosen-default")),this.selected_item.find("span").text(a)},Chosen.prototype.result_deselect=function(a){var b;return b=this.results_data[a],this.form_field.options[b.options_index].disabled?!1:(b.selected=!1,this.form_field.options[b.options_index].selected=!1,this.selected_option_count=null,this.result_clear_highlight(),this.results_showing&&this.winnow_results(),this.form_field_jq.trigger("change",{deselected:this.form_field.options[b.options_index].value}),this.search_field_scale(),!0)},Chosen.prototype.single_deselect_control_build=function(){return this.allow_single_deselect?(this.selected_item.find("abbr").length||this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'),this.selected_item.addClass("chosen-single-with-deselect")):void 0},Chosen.prototype.get_search_text=function(){return this.search_field.val()===this.default_text?"":a("<div/>").text(a.trim(this.search_field.val())).html()},Chosen.prototype.winnow_results_set_highlight=function(){var a,b;return b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first(),null!=a?this.result_do_highlight(a):void 0},Chosen.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c)},Chosen.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},Chosen.prototype.keydown_arrow=function(){var a;return this.results_showing&&this.result_highlight?(a=this.result_highlight.nextAll("li.active-result").first())?this.result_do_highlight(a):void 0:this.results_show()},Chosen.prototype.keyup_arrow=function(){var a;return this.results_showing||this.is_multiple?this.result_highlight?(a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices_count()>0&&this.results_hide(),this.result_clear_highlight())):void 0:this.results_show()},Chosen.prototype.keydown_backstroke=function(){var a;return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(a=this.search_container.siblings("li.search-choice").last(),a.length&&!a.hasClass("search-choice-disabled")?(this.pending_backstroke=a,this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus")):void 0)},Chosen.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},Chosen.prototype.keydown_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),8!==b&&this.pending_backstroke&&this.clear_backstroke(),b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:a.preventDefault(),this.keydown_arrow()}},Chosen.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){for(d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"],i=0,j=g.length;j>i;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return b=a("<div />",{style:f}),b.text(this.search_field.val()),a("body").append(b),h=b.width()+25,b.remove(),c=this.container.outerWidth(),h>c-10&&(h=c-10),this.search_field.css({width:h+"px"})}},Chosen}(AbstractChosen)}.call(this);

/**
 * Isotope v1.5.25
 * An exquisite jQuery plugin for magical layouts
 * http://isotope.metafizzy.co
 *
 * Commercial use requires one-time purchase of a commercial license
 * http://isotope.metafizzy.co/docs/license.html
 *
 * Non-commercial use is licensed under the MIT License
 *
 * Copyright 2013 Metafizzy
 */
(function(a,b,c){"use strict";var d=a.document,e=a.Modernizr,f=function(a){return a.charAt(0).toUpperCase()+a.slice(1)},g="Moz Webkit O Ms".split(" "),h=function(a){var b=d.documentElement.style,c;if(typeof b[a]=="string")return a;a=f(a);for(var e=0,h=g.length;e<h;e++){c=g[e]+a;if(typeof b[c]=="string")return c}},i=h("transform"),j=h("transitionProperty"),k={csstransforms:function(){return!!i},csstransforms3d:function(){var a=!!h("perspective");if(a){var c=" -o- -moz- -ms- -webkit- -khtml- ".split(" "),d="@media ("+c.join("transform-3d),(")+"modernizr)",e=b("<style>"+d+"{#modernizr{height:3px}}"+"</style>").appendTo("head"),f=b('<div id="modernizr" />').appendTo("html");a=f.height()===3,f.remove(),e.remove()}return a},csstransitions:function(){return!!j}},l;if(e)for(l in k)e.hasOwnProperty(l)||e.addTest(l,k[l]);else{e=a.Modernizr={_version:"1.6ish: miniModernizr for Isotope"};var m=" ",n;for(l in k)n=k[l](),e[l]=n,m+=" "+(n?"":"no-")+l;b("html").addClass(m)}if(e.csstransforms){var o=e.csstransforms3d?{translate:function(a){return"translate3d("+a[0]+"px, "+a[1]+"px, 0) "},scale:function(a){return"scale3d("+a+", "+a+", 1) "}}:{translate:function(a){return"translate("+a[0]+"px, "+a[1]+"px) "},scale:function(a){return"scale("+a+") "}},p=function(a,c,d){var e=b.data(a,"isoTransform")||{},f={},g,h={},j;f[c]=d,b.extend(e,f);for(g in e)j=e[g],h[g]=o[g](j);var k=h.translate||"",l=h.scale||"",m=k+l;b.data(a,"isoTransform",e),a.style[i]=m};b.cssNumber.scale=!0,b.cssHooks.scale={set:function(a,b){p(a,"scale",b)},get:function(a,c){var d=b.data(a,"isoTransform");return d&&d.scale?d.scale:1}},b.fx.step.scale=function(a){b.cssHooks.scale.set(a.elem,a.now+a.unit)},b.cssNumber.translate=!0,b.cssHooks.translate={set:function(a,b){p(a,"translate",b)},get:function(a,c){var d=b.data(a,"isoTransform");return d&&d.translate?d.translate:[0,0]}}}var q,r;e.csstransitions&&(q={WebkitTransitionProperty:"webkitTransitionEnd",MozTransitionProperty:"transitionend",OTransitionProperty:"oTransitionEnd otransitionend",transitionProperty:"transitionend"}[j],r=h("transitionDuration"));var s=b.event,t=b.event.handle?"handle":"dispatch",u;s.special.smartresize={setup:function(){b(this).bind("resize",s.special.smartresize.handler)},teardown:function(){b(this).unbind("resize",s.special.smartresize.handler)},handler:function(a,b){var c=this,d=arguments;a.type="smartresize",u&&clearTimeout(u),u=setTimeout(function(){s[t].apply(c,d)},b==="execAsap"?0:100)}},b.fn.smartresize=function(a){return a?this.bind("smartresize",a):this.trigger("smartresize",["execAsap"])},b.Isotope=function(a,c,d){this.element=b(c),this._create(a),this._init(d)};var v=["width","height"],w=b(a);b.Isotope.settings={resizable:!0,layoutMode:"masonry",containerClass:"isotope",itemClass:"isotope-item",hiddenClass:"isotope-hidden",hiddenStyle:{opacity:0,scale:.001},visibleStyle:{opacity:1,scale:1},containerStyle:{position:"relative",overflow:"hidden"},animationEngine:"best-available",animationOptions:{queue:!1,duration:800},sortBy:"original-order",sortAscending:!0,resizesContainer:!0,transformsEnabled:!0,itemPositionDataEnabled:!1},b.Isotope.prototype={_create:function(a){this.options=b.extend({},b.Isotope.settings,a),this.styleQueue=[],this.elemCount=0;var c=this.element[0].style;this.originalStyle={};var d=v.slice(0);for(var e in this.options.containerStyle)d.push(e);for(var f=0,g=d.length;f<g;f++)e=d[f],this.originalStyle[e]=c[e]||"";this.element.css(this.options.containerStyle),this._updateAnimationEngine(),this._updateUsingTransforms();var h={"original-order":function(a,b){return b.elemCount++,b.elemCount},random:function(){return Math.random()}};this.options.getSortData=b.extend(this.options.getSortData,h),this.reloadItems(),this.offset={left:parseInt(this.element.css("padding-left")||0,10),top:parseInt(this.element.css("padding-top")||0,10)};var i=this;setTimeout(function(){i.element.addClass(i.options.containerClass)},0),this.options.resizable&&w.bind("smartresize.isotope",function(){i.resize()}),this.element.delegate("."+this.options.hiddenClass,"click",function(){return!1})},_getAtoms:function(a){var b=this.options.itemSelector,c=b?a.filter(b).add(a.find(b)):a,d={position:"absolute"};return c=c.filter(function(a,b){return b.nodeType===1}),this.usingTransforms&&(d.left=0,d.top=0),c.css(d).addClass(this.options.itemClass),this.updateSortData(c,!0),c},_init:function(a){this.$filteredAtoms=this._filter(this.$allAtoms),this._sort(),this.reLayout(a)},option:function(a){if(b.isPlainObject(a)){this.options=b.extend(!0,this.options,a);var c;for(var d in a)c="_update"+f(d),this[c]&&this[c]()}},_updateAnimationEngine:function(){var a=this.options.animationEngine.toLowerCase().replace(/[ _\-]/g,""),b;switch(a){case"css":case"none":b=!1;break;case"jquery":b=!0;break;default:b=!e.csstransitions}this.isUsingJQueryAnimation=b,this._updateUsingTransforms()},_updateTransformsEnabled:function(){this._updateUsingTransforms()},_updateUsingTransforms:function(){var a=this.usingTransforms=this.options.transformsEnabled&&e.csstransforms&&e.csstransitions&&!this.isUsingJQueryAnimation;a||(delete this.options.hiddenStyle.scale,delete this.options.visibleStyle.scale),this.getPositionStyles=a?this._translate:this._positionAbs},_filter:function(a){var b=this.options.filter===""?"*":this.options.filter;if(!b)return a;var c=this.options.hiddenClass,d="."+c,e=a.filter(d),f=e;if(b!=="*"){f=e.filter(b);var g=a.not(d).not(b).addClass(c);this.styleQueue.push({$el:g,style:this.options.hiddenStyle})}return this.styleQueue.push({$el:f,style:this.options.visibleStyle}),f.removeClass(c),a.filter(b)},updateSortData:function(a,c){var d=this,e=this.options.getSortData,f,g;a.each(function(){f=b(this),g={};for(var a in e)!c&&a==="original-order"?g[a]=b.data(this,"isotope-sort-data")[a]:g[a]=e[a](f,d);b.data(this,"isotope-sort-data",g)})},_sort:function(){var a=this.options.sortBy,b=this._getSorter,c=this.options.sortAscending?1:-1,d=function(d,e){var f=b(d,a),g=b(e,a);return f===g&&a!=="original-order"&&(f=b(d,"original-order"),g=b(e,"original-order")),(f>g?1:f<g?-1:0)*c};this.$filteredAtoms.sort(d)},_getSorter:function(a,c){return b.data(a,"isotope-sort-data")[c]},_translate:function(a,b){return{translate:[a,b]}},_positionAbs:function(a,b){return{left:a,top:b}},_pushPosition:function(a,b,c){b=Math.round(b+this.offset.left),c=Math.round(c+this.offset.top);var d=this.getPositionStyles(b,c);this.styleQueue.push({$el:a,style:d}),this.options.itemPositionDataEnabled&&a.data("isotope-item-position",{x:b,y:c})},layout:function(a,b){var c=this.options.layoutMode;this["_"+c+"Layout"](a);if(this.options.resizesContainer){var d=this["_"+c+"GetContainerSize"]();this.styleQueue.push({$el:this.element,style:d})}this._processStyleQueue(a,b),this.isLaidOut=!0},_processStyleQueue:function(a,c){var d=this.isLaidOut?this.isUsingJQueryAnimation?"animate":"css":"css",f=this.options.animationOptions,g=this.options.onLayout,h,i,j,k;i=function(a,b){b.$el[d](b.style,f)};if(this._isInserting&&this.isUsingJQueryAnimation)i=function(a,b){h=b.$el.hasClass("no-transition")?"css":d,b.$el[h](b.style,f)};else if(c||g||f.complete){var l=!1,m=[c,g,f.complete],n=this;j=!0,k=function(){if(l)return;var b;for(var c=0,d=m.length;c<d;c++)b=m[c],typeof b=="function"&&b.call(n.element,a,n);l=!0};if(this.isUsingJQueryAnimation&&d==="animate")f.complete=k,j=!1;else if(e.csstransitions){var o=0,p=this.styleQueue[0],s=p&&p.$el,t;while(!s||!s.length){t=this.styleQueue[o++];if(!t)return;s=t.$el}var u=parseFloat(getComputedStyle(s[0])[r]);u>0&&(i=function(a,b){b.$el[d](b.style,f).one(q,k)},j=!1)}}b.each(this.styleQueue,i),j&&k(),this.styleQueue=[]},resize:function(){this["_"+this.options.layoutMode+"ResizeChanged"]()&&this.reLayout()},reLayout:function(a){this["_"+this.options.layoutMode+"Reset"](),this.layout(this.$filteredAtoms,a)},addItems:function(a,b){var c=this._getAtoms(a);this.$allAtoms=this.$allAtoms.add(c),b&&b(c)},insert:function(a,b){this.element.append(a);var c=this;this.addItems(a,function(a){var d=c._filter(a);c._addHideAppended(d),c._sort(),c.reLayout(),c._revealAppended(d,b)})},appended:function(a,b){var c=this;this.addItems(a,function(a){c._addHideAppended(a),c.layout(a),c._revealAppended(a,b)})},_addHideAppended:function(a){this.$filteredAtoms=this.$filteredAtoms.add(a),a.addClass("no-transition"),this._isInserting=!0,this.styleQueue.push({$el:a,style:this.options.hiddenStyle})},_revealAppended:function(a,b){var c=this;setTimeout(function(){a.removeClass("no-transition"),c.styleQueue.push({$el:a,style:c.options.visibleStyle}),c._isInserting=!1,c._processStyleQueue(a,b)},10)},reloadItems:function(){this.$allAtoms=this._getAtoms(this.element.children())},remove:function(a,b){this.$allAtoms=this.$allAtoms.not(a),this.$filteredAtoms=this.$filteredAtoms.not(a);var c=this,d=function(){a.remove(),b&&b.call(c.element)};a.filter(":not(."+this.options.hiddenClass+")").length?(this.styleQueue.push({$el:a,style:this.options.hiddenStyle}),this._sort(),this.reLayout(d)):d()},shuffle:function(a){this.updateSortData(this.$allAtoms),this.options.sortBy="random",this._sort(),this.reLayout(a)},destroy:function(){var a=this.usingTransforms,b=this.options;this.$allAtoms.removeClass(b.hiddenClass+" "+b.itemClass).each(function(){var b=this.style;b.position="",b.top="",b.left="",b.opacity="",a&&(b[i]="")});var c=this.element[0].style;for(var d in this.originalStyle)c[d]=this.originalStyle[d];this.element.unbind(".isotope").undelegate("."+b.hiddenClass,"click").removeClass(b.containerClass).removeData("isotope"),w.unbind(".isotope")},_getSegments:function(a){var b=this.options.layoutMode,c=a?"rowHeight":"columnWidth",d=a?"height":"width",e=a?"rows":"cols",g=this.element[d](),h,i=this.options[b]&&this.options[b][c]||this.$filteredAtoms["outer"+f(d)](!0)||g;h=Math.floor(g/i),h=Math.max(h,1),this[b][e]=h,this[b][c]=i},_checkIfSegmentsChanged:function(a){var b=this.options.layoutMode,c=a?"rows":"cols",d=this[b][c];return this._getSegments(a),this[b][c]!==d},_masonryReset:function(){this.masonry={},this._getSegments();var a=this.masonry.cols;this.masonry.colYs=[];while(a--)this.masonry.colYs.push(0)},_masonryLayout:function(a){var c=this,d=c.masonry;a.each(function(){var a=b(this),e=Math.ceil(a.outerWidth(!0)/d.columnWidth);e=Math.min(e,d.cols);if(e===1)c._masonryPlaceBrick(a,d.colYs);else{var f=d.cols+1-e,g=[],h,i;for(i=0;i<f;i++)h=d.colYs.slice(i,i+e),g[i]=Math.max.apply(Math,h);c._masonryPlaceBrick(a,g)}})},_masonryPlaceBrick:function(a,b){var c=Math.min.apply(Math,b),d=0;for(var e=0,f=b.length;e<f;e++)if(b[e]===c){d=e;break}var g=this.masonry.columnWidth*d,h=c;this._pushPosition(a,g,h);var i=c+a.outerHeight(!0),j=this.masonry.cols+1-f;for(e=0;e<j;e++)this.masonry.colYs[d+e]=i},_masonryGetContainerSize:function(){var a=Math.max.apply(Math,this.masonry.colYs);return{height:a}},_masonryResizeChanged:function(){return this._checkIfSegmentsChanged()},_fitRowsReset:function(){this.fitRows={x:0,y:0,height:0}},_fitRowsLayout:function(a){var c=this,d=this.element.width(),e=this.fitRows;a.each(function(){var a=b(this),f=a.outerWidth(!0),g=a.outerHeight(!0);e.x!==0&&f+e.x>d&&(e.x=0,e.y=e.height),c._pushPosition(a,e.x,e.y),e.height=Math.max(e.y+g,e.height),e.x+=f})},_fitRowsGetContainerSize:function(){return{height:this.fitRows.height}},_fitRowsResizeChanged:function(){return!0},_cellsByRowReset:function(){this.cellsByRow={index:0},this._getSegments(),this._getSegments(!0)},_cellsByRowLayout:function(a){var c=this,d=this.cellsByRow;a.each(function(){var a=b(this),e=d.index%d.cols,f=Math.floor(d.index/d.cols),g=(e+.5)*d.columnWidth-a.outerWidth(!0)/2,h=(f+.5)*d.rowHeight-a.outerHeight(!0)/2;c._pushPosition(a,g,h),d.index++})},_cellsByRowGetContainerSize:function(){return{height:Math.ceil(this.$filteredAtoms.length/this.cellsByRow.cols)*this.cellsByRow.rowHeight+this.offset.top}},_cellsByRowResizeChanged:function(){return this._checkIfSegmentsChanged()},_straightDownReset:function(){this.straightDown={y:0}},_straightDownLayout:function(a){var c=this;a.each(function(a){var d=b(this);c._pushPosition(d,0,c.straightDown.y),c.straightDown.y+=d.outerHeight(!0)})},_straightDownGetContainerSize:function(){return{height:this.straightDown.y}},_straightDownResizeChanged:function(){return!0},_masonryHorizontalReset:function(){this.masonryHorizontal={},this._getSegments(!0);var a=this.masonryHorizontal.rows;this.masonryHorizontal.rowXs=[];while(a--)this.masonryHorizontal.rowXs.push(0)},_masonryHorizontalLayout:function(a){var c=this,d=c.masonryHorizontal;a.each(function(){var a=b(this),e=Math.ceil(a.outerHeight(!0)/d.rowHeight);e=Math.min(e,d.rows);if(e===1)c._masonryHorizontalPlaceBrick(a,d.rowXs);else{var f=d.rows+1-e,g=[],h,i;for(i=0;i<f;i++)h=d.rowXs.slice(i,i+e),g[i]=Math.max.apply(Math,h);c._masonryHorizontalPlaceBrick(a,g)}})},_masonryHorizontalPlaceBrick:function(a,b){var c=Math.min.apply(Math,b),d=0;for(var e=0,f=b.length;e<f;e++)if(b[e]===c){d=e;break}var g=c,h=this.masonryHorizontal.rowHeight*d;this._pushPosition(a,g,h);var i=c+a.outerWidth(!0),j=this.masonryHorizontal.rows+1-f;for(e=0;e<j;e++)this.masonryHorizontal.rowXs[d+e]=i},_masonryHorizontalGetContainerSize:function(){var a=Math.max.apply(Math,this.masonryHorizontal.rowXs);return{width:a}},_masonryHorizontalResizeChanged:function(){return this._checkIfSegmentsChanged(!0)},_fitColumnsReset:function(){this.fitColumns={x:0,y:0,width:0}},_fitColumnsLayout:function(a){var c=this,d=this.element.height(),e=this.fitColumns;a.each(function(){var a=b(this),f=a.outerWidth(!0),g=a.outerHeight(!0);e.y!==0&&g+e.y>d&&(e.x=e.width,e.y=0),c._pushPosition(a,e.x,e.y),e.width=Math.max(e.x+f,e.width),e.y+=g})},_fitColumnsGetContainerSize:function(){return{width:this.fitColumns.width}},_fitColumnsResizeChanged:function(){return!0},_cellsByColumnReset:function(){this.cellsByColumn={index:0},this._getSegments(),this._getSegments(!0)},_cellsByColumnLayout:function(a){var c=this,d=this.cellsByColumn;a.each(function(){var a=b(this),e=Math.floor(d.index/d.rows),f=d.index%d.rows,g=(e+.5)*d.columnWidth-a.outerWidth(!0)/2,h=(f+.5)*d.rowHeight-a.outerHeight(!0)/2;c._pushPosition(a,g,h),d.index++})},_cellsByColumnGetContainerSize:function(){return{width:Math.ceil(this.$filteredAtoms.length/this.cellsByColumn.rows)*this.cellsByColumn.columnWidth}},_cellsByColumnResizeChanged:function(){return this._checkIfSegmentsChanged(!0)},_straightAcrossReset:function(){this.straightAcross={x:0}},_straightAcrossLayout:function(a){var c=this;a.each(function(a){var d=b(this);c._pushPosition(d,c.straightAcross.x,0),c.straightAcross.x+=d.outerWidth(!0)})},_straightAcrossGetContainerSize:function(){return{width:this.straightAcross.x}},_straightAcrossResizeChanged:function(){return!0}},b.fn.imagesLoaded=function(a){function h(){a.call(c,d)}function i(a){var c=a.target;c.src!==f&&b.inArray(c,g)===-1&&(g.push(c),--e<=0&&(setTimeout(h),d.unbind(".imagesLoaded",i)))}var c=this,d=c.find("img").add(c.filter("img")),e=d.length,f="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",g=[];return e||h(),d.bind("load.imagesLoaded error.imagesLoaded",i).each(function(){var a=this.src;this.src=f,this.src=a}),c};var x=function(b){a.console&&a.console.error(b)};b.fn.isotope=function(a,c){if(typeof a=="string"){var d=Array.prototype.slice.call(arguments,1);this.each(function(){var c=b.data(this,"isotope");if(!c){x("cannot call methods on isotope prior to initialization; attempted to call method '"+a+"'");return}if(!b.isFunction(c[a])||a.charAt(0)==="_"){x("no such method '"+a+"' for isotope instance");return}c[a].apply(c,d)})}else this.each(function(){var d=b.data(this,"isotope");d?(d.option(a),d._init(c)):b.data(this,"isotope",new b.Isotope(a,this,c))});return this}})(window,jQuery);

/*!
 * jQuery Upload File Plugin
 * version: 1.9
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/
 */
(function(a){a.fn.uploadFile=function(b){var c=a.extend({url:"",method:"POST",enctype:"multipart/form-data",formData:null,returnType:null,allowedTypes:"*",fileName:"userpro_file",multiple:false,autoSubmit:true,showCancel:false,showAbort:false,showDone:false,showStatusAfterSuccess:true,buttonCss:false,buttonClass:false,onSubmit:function(e){},onSuccess:function(f,e){},onError:function(f,e){},uploadButtonClass:"ajax-file-upload"},b);var d="ajax-file-upload-"+a(this).attr("id");this.formGroup=d;a(this).click(function(){a.fn.uploadFile.createAjaxForm(this,d,c)});this.startUpload=function(){a("."+this.formGroup).each(function(f,e){a(this).submit()})};a(this).addClass(c.uploadButtonClass);return this};a.fn.uploadFile.createAjaxForm=function(g,l,o){var d=a("<form style='display:none;' class='"+l+"' method='"+o.method+"' action='"+o.url+"' enctype='"+o.enctype+"'></form>");var c="<input type='file' name='"+o.fileName+"'/>";if(o.multiple){if(o.fileName.indexOf("[]")!=o.fileName.length-2){o.fileName+="[]"}c="<input type='file' name='"+o.fileName+"' multiple/>"}var h=a(c).appendTo(d);var k=a("<div class='ajax-file-upload-statusbar'></div>");var b=a("").appendTo(k);var n=a("<div class='ajax-file-upload-progress'>").appendTo(k).hide();var j=a("<div class='ajax-file-upload-bar'></div>").appendTo(n);var f=a("").appendTo(k).hide();var m=a("<div class='ajax-file-upload-red'>Cancel</div>").appendTo(k).hide();var e=a("").appendTo(k).hide();a(h).change(function(){var v=o.allowedTypes.toLowerCase().split(",");var r="";var q=[];if(this.files){for(i=0;i<this.files.length;i++){var t=this.files[i].name;q.push(t);var u=t.split(".").pop().toLowerCase();if(o.allowedTypes!="*"&&jQuery.inArray(u,v)<0){alert("File type is not allowed. Allowed only: "+o.allowedTypes);a(d).remove();return}r+=t;if(this.files.length!=0){r+=""}}}else{var t=a(this).val();q.push(t);var u=t.split(".").pop().toLowerCase();if(o.allowedTypes!="*"&&jQuery.inArray(u,v)<0){alert("File type is not allowed. Allowed only: "+o.allowedTypes);a(d).remove();return}r=t}a("body").append(d);a(g).after(k);a(b).html(r);var s=null;var p={forceSync:false,data:o.formData,dataType:o.returnType,beforeSend:function(x,w){o.onSubmit.call(this,q);a(n).show();a(m).hide();a(e).hide();if(o.showAbort){a(f).show();a(f).click(function(){x.abort()})}},uploadProgress:function(A,w,z,y){var x=y+"%";a(j).width(x)},success:function(x,w,y){a(f).hide();o.onSuccess.call(this,q,x,y);if(o.showStatusAfterSuccess){if(o.showDone){a(e).show();a(e).click(function(){a(k).hide("slow")})}else{a(e).hide()}a(j).width("100%")}else{a(k).hide("slow")}a(d).remove()},error:function(y,w,x){if(y.statusText=="abort"){a(k).hide("slow")}else{o.onError.call(this,q,w,x);a(n).hide();a(k).append("<font color='red'>ERROR: "+x+"</font>")}a(f).hide();a(d).remove()}};if(o.autoSubmit){a(d).ajaxSubmit(p)}else{if(o.showCancel){a(m).show();a(m).click(function(){a(d).remove();a(k).remove()})}a(d).ajaxForm(p)}});a(h).click()};if(a.fn.ajaxForm==undefined){
/*!
 * jQuery Form Plugin
 * version: 3.40.0-2013.08.13
 * @requires jQuery v1.5 or later
 * Copyright (c) 2013 M. Alsup
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
;(function(g){var d={};d.fileapi=g("<input type='file'/>").get(0).files!==undefined;d.formdata=window.FormData!==undefined;var f=!!g.fn.prop;g.fn.attr2=function(){if(!f){return this.attr.apply(this,arguments)}var h=this.prop.apply(this,arguments);if((h&&h.jquery)||typeof h==="string"){return h}return this.attr.apply(this,arguments)};g.fn.ajaxSubmit=function(m){if(!this.length){e("ajaxSubmit: skipping submit process - no element selected");return this}var l,E,o,r=this;if(typeof m=="function"){m={success:m}}else{if(m===undefined){m={}}}l=m.type||this.attr2("method");E=m.url||this.attr2("action");o=(typeof E==="string")?g.trim(E):"";o=o||window.location.href||"";if(o){o=(o.match(/^([^#]+)/)||[])[1]}m=g.extend(true,{url:o,success:g.ajaxSettings.success,type:l||g.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},m);var w={};this.trigger("form-pre-serialize",[this,m,w]);if(w.veto){e("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(m.beforeSerialize&&m.beforeSerialize(this,m)===false){e("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var p=m.traditional;if(p===undefined){p=g.ajaxSettings.traditional}var u=[];var G,H=this.formToArray(m.semantic,u);if(m.data){m.extraData=m.data;G=g.param(m.data,p)}if(m.beforeSubmit&&m.beforeSubmit(H,this,m)===false){e("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[H,this,m,w]);if(w.veto){e("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var A=g.param(H,p);if(G){A=(A?(A+"&"+G):G)}if(m.type.toUpperCase()=="GET"){m.url+=(m.url.indexOf("?")>=0?"&":"?")+A;m.data=null}else{m.data=A}var J=[];if(m.resetForm){J.push(function(){r.resetForm()})}if(m.clearForm){J.push(function(){r.clearForm(m.includeHidden)})}if(!m.dataType&&m.target){var n=m.success||function(){};J.push(function(q){var k=m.replaceTarget?"replaceWith":"html";g(m.target)[k](q).each(n,arguments)})}else{if(m.success){J.push(m.success)}}m.success=function(M,q,N){var L=m.context||this;for(var K=0,k=J.length;K<k;K++){J[K].apply(L,[M,q,N||r,r])}};if(m.error){var B=m.error;m.error=function(L,k,q){var K=m.context||this;B.apply(K,[L,k,q,r])}}if(m.complete){var j=m.complete;m.complete=function(K,k){var q=m.context||this;j.apply(q,[K,k,r])}}var F=g('input[type=file]:enabled:not([value=""])',this);var s=F.length>0;var D="multipart/form-data";var z=(r.attr("enctype")==D||r.attr("encoding")==D);var y=d.fileapi&&d.formdata;e("fileAPI :"+y);var t=(s||z)&&!y;var x;if(m.iframe!==false&&(m.iframe||t)){if(m.closeKeepAlive){g.get(m.closeKeepAlive,function(){x=I(H)})}else{x=I(H)}}else{if((s||z)&&y){x=v(H)}else{x=g.ajax(m)}}r.removeData("jqxhr").data("jqxhr",x);for(var C=0;C<u.length;C++){u[C]=null}this.trigger("form-submit-notify",[this,m]);return this;function h(M){var N=g.param(M,m.traditional).split("&");var q=N.length;var k=[];var L,K;for(L=0;L<q;L++){N[L]=N[L].replace(/\+/g," ");K=N[L].split("=");k.push([decodeURIComponent(K[0]),decodeURIComponent(K[1])])}return k}function v(q){var k=new FormData();for(var K=0;K<q.length;K++){k.append(q[K].name,q[K].value)}if(m.extraData){var N=h(m.extraData);for(K=0;K<N.length;K++){if(N[K]){k.append(N[K][0],N[K][1])}}}m.data=null;var M=g.extend(true,{},g.ajaxSettings,m,{contentType:false,processData:false,cache:false,type:l||"POST"});if(m.uploadProgress){M.xhr=function(){var O=g.ajaxSettings.xhr();if(O.upload){O.upload.addEventListener("progress",function(S){var R=0;var P=S.loaded||S.position;var Q=S.total;if(S.lengthComputable){R=Math.ceil(P/Q*100)}m.uploadProgress(S,P,Q,R)},false)}return O}}M.data=null;var L=M.beforeSend;M.beforeSend=function(P,O){O.data=k;if(L){L.call(this,P,O)}};return g.ajax(M)}function I(ah){var N=r[0],M,ad,X,af,aa,P,S,Q,R,ab,ae,V;var ak=g.Deferred();ak.abort=function(al){Q.abort(al)};if(ah){for(ad=0;ad<u.length;ad++){M=g(u[ad]);if(f){M.prop("disabled",false)}else{M.removeAttr("disabled")}}}X=g.extend(true,{},g.ajaxSettings,m);X.context=X.context||X;aa="jqFormIO"+(new Date().getTime());if(X.iframeTarget){P=g(X.iframeTarget);ab=P.attr2("name");if(!ab){P.attr2("name",aa)}else{aa=ab}}else{P=g('<iframe name="'+aa+'" src="'+X.iframeSrc+'" />');P.css({position:"absolute",top:"-1000px",left:"-1000px"})}S=P[0];Q={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(al){var am=(al==="timeout"?"timeout":"aborted");e("aborting upload... "+am);this.aborted=1;try{if(S.contentWindow.document.execCommand){S.contentWindow.document.execCommand("Stop")}}catch(an){}P.attr("src",X.iframeSrc);Q.error=am;if(X.error){X.error.call(X.context,Q,am,al)}if(af){g.event.trigger("ajaxError",[Q,X,am])}if(X.complete){X.complete.call(X.context,Q,am)}}};af=X.global;if(af&&0===g.active++){g.event.trigger("ajaxStart")}if(af){g.event.trigger("ajaxSend",[Q,X])}if(X.beforeSend&&X.beforeSend.call(X.context,Q,X)===false){if(X.global){g.active--}ak.reject();return ak}if(Q.aborted){ak.reject();return ak}R=N.clk;if(R){ab=R.name;if(ab&&!R.disabled){X.extraData=X.extraData||{};X.extraData[ab]=R.value;if(R.type=="image"){X.extraData[ab+".x"]=N.clk_x;X.extraData[ab+".y"]=N.clk_y}}}var W=1;var T=2;function U(an){var am=null;try{if(an.contentWindow){am=an.contentWindow.document}}catch(al){e("cannot get iframe.contentWindow document: "+al)}if(am){return am}try{am=an.contentDocument?an.contentDocument:an.document}catch(al){e("cannot get iframe.contentDocument: "+al);am=an.document}return am}var L=g("meta[name=csrf-token]").attr("content");var K=g("meta[name=csrf-param]").attr("content");if(K&&L){X.extraData=X.extraData||{};X.extraData[K]=L}function ac(){var an=r.attr2("target"),al=r.attr2("action");N.setAttribute("target",aa);if(!l){N.setAttribute("method","POST")}if(al!=X.url){N.setAttribute("action",X.url)}if(!X.skipEncodingOverride&&(!l||/post/i.test(l))){r.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(X.timeout){V=setTimeout(function(){ae=true;Z(W)},X.timeout)}function ao(){try{var at=U(S).readyState;e("state = "+at);if(at&&at.toLowerCase()=="uninitialized"){setTimeout(ao,50)}}catch(au){e("Server abort: ",au," (",au.name,")");Z(T);if(V){clearTimeout(V)}V=undefined}}var am=[];try{if(X.extraData){for(var ar in X.extraData){if(X.extraData.hasOwnProperty(ar)){if(g.isPlainObject(X.extraData[ar])&&X.extraData[ar].hasOwnProperty("name")&&X.extraData[ar].hasOwnProperty("value")){am.push(g('<input type="hidden" name="'+X.extraData[ar].name+'">').val(X.extraData[ar].value).appendTo(N)[0])}else{am.push(g('<input type="hidden" name="'+ar+'">').val(X.extraData[ar]).appendTo(N)[0])}}}}if(!X.iframeTarget){P.appendTo("body");if(S.attachEvent){S.attachEvent("onload",Z)}else{S.addEventListener("load",Z,false)}}setTimeout(ao,15);try{N.submit()}catch(ap){var aq=document.createElement("form").submit;aq.apply(N)}}finally{N.setAttribute("action",al);if(an){N.setAttribute("target",an)}else{r.removeAttr("target")}g(am).remove()}}if(X.forceSync){ac()}else{setTimeout(ac,10)}var ai,aj,ag=50,O;function Z(ar){if(Q.aborted||O){return}aj=U(S);if(!aj){e("cannot access response document");ar=T}if(ar===W&&Q){Q.abort("timeout");ak.reject(Q,"timeout");return}else{if(ar==T&&Q){Q.abort("server abort");ak.reject(Q,"error","server abort");return}}if(!aj||aj.location.href==X.iframeSrc){if(!ae){return}}if(S.detachEvent){S.detachEvent("onload",Z)}else{S.removeEventListener("load",Z,false)}var ap="success",au;try{if(ae){throw"timeout"}var ao=X.dataType=="xml"||aj.XMLDocument||g.isXMLDoc(aj);e("isXml="+ao);if(!ao&&window.opera&&(aj.body===null||!aj.body.innerHTML)){if(--ag){e("requeing onLoad callback, DOM not available");setTimeout(Z,250);return}}var av=aj.body?aj.body:aj.documentElement;Q.responseText=av?av.innerHTML:null;Q.responseXML=aj.XMLDocument?aj.XMLDocument:aj;if(ao){X.dataType="xml"}Q.getResponseHeader=function(ay){var ax={"content-type":X.dataType};return ax[ay.toLowerCase()]};if(av){Q.status=Number(av.getAttribute("status"))||Q.status;Q.statusText=av.getAttribute("statusText")||Q.statusText}var al=(X.dataType||"").toLowerCase();var at=/(json|script|text)/.test(al);if(at||X.textarea){var aq=aj.getElementsByTagName("textarea")[0];if(aq){Q.responseText=aq.value;Q.status=Number(aq.getAttribute("status"))||Q.status;Q.statusText=aq.getAttribute("statusText")||Q.statusText}else{if(at){var am=aj.getElementsByTagName("pre")[0];var aw=aj.getElementsByTagName("body")[0];if(am){Q.responseText=am.textContent?am.textContent:am.innerText}else{if(aw){Q.responseText=aw.textContent?aw.textContent:aw.innerText}}}}}else{if(al=="xml"&&!Q.responseXML&&Q.responseText){Q.responseXML=Y(Q.responseText)}}try{ai=k(Q,al,X)}catch(an){ap="parsererror";Q.error=au=(an||ap)}}catch(an){e("error caught: ",an);ap="error";Q.error=au=(an||ap)}if(Q.aborted){e("upload aborted");ap=null}if(Q.status){ap=(Q.status>=200&&Q.status<300||Q.status===304)?"success":"error"}if(ap==="success"){if(X.success){X.success.call(X.context,ai,"success",Q)}ak.resolve(Q.responseText,"success",Q);if(af){g.event.trigger("ajaxSuccess",[Q,X])}}else{if(ap){if(au===undefined){au=Q.statusText}if(X.error){X.error.call(X.context,Q,ap,au)}ak.reject(Q,"error",au);if(af){g.event.trigger("ajaxError",[Q,X,au])}}}if(af){g.event.trigger("ajaxComplete",[Q,X])}if(af&&!--g.active){g.event.trigger("ajaxStop")}if(X.complete){X.complete.call(X.context,Q,ap)}O=true;if(X.timeout){clearTimeout(V)}setTimeout(function(){if(!X.iframeTarget){P.remove()}Q.responseXML=null},100)}var Y=g.parseXML||function(al,am){if(window.ActiveXObject){am=new ActiveXObject("Microsoft.XMLDOM");am.async="false";am.loadXML(al)}else{am=(new DOMParser()).parseFromString(al,"text/xml")}return(am&&am.documentElement&&am.documentElement.nodeName!="parsererror")?am:null};var q=g.parseJSON||function(al){return window["eval"]("("+al+")")};var k=function(aq,ao,an){var am=aq.getResponseHeader("content-type")||"",al=ao==="xml"||!ao&&am.indexOf("xml")>=0,ap=al?aq.responseXML:aq.responseText;if(al&&ap.documentElement.nodeName==="parsererror"){if(g.error){g.error("parsererror")}}if(an&&an.dataFilter){ap=an.dataFilter(ap,ao)}if(typeof ap==="string"){if(ao==="json"||!ao&&am.indexOf("json")>=0){ap=q(ap)}else{if(ao==="script"||!ao&&am.indexOf("javascript")>=0){g.globalEval(ap)}}}return ap};return ak}};g.fn.ajaxForm=function(h){h=h||{};h.delegation=h.delegation&&g.isFunction(g.fn.on);if(!h.delegation&&this.length===0){var j={s:this.selector,c:this.context};if(!g.isReady&&j.s){e("DOM not ready, queuing ajaxForm");g(function(){g(j.s,j.c).ajaxForm(h)});return this}e("terminating; zero elements found by selector"+(g.isReady?"":" (DOM not ready)"));return this}if(h.delegation){g(document).off("submit.form-plugin",this.selector,c).off("click.form-plugin",this.selector,b).on("submit.form-plugin",this.selector,h,c).on("click.form-plugin",this.selector,h,b);return this}return this.ajaxFormUnbind().bind("submit.form-plugin",h,c).bind("click.form-plugin",h,b)};function c(j){var h=j.data;if(!j.isDefaultPrevented()){j.preventDefault();g(this).ajaxSubmit(h)}}function b(m){var l=m.target;var j=g(l);if(!(j.is("[type=submit],[type=image]"))){var h=j.closest("[type=submit]");if(h.length===0){return}l=h[0]}var k=this;k.clk=l;if(l.type=="image"){if(m.offsetX!==undefined){k.clk_x=m.offsetX;k.clk_y=m.offsetY}else{if(typeof g.fn.offset=="function"){var n=j.offset();k.clk_x=m.pageX-n.left;k.clk_y=m.pageY-n.top}else{k.clk_x=m.pageX-l.offsetLeft;k.clk_y=m.pageY-l.offsetTop}}}setTimeout(function(){k.clk=k.clk_x=k.clk_y=null},100)}g.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};g.fn.formToArray=function(y,h){var x=[];if(this.length===0){return x}var m=this[0];var q=y?m.getElementsByTagName("*"):m.elements;if(!q){return x}var s,r,p,z,o,u,l;for(s=0,u=q.length;s<u;s++){o=q[s];p=o.name;if(!p||o.disabled){continue}if(y&&m.clk&&o.type=="image"){if(m.clk==o){x.push({name:p,value:g(o).val(),type:o.type});x.push({name:p+".x",value:m.clk_x},{name:p+".y",value:m.clk_y})}continue}z=g.fieldValue(o,true);if(z&&z.constructor==Array){if(h){h.push(o)}for(r=0,l=z.length;r<l;r++){x.push({name:p,value:z[r]})}}else{if(d.fileapi&&o.type=="file"){if(h){h.push(o)}var k=o.files;if(k.length){for(r=0;r<k.length;r++){x.push({name:p,value:k[r],type:o.type})}}else{x.push({name:p,value:"",type:o.type})}}else{if(z!==null&&typeof z!="undefined"){if(h){h.push(o)}x.push({name:p,value:z,type:o.type,required:o.required})}}}}if(!y&&m.clk){var t=g(m.clk),w=t[0];p=w.name;if(p&&!w.disabled&&w.type=="image"){x.push({name:p,value:t.val()});x.push({name:p+".x",value:m.clk_x},{name:p+".y",value:m.clk_y})}}return x};g.fn.formSerialize=function(h){return g.param(this.formToArray(h))};g.fn.fieldSerialize=function(j){var h=[];this.each(function(){var o=this.name;if(!o){return}var l=g.fieldValue(this,j);if(l&&l.constructor==Array){for(var m=0,k=l.length;m<k;m++){h.push({name:o,value:l[m]})}}else{if(l!==null&&typeof l!="undefined"){h.push({name:this.name,value:l})}}});return g.param(h)};g.fn.fieldValue=function(n){for(var m=[],k=0,h=this.length;k<h;k++){var l=this[k];var j=g.fieldValue(l,n);if(j===null||typeof j=="undefined"||(j.constructor==Array&&!j.length)){continue}if(j.constructor==Array){g.merge(m,j)}else{m.push(j)}}return m};g.fieldValue=function(h,p){var k=h.name,w=h.type,x=h.tagName.toLowerCase();if(p===undefined){p=true}if(p&&(!k||h.disabled||w=="reset"||w=="button"||(w=="checkbox"||w=="radio")&&!h.checked||(w=="submit"||w=="image")&&h.form&&h.form.clk!=h||x=="select"&&h.selectedIndex==-1)){return null}if(x=="select"){var q=h.selectedIndex;if(q<0){return null}var s=[],j=h.options;var m=(w=="select-one");var r=(m?q+1:j.length);for(var l=(m?q:0);l<r;l++){var o=j[l];if(o.selected){var u=o.value;if(!u){u=(o.attributes&&o.attributes.value&&!(o.attributes.value.specified))?o.text:o.value}if(m){return u}s.push(u)}}return s}return g(h).val()};g.fn.clearForm=function(h){return this.each(function(){g("input,select,textarea",this).clearFields(h)})};g.fn.clearFields=g.fn.clearInputs=function(h){var j=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var l=this.type,k=this.tagName.toLowerCase();if(j.test(l)||k=="textarea"){this.value=""}else{if(l=="checkbox"||l=="radio"){this.checked=false}else{if(k=="select"){this.selectedIndex=-1}else{if(l=="file"){if(/MSIE/.test(navigator.userAgent)){g(this).replaceWith(g(this).clone(true))}else{g(this).val("")}}else{if(h){if((h===true&&/hidden/.test(l))||(typeof h=="string"&&g(this).is(h))){this.value=""}}}}}}})};g.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||(typeof this.reset=="object"&&!this.reset.nodeType)){this.reset()}})};g.fn.enable=function(h){if(h===undefined){h=true}return this.each(function(){this.disabled=!h})};g.fn.selected=function(h){if(h===undefined){h=true}return this.each(function(){var j=this.type;if(j=="checkbox"||j=="radio"){this.checked=h}else{if(this.tagName.toLowerCase()=="option"){var k=g(this).parent("select");if(h&&k[0]&&k[0].type=="select-one"){k.find("option").selected(false)}this.selected=h}}})};g.fn.ajaxSubmit.debug=false;function e(){if(!g.fn.ajaxSubmit.debug){return}var h="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(h)}else{if(window.opera&&window.opera.postError){window.opera.postError(h)}}}})((typeof(jQuery)!="undefined")?jQuery:window.Zepto)}}(jQuery));

/*********
********
************ change shortcod content dynamically
********
***********/
function userpro_shortcode_template( method, container, shortcode, up_username, force_redirect_uri, post_id ) {
	str = 'action=userpro_shortcode_template&shortcode='+shortcode;
	if (up_username) {
		str = str + '&up_username='+up_username;
	}
	if (post_id) {
		str = str + '&post_id='+post_id;
	}
	if (force_redirect_uri){
		str = str + '&force_redirect_uri=1';
	}
	if (container.find('form').length > 0){
		var form = container.find('form');
		userpro_init_load( form );
	}
	jQuery.ajax({
		url: userpro_ajax_url,
		data: str,
		dataType: 'JSON',
		type: 'POST',
		error: function(xhr, status, error){
			userpro_end_load( form );
			alert(error);
		},
		success:function(data){
			if (method == 'insert') { // overlay
				jQuery(container).html( data.response );
			}
			if (method == 'update') { // update
				jQuery(container).replaceWith( data.response );
				userpro_end_load( form );
			}

			userpro_responsive();
			userpro_chosen();
			userpro_fluid_videos();
			userpro_ajax_picupload();
			if(typeof(userpro_media_manager)=='function')
			{	
				userpro_media_manager();
			}
			jQuery('.userpro form').each(function(){
			userpro_collapse( jQuery(this) );
			});
			userpro_overlay_center('.userpro-overlay-inner');
            $$('body').trigger('reset_form')
		}
	});
}

/*********
********
************ fluid videos
********
***********/
function userpro_fluid_videos(){
   var $allVideos = jQuery(".userpro iframe, .userpro object, .userpro embed"),
    $fluidEl = jQuery(".userpro-input");	
	$allVideos.each(function() {
	  jQuery(this)
	    // jQuery .data does not work on object/embed elements
	    .attr('data-aspectRatio', this.height / this.width)
	    .removeAttr('height')
	    .removeAttr('width');
	});
	 var newWidth = $fluidEl.width();
	  $allVideos.each(function() {
	    var $el = jQuery(this);
	    $el
	        .width( jQuery(this).parents('.userpro-input').width() )
	        .height( jQuery(this).parents('.userpro-input').width() * $el.attr('data-aspectRatio'));
	  
	});
}

/*********
********
************ ajax picture upload
********
***********/
function userpro_ajax_picupload(){
jQuery(".userpro-pic-upload").each(function(){
	var allowed = jQuery(this).data('allowed_extensions');
	var filetype = jQuery(this).data('filetype');
	var form = jQuery(this).parents('.userpro').find('form');
	jQuery(this).uploadFile({
		url: userpro_upload_url,
		allowedTypes: allowed,
		onSubmit:function(files){
			var statusbar = jQuery('.ajax-file-upload-statusbar:visible');
			statusbar.parents('.userpro-input').find('.red').hide();
			if (statusbar.parents('.userpro-input').find('img.default').length){
			statusbar.parents('.userpro-input').find('img.default').show();
			statusbar.parents('.userpro-input').find('img.modified').remove();
			}
		},
		onSuccess:function(files,data,xhr){
		
			data= jQuery.parseJSON(data);
			
			var statusbar = jQuery('.ajax-file-upload-statusbar:visible');
			var src = data.target_file_uri;
			if (statusbar.parents('.userpro-input').find('img.default').length){
			var width = statusbar.parents('.userpro-input').find('img.default').attr('width');
			var height = statusbar.parents('.userpro-input').find('img.default').attr('height');
			} else if (statusbar.parents('.userpro-input').find('img.modified').length){
			var width = statusbar.parents('.userpro-input').find('img.modified').attr('width');
			var height = statusbar.parents('.userpro-input').find('img.modified').attr('height');
			} else if (statusbar.parents('.userpro-input').find('img.avatar').length){
			var width = statusbar.parents('.userpro-input').find('img.avatar').attr('width');
			var height = statusbar.parents('.userpro-input').find('img.avatar').attr('height');
			}
			
			str = 'action=userpro_crop_picupload&filetype='+filetype+'&width='+width+'&height='+height+'&src='+src;
			jQuery.ajax({
				url: userpro_ajax_url,
				data: str,
				dataType: 'JSON',
				type: 'POST',
				success:function(data){
					statusbar.prev().after("<input type='button' value='" + statusbar.parents('.userpro-input').find('.userpro-pic').data('remove_text') + "' class='userpro-button red' style='display:none' />");
					statusbar.prev().fadeIn( function() {
						if (filetype == 'picture'){
							
							statusbar.parents('.userpro-input').find('img').attr('src', data.response );
							statusbar.parents('.userpro-input').find('img').removeClass('no_feature');
						
						} else if (filetype == 'file'){
							
							statusbar.parents('.userpro-input').find('.userpro-file-input').remove();
							statusbar.parents('.userpro-input').prepend( data.response );
						
						}
						statusbar.hide();
					});
					statusbar.parents('.userpro-input').find('input:hidden').val( src );
					statusbar.parents('.userpro-input').find('.userpro-pic-none').hide();
					
					// re-validate
					form.find('input').each(function(){
						jQuery(this).trigger('blur');
					});
					
				}
			});

		}
	});
});
}

/*********
********
************ password strength meter
********
***********/
function userpro_password_strength_meter(element){
		var meter = element.parents('.userpro').find(".userpro-field[data-key^='passwordstrength']");
		var meter_data = meter.find('span.strength-text').data();
		var meter_text = meter.find('span.strength-text');
		var password = element.val();
		var LOWER = /[a-z]/,
			UPPER = /[A-Z]/,
			DIGIT = /[0-9]/,
			DIGITS = /[0-9].*[0-9]/,
			SPECIAL = /[^a-zA-Z0-9]/,
			SAME = /^(.)\1+$/;
		var lower = LOWER.test(password),
			upper = UPPER.test( password.substring(0, 1).toLowerCase() + password.substring(1) ),
			digit = DIGIT.test(password),
			digits = DIGITS.test(password),
			special = SPECIAL.test(password);
		if (meter.length > 0 ) {
			if  ( password.length < 8 ) {
				meter.find('.strength-plain').removeClass('fill');
				meter_text.html( meter_data['too_short'] );
				return 0;
			} else if ( SAME.test(password) ) {
				meter.find('.strength-plain').removeClass('fill');
				meter.find('.strength-plain:eq(0)').addClass('fill');
				meter_text.html( meter_data['very_weak'] );
				return 1;
			} else if ( lower && upper && digit && special ) {
				meter.find('.strength-plain').removeClass('fill');
				meter.find('.strength-plain').addClass('fill');
				meter_text.html( meter_data['very_strong'] );
				return 5;
			} else if ( lower && upper && digit || lower && digits || upper && digits || special ) {
				meter.find('.strength-plain').removeClass('fill');
				meter.find('.strength-plain:eq(0),.strength-plain:eq(1),.strength-plain:eq(2),.strength-plain:eq(3)').addClass('fill');
				meter_text.html( meter_data['strong'] );
				return 4;
			} else if (lower && upper || lower && digit || upper && digit) {
				meter.find('.strength-plain').removeClass('fill');
				meter.find('.strength-plain:eq(0),.strength-plain:eq(1),.strength-plain:eq(2)').addClass('fill');
				meter_text.html( meter_data['good'] );
				return 3;
			} else {
				meter.find('.strength-plain').removeClass('fill');
				meter.find('.strength-plain:eq(0),.strength-plain:eq(1)').addClass('fill');
				meter_text.html( meter_data['weak'] );
				return 2;
			}	
		}
}

/*********
********
************ setup chosen dropdowns
********
***********/
function userpro_chosen(){

	jQuery(".userpro select, .emd-filters select").removeClass("chzn-done").css('display', 'inline').data('chosen', null);
	jQuery('.userpro, .emd-filters').find("*[class*=chzn], .chosen-container").remove();
	jQuery(".chosen-select").chosen({
		disable_search_threshold: 10,
		width: '100%'
	});
	jQuery(".chosen-select-compact").chosen({
		disable_search: 1,
		width: '100%'
	});
	
	/**
		Tooltips 
	**/
	// jQuery('.userpro-tip-fade').tipsy({
	// 	offset: 2,
	// 	fade: true,
	// 	opacity: 1
	// });
	
	// jQuery('span.userpro-tip, .userpro-tip').tipsy({
	// 	offset: 5,
	// 	fade: true,
	// 	opacity: 1,
	// });
	
	jQuery('.userpro-profile-badge').tipsy({
		offset: 3,
		fade: true,
		opacity: 1,
	});
	
	jQuery('.userpro-profile-badge-right').tipsy({
		offset: 3,
		fade: true,
		opacity: 1,
		gravity: 'w',
	});
	
}

/*********
********
************ userpro responsiveness
********
***********/
function userpro_responsive(){

	/* Tweaking compact head/profile */
	jQuery('.userpro-is-responsive').each(function(){
	
		var upro = jQuery(this);
		if (upro.width() <= 400) {
			upro.addClass('userpro-centered-c').removeClass('userpro-head');
			upro.find('*').addClass('userpro-force-center');
			upro.find('.userpro-profile-name').addClass('small');
		} else {
			upro.removeClass('userpro-centered-c').addClass('userpro-head');
			upro.find('*').removeClass('userpro-force-center');
			upro.find('.userpro-profile-name').removeClass('small');
		}
		
	});
	
	/* General form responsiveness */
	jQuery('.userpro').each(function(){
	
		var upro = jQuery(this);
		
		if (upro.width() <= 400 && upro.width() > 0 && upro.data('layout') == 'float' ){
			upro.removeClass('userpro-float');
		} else if (upro.data('layout') == 'float') {
			upro.addClass('userpro-float');
		}
		
		if (upro.width() <= 400 && upro.width() > 0){
			if ( upro.find('.userpro-label').hasClass('iconed')) {
				upro.find('.userpro-field-icon').hide();
				upro.find('.userpro-label').removeClass('iconed');
			}
			upro.find('.userpro-submit').find('input').addClass('fullwidth-block');
			upro.find('.userpro-social-connect').addClass('fullwidth-block-social');
		} else {
			upro.find('.userpro-submit').find('input').removeClass('fullwidth-block');
			upro.find('.userpro-social-connect').removeClass('fullwidth-block-social');
		}
		
	});
	
	/* Elegant member dir */
	jQuery('.userpro-users-v2').each(function(){
				
		var elem = jQuery(this);
		if (elem.width() <= 700) {
			elem.find('.userpro-awsm').css({'width': '40%', 'margin-left': '15px', 'margin-right': '15px'});
		}
		if (elem.width() <= 560) {
			elem.find('.userpro-awsm').css({'width': '100%', 'margin-left': 0, 'margin-right': 0});
		}
		if (elem.width() > 700){
			elem.find('.userpro-awsm').css({'width': '25%', 'margin-left': '15px', 'margin-right': '15px'});
		}
		
		equalHeight( jQuery(this).find('.userpro-awsm-bio') );
		equalHeight( jQuery(this).find('.userpro-awsm span.userpro-badges') );
		equalHeight( jQuery(this).find('.userpro-awsm-social') );
		
	});
	
	/* User posts */
	jQuery('.userpro-post-wrap').each(function(){
				
		var elem = jQuery(this);
		if (elem.width() <= 700) {
			elem.find('.userpro-post:not(.userpro-post-compact)').css({'width': '40%', 'margin-left': '10px', 'margin-right' : '10px'});
		}
		if (elem.width() <= 400) {
			elem.find('.userpro-post:not(.userpro-post-compact)').css({'width': '100%', 'margin-left': 0, 'margin-right': 0});
		}
		if (elem.width() > 700){
			elem.find('.userpro-post:not(.userpro-post-compact)').css({'width': '25%',  'margin-left': '10px', 'margin-right' : '10px'});
		}
				
	});
	


	/**
		Datepicker
	**/
	jQuery('input[data-fieldtype=datepicker]').datepicker({
		dateFormat: 'dd-mm-yy',
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		yearRange: 'c-65:c+0'
    });
		
}

/*********
********
************ equal heights
********
***********/
function equalHeight(group) {
   tallest = 0;
   group.each(function() {
	jQuery(this).height('auto');
      thisHeight = jQuery(this).height();
      if(thisHeight > tallest) {
         tallest = thisHeight;
      }
   });
   group.height(tallest);
}

/*********
********
************ center the overlay popup
********
***********/
function userpro_overlay_center(container){
	if (container.length) {
		jQuery(container).imagesLoaded(function(){
			jQuery(container).animate({
				'top' : jQuery(window).innerHeight() / 2,
				'margin-top' : '-' + jQuery(container).find('.userpro').innerHeight() / 2 + 'px'
			});
		});
	}
}

/*********
********
************ collapse and maximize field groups
********
***********/
function userpro_collapse( form ){
	form.find('.userpro-section').each(function(){
		if (jQuery(this).next('div.userpro-field:not(.userpro-submit)').length == 0){
			jQuery(this).hide();
		} else {

		}
	});
	form.find('.userpro-collapsible-1.userpro-collapsed-1').each(function(){
		jQuery(this).nextUntil('div.userpro-column').hide();
		if (jQuery(this).find('span').length==0) jQuery(this).prepend('<span><i class="userpro-icon-angle-right"></i></span>');
	});
	form.find('.userpro-collapsible-1.userpro-collapsed-0').each(function(){
		jQuery(this).nextUntil('div.userpro-column').show();
		if (jQuery(this).find('span').length==0) jQuery(this).prepend('<span><i class="userpro-icon-angle-down"></i></span>');
	});
}

/*********
********
************ side validate element with value and ajax check
********
***********/
function userpro_side_validate( element, input_value, ajaxcheck ) {
	str = 'action=userpro_side_validate&input_value='+input_value.replace(/&/g, "%26")+'&ajaxcheck='+ajaxcheck+'&element='+element;
	jQuery.ajax({
		url: userpro_ajax_url,
		data: str,
		dataType: 'JSON',
		type: 'POST',
		success:function(data){
			var parent = element.parents('.userpro-input');
			if (data && data.error){
				userpro_client_error(element, parent, data.error);
			} else {
				userpro_client_valid(element, parent);
			}
			userpro_overlay_center('.userpro-overlay-inner');
		}
	});
}

/*********
********
************ return an error to client side
********
***********/
function userpro_client_error( element, parent, error) {

	if (element.data('custom-error')) {
		error = element.data('custom-error');
	}
	
	if ( element.attr('type') ) {
	
		if (element.attr('type') == 'hidden') {
			
			parent.find('.icon-ok').remove();
			if (parent.find('.userpro-warning').length==0) {
				element.addClass('warning').removeClass('ok');
				parent.append('<div class="userpro-warning"><i class="userpro-icon-caret-up"></i>' + error + '</div>');
				parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
			} else {
				parent.find('.userpro-warning').html('<i class="userpro-icon-caret-up"></i>' + error );
				parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
			}
			
		} else {
		
			parent.find('.icon-ok').remove();
			if (parent.find('.userpro-warning').length==0) {
				element.addClass('warning').removeClass('ok');
				element.after('<div class="userpro-warning"><i class="userpro-icon-caret-up"></i>' + error + '</div>');
				parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
			} else {
				parent.find('.userpro-warning').html('<i class="userpro-icon-caret-up"></i>' + error );
				parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
			}
		
		}

	} else {
	
		// select
		if (parent.find('.userpro-warning').length == 0) {
		parent.find('.chosen-container').after( '<div class="userpro-warning"><i class="userpro-icon-caret-up"></i>' + error + '</div>' );
		parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
		} else {
		parent.find('.userpro-warning').html('<i class="userpro-icon-caret-up"></i>' + error );
		parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
		}
		
	}
	userpro_overlay_center('.userpro-overlay-inner');
}

/*********
********
************ return an error to client side / radio
********
***********/
function userpro_client_error_irregular( element, parent, error) {

	if ( element != '' && element.data('custom-error')) {
		error = element.data('custom-error');
	}
	
	if (parent.find('.userpro-warning').length == 0) {
		parent.append( '<div class="userpro-warning"><i class="userpro-icon-caret-up"></i>' + error + '</div>' );
		parent.find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
	}
	
	userpro_overlay_center('.userpro-overlay-inner');
	
}

/*********
********
************ return a valid field callback
********
***********/
function userpro_client_valid( element, parent) {
	if ( element.attr('type') ) {
	
		if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
		
			parent.find('.userpro-warning').remove();
			element.removeClass('warning').addClass('ok');
			
		} else {
		
			parent.find('.userpro-warning').remove();
			element.removeClass('warning').addClass('ok');
			if (parent.find('.icon-ok').length==0){
				if (element.val() != '') {
				parent.append('<div class="icon-ok"><i class="userpro-icon-ok"></i></div>');
				} else {
				parent.find('.icon-ok').remove();
				}
			}
	
		}
		
	} else {
		
		parent.find('.userpro-warning').remove();
		
	}
	userpro_overlay_center('.userpro-overlay-inner');
}

/*********
********
************ clear form
********
***********/
function userpro_clear_form( form ) {
	form.find('.userpro-warning').remove();
	form.find('input,select,textarea').removeClass('warning').addClass('ok');
}

/*********
********
************ clear inputs
********
***********/
function userpro_clear_input( element ) {
	element.parents('.userpro-input').find('.userpro-warning').remove();
	element.removeClass('warning');
}

/*********
********
************ init loading on shortcode
********
***********/
function userpro_init_load(form) {
	//form.parents('.userpro').find('.userpro-message-ajax').hide();
	form.find('input[type=submit],input[type=button]').attr('disabled','disabled');
	form.parents('.userpro').find('img.userpro-loading').show().addClass('inline');
}

/*********
********
************ end loading on shortcode
********
***********/
function userpro_end_load(form) {
	jQuery('.tipsy').remove();
	form.find('input[type=submit],input[type=button]').removeAttr('disabled');
	form.parents('.userpro').find('img.userpro-loading').hide().removeClass('inline');
}

/*********
********
************ result modal / confirmation
********
***********/
function userpro_overlay_confirmation(message){

	if (jQuery('.userpro-modal-inner').length){
		jQuery('.userpro-modal-inner').remove();
	}
	jQuery('body').append('<div class="userpro-modal-inner"><i class="userpro-icon-ok"></i><i class="userpro-icon-remove"></i>' + message + '</div>');
	jQuery('.userpro-modal-inner').css({
		'margin-top' : '-' + jQuery('.userpro-modal-inner').innerHeight() / 2 + 'px',
		'opacity' : 1
	});
	
}

/* Custom JS starts here */
jQuery(document).ready(function() {

	/**
		Modal Close
	**/
	jQuery(document).on('click',function(){
		if (jQuery('.userpro-modal-inner').length > 0){
			jQuery('.userpro-modal-inner').remove();
		}
	});

	jQuery(document).on('click', 'div.userpro-modal-inner i.userpro-icon-remove', function(e){
		jQuery('.userpro-modal-inner').remove();
	});

	/**
		Remove status
	**/
	jQuery(document).on('click', '.userpro-bar-success i, .userpro-bar-failed i', function(e){
		jQuery(this).parent().slideToggle('fast');
	});
	
	/**
		Icons
	**/
	jQuery(document).on('mouseenter', '.userpro-field', function(e){
		if (jQuery(this).find('.userpro-field-icon').length){
			jQuery(this).find('.userpro-field-icon').addClass('icon-active');
		}
	});
	
	jQuery(document).on('mouseleave', '.userpro-field', function(e){
		if (jQuery(this).find('.userpro-field-icon').length){
			jQuery(this).find('.userpro-field-icon').removeClass('icon-active');
		}	
	});
	
	/**
		toggle notice
	**/
	jQuery(document).on('click', 'a.userpro-alert-close', function(e){
		jQuery(this).parents('.userpro-alert').slideUp(200);
	});
	
	/**
	fade for online
	users list
	**/
	jQuery(document).on('mouseenter', '.userpro-online-i', function(e){
		jQuery(this).find('.userpro-online-i-thumb').fadeTo('fast', 0.7);
	})
	
	jQuery(document).on('mouseleave', '.userpro-online-i', function(e){
		jQuery(this).find('.userpro-online-i-thumb').fadeTo('fast', 1);
	});
	
	/**
	fade for posts by user
	**/
	jQuery(document).on('mouseenter', '.userpro-post:not(.userpro-post-compact)', function(e){
		jQuery(this).find('span.shadowed').stop().animate({ 'height' : '100%' }, function(){
			jQuery(this).parent().find('span.iconed').fadeIn('slow');
		});
	})
	
	jQuery(document).on('mouseleave', '.userpro-post:not(.userpro-post-compact)', function(e){
		jQuery(this).find('span.iconed').hide();
		jQuery(this).find('span.shadowed').stop().animate({ 'height' : '0' }, 200);
	});
	
	jQuery(document).on('click', '.userpro-alert-edit', function(e){
		jQuery('.userpro-alert-input').fadeIn();
	});
	
	/**
		save notice
	**/
	jQuery(document).on('click', '.userpro-alert input[type=button]', function(e){
		var parent = jQuery(this).parents('.userpro-alert');
		var content = jQuery(this).parents('.userpro-alert').find('.userpro-alert-content');
		var value = jQuery(this).parents('.userpro-alert').find('input[type=text]').val();
		var user_id = jQuery(this).parents('.userpro-alert').data('user_id');
		jQuery.ajax({
				url: userpro_ajax_url,
				data: 'action=userpro_save_userdata&field=userpro_alert&value='+value+'&user_id='+user_id,
				dataType: 'JSON',
				type: 'POST',
				success:function(data){
					if (content.length == 0){
						parent.prepend('<div class="userpro-alert-content">'+data.res+'</div>');
					} else {
						content.html( data.res );
					}
					parent.find('.userpro-alert-input').fadeOut();
				}
		});
	});
	
	/**
		facebook login trigger
	**/
	jQuery(document).on('click', '.userpro-social-facebook', function(e){
		Login( jQuery(this) );
	});

	/**
		denies click behaviours
		#, redirection, etc.
	**/
	jQuery(document).on('click', "*[class^='popup-'], a[href='#']", function(e){
		e.preventDefault();
		return false;
	});

	/**
		remove and fade overlay
		when clicking outside overlay
	**/
	jQuery(document).on('click', '.userpro-overlay, a.userpro-close-popup', function(e){
		jQuery('.userpro-overlay').fadeOut(function(){jQuery('.userpro-overlay').remove()});
		jQuery('.userpro-overlay-inner').fadeOut(function(){jQuery('.userpro-overlay-inner').remove()});
	});
	
	/**
		denies submission of form
	**/
	jQuery(document).on('submit', '.userpro form:not(.userpro-search-form)', function(e){
		e.preventDefault();
		return false;
	});
	
	/**
		Animation on users list with images only
	**/
	jQuery(document).on('mouseenter', '.userpro-user', function(e){
		if (jQuery(this).data('pic_size') > 100 ) {
		jQuery(this).find('span').animate({top: 0}, 200);
		} else {
		jQuery(this).find('a.userpro-user-img').fadeTo('fast', 0.70);
		}
		jQuery(this).find('.userpro-user-link').css({opacity: 1});
	})
	
	jQuery(document).on('mouseleave', '.userpro-user', function(e){
		if (jQuery(this).data('pic_size') > 100 ) {
		jQuery(this).find('span').animate({top: '-' + jQuery(this).parents('.userpro').data('memberlist_pic_size') + 'px'}, 50);
		} else {
		jQuery(this).find('a.userpro-user-img').fadeTo(1, 1);
		}
		jQuery(this).find('.userpro-user-link').css({opacity: 0});
	});

	/**
		load templates easily via data-template attribute
		any anchor or input with data-template
	**/
	jQuery(document).on('click', 'a,input', function(e){
	
		if (jQuery(this).data('template')) {
			form_data = jQuery(this).parents('.userpro').data();
			var id = jQuery('.userpro').length;
			shortcode = '[userpro id=' + id;
			jQuery.each( form_data, function(key, value) {
				shortcode = shortcode + ' ' + key + '=' + '"' + value + '"';
			});
			shortcode = shortcode + ']';
			shortcode = shortcode.replace(/(template=)"(.*?)"/, 'template="' + jQuery(this).data('template') + '"');
			if (jQuery(this).data('up_username')) {
				up_username = jQuery(this).data('up_username');
			} else {
				up_username = 0;
			}
			if (jQuery(this).data('force_redirect_uri')) {
				force_redirect_uri = jQuery(this).data('force_redirect_uri');
			} else {
				force_redirect_uri = 0;
			}
			post_id = jQuery(this).parents('.userpro').data('post_id');
			userpro_shortcode_template( 'update', jQuery(this).parents('.userpro'), shortcode, up_username, force_redirect_uri, post_id);
		}
	});
	
	/** Clear search form
	**/
	jQuery('.userpro-clear-search').click(function(){
		var search = jQuery(this).parents('.userpro-search-form');
		search.find('input[type=text]').val('');
		search.find('select').val('');
		search.trigger('submit');
	});
	
	/**
		the hard part
		processing forms via ajax
	**/
	jQuery(document).on('submit', '.userpro form:not(.userpro-search-form)', function(e){
		var form = jQuery(this);

		// Trigger validation client side
		if ( form.data('action') != 'login' && 
			form.data('action') != 'reset' && 
			form.data('action') != 'delete' ) {

			// re-validate
			form.find('input,textarea').each(function(){
				jQuery(this).trigger('blur');
			});
			
			form.find('select').each(function(){
				jQuery(this).trigger('change');
			});
			
			form.find('select[data-required=1],textarea[data-required=1]').each(function(){
				if ( !jQuery(this).val() ) {
					userpro_client_error_irregular( jQuery(this), jQuery(this).parents('.userpro-input'), jQuery(this).parents('.userpro').data('required_text') );
				} else {
					userpro_client_valid( jQuery(this).find("select"), jQuery(this).parents('.userpro-input') );
				}
			});
			
			form.find('.userpro-radio-wrap[data-required=1]').each(function(){
				if ( !jQuery(this).find("input:radio").is(":checked") ) {
					userpro_client_error_irregular( '', jQuery(this).parents('.userpro-input'), jQuery(this).parents('.userpro').data('required_text') );
				} else {
					userpro_client_valid( jQuery(this).find("input:radio"), jQuery(this).parents('.userpro-input') );
				}
			});
			
			form.find('.userpro-checkbox-wrap[data-required=1]').each(function(){
				if ( !jQuery(this).find("input:checkbox").is(":checked") ) {
					userpro_client_error_irregular( '', jQuery(this).parents('.userpro-input'), jQuery(this).parents('.userpro').data('required_text') );
				} else {
					userpro_client_valid( jQuery(this).find("input:checkbox"), jQuery(this).parents('.userpro-input') );
				}
			});
			
			form.find('.userpro-maxwidth[data-required=1]').each(function(){
				if ( !jQuery(this).find("input:checkbox").is(":checked") ) {
					userpro_client_error_irregular( '', jQuery(this).find('.userpro-input'), jQuery(this).data('required_msg') );
				} else {
					userpro_client_valid( jQuery(this).find("input:checkbox"), jQuery(this).find('.userpro-input') );
				}
			});
			
			if (form.find('.userpro-warning').length > 0){
				form.find('.userpro-section').each(function(){
					jQuery(this).find('.userpro-section-warning').remove();
					if (jQuery(this).nextUntil('div.userpro-column').find('.userpro-warning').length > 0) {
						jQuery(this).css({'display': 'block'});
						jQuery(this).append('<ins class="userpro-section-warning">Please correct fields</ins>');
						jQuery(this).find('.userpro-section-warning').fadeIn();
					}
				});
				form.find('.userpro-warning:first').parents('.userpro-input').find('input').focus();
				return false;
			} else {
				form.find('.userpro-section').each(function(){
					jQuery(this).find('.userpro-section-warning').remove();
				});
			}
			
		// Done
		} else {
		
			userpro_clear_form( form );
		
		}
		
		// start load
		userpro_init_load( form );

		// form data and shortcode
		form_data = jQuery(this).parents('.userpro').data();
		shortcode = '[userpro';
		jQuery.each( form_data, function(key, value) {
			shortcode = shortcode + ' ' + key + '=' + '"' + value + '"';
		});
		shortcode = shortcode + ']';
		
		// username
		if (jQuery(this).parents('.userpro').find('.userpro-profile-img-btn a').data('up_username')) {
			up_username = jQuery(this).parents('.userpro').find('.userpro-profile-img-btn a').data('up_username');
		} else {
			up_username = 0;
		}
		
		jQuery.ajax({
			url: userpro_ajax_url,
			data: form.serialize() + "&action=userpro_process_form&template="+form_data['template']+"&group="+form_data[ form_data['template'] + '_group' ]+"&shortcode="+shortcode+'&up_username='+up_username,
			dataType: 'JSON',
			type: 'POST',
			error: function(xhr, status, error){
				userpro_end_load( form );
				alert(error);
			},
			success:function(data){

				userpro_end_load( form );

				/* server-side error */
				if (data && data.error){
					
					var i = 0;
					jQuery.each( data.error, function(key, value) {
						i++;
						element = form.find('.userpro-field[data-key="'+key+'"]').find('input');
						parent = element.parents('.userpro-input');
						if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox' ){
							userpro_client_error_irregular( element, parent, value );
						} else {
							if (i==1) element.focus();
							userpro_client_error( element, parent, value );
						}
						
						if (key == 'userpro_editor') {
							if (form.find('.userpro-field-editor .userpro-input').find('.userpro-warning').length){
							form.find('.userpro-field-editor .userpro-input').find('.userpro-warning').html(value);
							form.find('.userpro-field-editor .userpro-input').find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
							} else {
							form.find('.userpro-field-editor .userpro-input').append('<div class="userpro-warning"><i class="userpro-icon-caret-up"></i>' + value + '</div>');
							form.find('.userpro-field-editor .userpro-input').find('.userpro-warning').css({'top' : '0px', 'opacity' : '1'});
							}
						}
						
					});
				
				}
				
				/* custom message */
				if (data && data.custom_message && data.custom_message != '' ){
					form.parents('.userpro').find('.userpro-body').find('.userpro-message').remove();
					form.parents('.userpro').find('.userpro-body').prepend( data.custom_message );
				}
				
				/* redirect after form */
				if ( data && data.redirect_uri && data.redirect_uri != '' ){
					if (data.redirect_uri =='refresh') {
						document.location.href=jQuery(location).attr('href');
					} else {
						document.location.href=data.redirect_uri;
					}
				}
				
				/* show modal confirmation */
				if (form_data['template'] == 'publish' && data.modal_msg ){
					userpro_overlay_confirmation( data.modal_msg );
				}
				
				/* display template */
				if (data && data.template && data.template != '' ){
                    // form.parents('.userpro').replaceWith( data.template );
					form.parents('.userpro').remove();
                    $$('body').addClass('register-submitted');
                    notify('success', 'Your email is pending verification. Please activate your account.')
					
					/* show modal confirmation */
					if (form_data['template'] == 'edit'){
						userpro_overlay_confirmation( form.parents('.userpro').data('modal_profile_saved') );
					}

				}
				
				/* clear publish form (stop spam) */
				if ( !data && !data.error && form_data['template'] == 'publish' ) {
					form.find('input,textarea').not('input[type=submit],input[type=hidden],input[type=button]').val('');
					form.find('div.userpro-pic-post_featured_image img').addClass('no_feature');
					form.find('.userpro-button.red').remove();
					form.find('.icon-ok').remove();
				}

				/* reinitialise */
				userpro_responsive();
				userpro_chosen();
				userpro_fluid_videos();
				userpro_ajax_picupload();
				if(typeof(userpro_media_manager)=='function')
				{	
					userpro_media_manager();
				}
				jQuery('.userpro form').each(function(){
				userpro_collapse( jQuery(this) );
				});
				userpro_overlay_center('.userpro-overlay-inner');
				
			}
		});
	});
	
	/**
		registration fields blur validation
	**/
	jQuery(document).on('blur', '.userpro[data-template=publish] textarea, .userpro[data-template=publish] input, .userpro[data-template=register] input, .userpro[data-template=edit] input, .userpro[data-template=change] input', function(e){
	
		var element = jQuery(this);
		var parent = element.parents('.userpro-input');
		var required = element.data('required');
		var ajaxcheck = element.data('ajaxcheck');
		var original_elem = element.parents('.userpro').find('input[type=password]:first');
		var original = element.parents('.userpro').find('input[type=password]:first').val();
	
		if (required == 1) {
			
			if ( element.val().replace(/^\s+|\s+$/g, "").length == 0) {
				userpro_client_error( element, parent, element.parents('.userpro').data('required_text') );
			} else if (ajaxcheck) {
				userpro_side_validate( element, element.val(), ajaxcheck );
			} else {
				userpro_client_valid(element, parent);
			}
			
			if ( jQuery(this).attr('type') == 'password') { // only if field is password
				if ( element.val().replace(/^\s+|\s+$/g, "").length == 0) {
					userpro_client_error( element, parent, element.parents('.userpro').data('required_text') );
				} else if ( element.val().length < 8 ) {
					userpro_client_error( element, parent, element.parents('.userpro').data('password_too_short') );
				} else if ( userpro_password_strength_meter( element ) < 3 ) {
					userpro_client_error( element, parent, element.parents('.userpro').data('password_not_strong') );
				} else {
					userpro_client_valid(element, parent);
				}
			}

		} else if ( element.attr('type') == 'password' && original_elem && original && original_elem.parents('.userpro-input').find('.userpro-warning').length == 0 ) {
			if (element.val().replace(/^\s+|\s+$/g, "").length == 0) {
				userpro_client_error( element, parent, element.parents('.userpro').data('required_text') );
			} else if ( element.val().length < 8 ) {
				userpro_client_error( element, parent, element.parents('.userpro').data('password_too_short') );
			} else if ( userpro_password_strength_meter( element ) < 3 ) {
				userpro_client_error( element, parent, element.parents('.userpro').data('password_not_strong') );
			} else if ( original != element.val() ) {
				userpro_client_error( element, parent, jQuery(this).parents('.userpro').data('passwords_do_not_match') );
			} else {
				userpro_client_valid(element, parent);
			}
		} else if ( ( element.attr('type') == 'password' && original ) || ( element.attr('type') == 'password' && element.parents('.userpro').data('template') == 'change' ) ) {
			if (element.val().replace(/^\s+|\s+$/g, "").length == 0) {
				userpro_client_error( element, parent, element.parents('.userpro').data('required_text') );
			} else if ( element.val().length < 8 ) {
				userpro_client_error( element, parent, element.parents('.userpro').data('password_too_short') );
			} else if ( userpro_password_strength_meter( element ) < 3 ) {
				userpro_client_error( element, parent, element.parents('.userpro').data('password_not_strong') );
			} else if ( original != element.val() ) {
				userpro_client_error( element, parent, jQuery(this).parents('.userpro').data('passwords_do_not_match') );
			} else {
				userpro_client_valid(element, parent);
			}
		} else if (element.attr('type') == 'password' && original == '' && element.val() == '' ){
			userpro_clear_input(element);
		} else if ( ajaxcheck && element.val() ){
			userpro_side_validate( element, element.val(), ajaxcheck );
		} else if ( ajaxcheck && !element.val() ){
			userpro_clear_input( element );
		} else if ( element.val() && element.data('type') == 'antispam'){
			userpro_clear_input(element);
		} else if ( !ajaxcheck && element.attr('type') == 'text' ) {
			userpro_clear_input(element);
		}
		
	});
	
	/**
		select dropdowns live change
		validation to which fields are
		required
	**/
	jQuery(document).on('change', '.userpro[data-template=register] select', function(e){
		var element = jQuery(this);
		var parent = element.parents('.userpro-input');
		var required = element.data('required');
		if (required == 1) {
			if ( element.val() == 0) {
				userpro_client_error( element, parent, element.parents('.userpro').data('required_text') );
			} else {
				userpro_client_valid(element, parent);
			}
		}
	});
	
	/**
		activate password strength in
		registration mode
	**/
	jQuery(document).on('keyup keydown', '.userpro[data-template=register] input[type=password][data-required=1]', function(e){
		userpro_password_strength_meter( jQuery(this) );
	});
	
	/**
		activate password strength in
		edit mode
	**/
	jQuery(document).on('keyup keydown', '.userpro[data-template=edit] input[type=password]:first', function(e){
		userpro_password_strength_meter( jQuery(this) );
	});
	
	/**
		activate password strength in
		password change mode
	**/
	jQuery(document).on('keyup keydown', '.userpro[data-template=change] input[type=password]:first', function(e){
		userpro_password_strength_meter( jQuery(this) );
	});
	
	/**
		collapse / un-collapse work
		on field sections is done here
	**/
	jQuery(document).on('click', '.userpro-collapsible-1', function(e){

		if (jQuery(this).nextUntil('div.userpro-column').is(':hidden')){
			jQuery(this).nextUntil('div.userpro-column').show();
			jQuery(this).removeClass('userpro-collapsed-1').addClass('userpro-collapsed-0');
			jQuery(this).find('span').html('<i class="userpro-icon-angle-down"></i>');
		
			if (jQuery(this).parents('.userpro').data('keep_one_section_open') == 1){
			jQuery('.userpro-collapsible-1.userpro-collapsed-0').not(this).nextUntil('div.userpro-column').hide();
			jQuery('.userpro-collapsible-1.userpro-collapsed-0').not(this).find('span').html('<i class="userpro-icon-angle-right"></i>');
			jQuery('.userpro-collapsible-1.userpro-collapsed-0').not(this).removeClass('userpro-collapsed-0').addClass('userpro-collapsed-1');
			}
		
		} else {
			jQuery(this).nextUntil('div.userpro-column').hide();
			jQuery(this).find('span').html('<i class="userpro-icon-angle-right"></i>');
			jQuery(this).removeClass('userpro-collapsed-0').addClass('userpro-collapsed-1');
		}
		userpro_overlay_center('.userpro-overlay-inner');
	});
	
	/**
		instant popups with automatic template recognition
		popup-register as example
	**/
	jQuery(document).on('click', "*[class^='popup-'],*[class^='popup-'] a", function(e){
	
		var up_username = '';
		if (jQuery(this).data('up_username')) {
			up_username = jQuery(this).data('up_username');
		}
		if ( /popup/.test(jQuery(this).attr("class")) == false ){
			var template = jQuery(this).parents('li').attr('class').split('-')[1].match(/\w*/);
		} else {
			var template = jQuery(this).attr('class').split('-')[1].match(/\w*/);
		}
		var id = jQuery('.userpro').length;
		shortcode = '[userpro id=' + id + ' template=' + template + '';
		jQuery.each( jQuery(this).data(), function(key, value) {
			shortcode = shortcode + ' ' + key + '=' + '"' + value + '"';
		});
		shortcode = shortcode + ']';
		if (jQuery('body').find('.userpro-overlay').length==0) {
			jQuery('body').append('<div class="userpro-overlay"/><div class="userpro-overlay-inner"/>');
		}
		userpro_shortcode_template( 'insert', jQuery('.userpro-overlay-inner'), shortcode, up_username);
		
		if ( template == 'request_verify') {
			jQuery('.popup-request_verify').remove();
		}
	});
	
	/**
		auto change avatar based on gender
	**/
	jQuery(document).on('change', "input[name^='gender']", function(e){
		this_form = jQuery(this).parents('.userpro');
		if (this_form.find("*[data-key=profilepicture]").find('input:hidden').val()==''){
			this_form.find("*[data-key=profilepicture]").find('img').attr('src', jQuery(this).parents('.userpro').data('default_avatar_'+jQuery(this).val().toLowerCase() ));
		}
	});
	
	/**
		smart resizing, responsive, recalculation stuff
		is done here
	**/
	jQuery(window).smartresize(function(){
		userpro_overlay_center('.userpro-overlay-inner');
		userpro_responsive();
		userpro_fluid_videos();
	});
	userpro_responsive();
	setTimeout(function(){
	userpro_fluid_videos();
	}, 3000);
	userpro_chosen();
	userpro_ajax_picupload();
	if(typeof(userpro_media_manager)=='function')
	{	
		userpro_media_manager();
	}
	jQuery('.userpro form').each(function(){
	userpro_collapse( jQuery(this) );
	});
	
	/**
		cancel an upload
	**/
	jQuery(document).on('click', '.userpro form:not(.userpro-search-form) .userpro-input .userpro-button.red', function(e){
		jQuery(this).parents('.userpro-input').find('.userpro-pic-none').show();
		if ( jQuery(this).parents('.userpro-input').find('img.default').length) {
		jQuery(this).parents('.userpro-input').find('img.default').show();
		jQuery(this).parents('.userpro-input').find('img.modified').remove();
		} else {
		
			if (jQuery(this).parents('.userpro').find('div.userpro-pic-post_featured_image').length) {
			jQuery(this).parents('.userpro-input').find('img.modified').addClass('no_feature').attr('src', jQuery(this).parents('.userpro-input').data('placeholder') );
			} else {
			jQuery(this).parents('.userpro-input').find('img.modified').attr('src', '' );
			}
			
		}
		if ( jQuery(this).parents('.userpro-input').find('.userpro-file-input').length) {
			jQuery(this).parents('.userpro-input').find('.userpro-file-input').remove();
		}
		jQuery(this).parents('.userpro-input').find('input:hidden').val( '' );
		jQuery(this).fadeOut();
		
		// re-validate
		jQuery(this).parents('.userpro-input').find('input:hidden').each(function(){
			jQuery(this).trigger('blur');
		});

	});
	
	/**
		custom radio buttons
	**/
	jQuery(document).on('click', '.userpro input[type=radio]', function(e){
		var field = jQuery(this).parents('.userpro-input');
		field.find('span').removeClass('checked');
		jQuery(this).parents('label').find('span').addClass('checked');
	});
	
	/**
		custom checkbox buttons
	**/
	jQuery(document).on('change', '.userpro input[type=checkbox]', function(e){
		if (jQuery(this).is(':checked')) {
			jQuery(this).parents('label').find('span').addClass('checked');
		} else {
			jQuery(this).parents('label').find('span').removeClass('checked');
		}
	});
	
	/**
		if accidently clicked on error message
	**/
	jQuery(document).on('click', '.userpro-warning', function(e){
		jQuery(this).parents('.userpro-input').find('input').focus();
	});
	
});