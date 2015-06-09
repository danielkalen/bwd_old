
/*
 * Pixastic - JavaScript Image Processing Library
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
 * MIT License [http://www.pixastic.com/lib/license.txt]
 */

var Pixastic=(function(){function addEvent(el,event,handler){if(el.addEventListener)
el.addEventListener(event,handler,false);else if(el.attachEvent)
el.attachEvent("on"+event,handler);}
function onready(handler){var handlerDone=false;var execHandler=function(){if(!handlerDone){handlerDone=true;handler();}}
document.write("<"+"script defer src=\"//:\" id=\"__onload_ie_pixastic__\"></"+"script>");var script=document.getElementById("__onload_ie_pixastic__");script.onreadystatechange=function(){if(script.readyState=="complete"){script.parentNode.removeChild(script);execHandler();}}
if(document.addEventListener)
document.addEventListener("DOMContentLoaded",execHandler,false);addEvent(window,"load",execHandler);}
function init(){var imgEls=getElementsByClass("pixastic",null,"img");var canvasEls=getElementsByClass("pixastic",null,"canvas");var elements=imgEls.concat(canvasEls);for(var i=0;i<elements.length;i++){(function(){var el=elements[i];var actions=[];var classes=el.className.split(" ");for(var c=0;c<classes.length;c++){var cls=classes[c];if(cls.substring(0,9)=="pixastic-"){var actionName=cls.substring(9);if(actionName!="")
actions.push(actionName);}}
if(actions.length){if(el.tagName.toLowerCase()=="img"){var dataImg=new Image();dataImg.src=el.src;if(dataImg.complete){for(var a=0;a<actions.length;a++){var res=Pixastic.applyAction(el,el,actions[a],null);if(res)
el=res;}}else{dataImg.onload=function(){for(var a=0;a<actions.length;a++){var res=Pixastic.applyAction(el,el,actions[a],null)
if(res)
el=res;}}}}else{setTimeout(function(){for(var a=0;a<actions.length;a++){var res=Pixastic.applyAction(el,el,actions[a],null);if(res)
el=res;}},1);}}})();}}
if(typeof pixastic_parseonload!="undefined"&&pixastic_parseonload)
onready(init);function getElementsByClass(searchClass,node,tag){var classElements=new Array();if(node==null)
node=document;if(tag==null)
tag='*';var els=node.getElementsByTagName(tag);var elsLen=els.length;var pattern=new RegExp("(^|\\s)"+searchClass+"(\\s|$)");for(i=0,j=0;i<elsLen;i++){if(pattern.test(els[i].className)){classElements[j]=els[i];j++;}}
return classElements;}
var debugElement;function writeDebug(text,level){if(!Pixastic.debug)return;try{switch(level){case"warn":console.warn("Pixastic:",text);break;case"error":console.error("Pixastic:",text);break;default:console.log("Pixastic:",text);}}catch(e){}
if(!debugElement){}}
var hasCanvas=(function(){var c=document.createElement("canvas");var val=false;try{val=!!((typeof c.getContext=="function")&&c.getContext("2d"));}catch(e){}
return function(){return val;}})();var hasCanvasImageData=(function(){var c=document.createElement("canvas");var val=false;var ctx;try{if(typeof c.getContext=="function"&&(ctx=c.getContext("2d"))){val=(typeof ctx.getImageData=="function");}}catch(e){}
return function(){return val;}})();var hasGlobalAlpha=(function(){var hasAlpha=false;var red=document.createElement("canvas");if(hasCanvas()&&hasCanvasImageData()){red.width=red.height=1;var redctx=red.getContext("2d");redctx.fillStyle="rgb(255,0,0)";redctx.fillRect(0,0,1,1);var blue=document.createElement("canvas");blue.width=blue.height=1;var bluectx=blue.getContext("2d");bluectx.fillStyle="rgb(0,0,255)";bluectx.fillRect(0,0,1,1);redctx.globalAlpha=0.5;redctx.drawImage(blue,0,0);var reddata=redctx.getImageData(0,0,1,1).data;hasAlpha=(reddata[2]!=255);}
return function(){return hasAlpha;}})();return{parseOnLoad:false,debug:false,applyAction:function(img,dataImg,actionName,options){options=options||{};var imageIsCanvas=(img.tagName.toLowerCase()=="canvas");if(imageIsCanvas&&Pixastic.Client.isIE()){if(Pixastic.debug)writeDebug("Tried to process a canvas element but browser is IE.");return false;}
var canvas,ctx;var hasOutputCanvas=false;if(Pixastic.Client.hasCanvas()){hasOutputCanvas=!!options.resultCanvas;canvas=options.resultCanvas||document.createElement("canvas");ctx=canvas.getContext("2d");}
var w=img.offsetWidth;var h=img.offsetHeight;if(imageIsCanvas){w=img.width;h=img.height;}
if(w==0||h==0){if(img.parentNode==null){var oldpos=img.style.position;var oldleft=img.style.left;img.style.position="absolute";img.style.left="-9999px";document.body.appendChild(img);w=img.offsetWidth;h=img.offsetHeight;document.body.removeChild(img);img.style.position=oldpos;img.style.left=oldleft;}else{if(Pixastic.debug)writeDebug("Image has 0 width and/or height.");return;}}
if(actionName.indexOf("(")>-1){var tmp=actionName;actionName=tmp.substr(0,tmp.indexOf("("));var arg=tmp.match(/\((.*?)\)/);if(arg[1]){arg=arg[1].split(";");for(var a=0;a<arg.length;a++){thisArg=arg[a].split("=");if(thisArg.length==2){if(thisArg[0]=="rect"){var rectVal=thisArg[1].split(",");options[thisArg[0]]={left:parseInt(rectVal[0],10)||0,top:parseInt(rectVal[1],10)||0,width:parseInt(rectVal[2],10)||0,height:parseInt(rectVal[3],10)||0}}else{options[thisArg[0]]=thisArg[1];}}}}}
if(!options.rect){options.rect={left:0,top:0,width:w,height:h};}else{options.rect.left=Math.round(options.rect.left);options.rect.top=Math.round(options.rect.top);options.rect.width=Math.round(options.rect.width);options.rect.height=Math.round(options.rect.height);}
var validAction=false;if(Pixastic.Actions[actionName]&&typeof Pixastic.Actions[actionName].process=="function"){validAction=true;}
if(!validAction){if(Pixastic.debug)writeDebug("Invalid action \""+actionName+"\". Maybe file not included?");return false;}
if(!Pixastic.Actions[actionName].checkSupport()){if(Pixastic.debug)writeDebug("Action \""+actionName+"\" not supported by this browser.");return false;}
if(Pixastic.Client.hasCanvas()){if(canvas!==img){canvas.width=w;canvas.height=h;}
if(!hasOutputCanvas){canvas.style.width=w+"px";canvas.style.height=h+"px";}
ctx.drawImage(dataImg,0,0,w,h);if(!img.__pixastic_org_image){canvas.__pixastic_org_image=img;canvas.__pixastic_org_width=w;canvas.__pixastic_org_height=h;}else{canvas.__pixastic_org_image=img.__pixastic_org_image;canvas.__pixastic_org_width=img.__pixastic_org_width;canvas.__pixastic_org_height=img.__pixastic_org_height;}}else if(Pixastic.Client.isIE()&&typeof img.__pixastic_org_style=="undefined"){img.__pixastic_org_style=img.style.cssText;}
var params={image:img,canvas:canvas,width:w,height:h,useData:true,options:options}
var res=Pixastic.Actions[actionName].process(params);if(!res){return false;}
if(Pixastic.Client.hasCanvas()){if(params.useData){if(Pixastic.Client.hasCanvasImageData()){canvas.getContext("2d").putImageData(params.canvasData,options.rect.left,options.rect.top);canvas.getContext("2d").fillRect(0,0,0,0);}}
if(!options.leaveDOM){canvas.title=img.title;canvas.imgsrc=img.imgsrc;if(!imageIsCanvas)canvas.alt=img.alt;if(!imageIsCanvas)canvas.imgsrc=img.src;canvas.className=img.className;canvas.style.cssText=img.style.cssText;canvas.name=img.name;canvas.tabIndex=img.tabIndex;canvas.id=img.id;if(img.parentNode&&img.parentNode.replaceChild){img.parentNode.replaceChild(canvas,img);}}
options.resultCanvas=canvas;return canvas;}
return img;},prepareData:function(params,getCopy){var ctx=params.canvas.getContext("2d");var rect=params.options.rect;var dataDesc=ctx.getImageData(rect.left,rect.top,rect.width,rect.height);var data=dataDesc.data;if(!getCopy)params.canvasData=dataDesc;return data;},process:function(img,actionName,options,callback){if(img.tagName.toLowerCase()=="img"){var dataImg=new Image();dataImg.src=img.src;if(dataImg.complete){var res=Pixastic.applyAction(img,dataImg,actionName,options);if(callback)callback(res);return res;}else{dataImg.onload=function(){var res=Pixastic.applyAction(img,dataImg,actionName,options)
if(callback)callback(res);}}}
if(img.tagName.toLowerCase()=="canvas"){var res=Pixastic.applyAction(img,img,actionName,options);if(callback)callback(res);return res;}},revert:function(img){if(Pixastic.Client.hasCanvas()){if(img.tagName.toLowerCase()=="canvas"&&img.__pixastic_org_image){img.width=img.__pixastic_org_width;img.height=img.__pixastic_org_height;img.getContext("2d").drawImage(img.__pixastic_org_image,0,0);if(img.parentNode&&img.parentNode.replaceChild){img.parentNode.replaceChild(img.__pixastic_org_image,img);}
return img;}}else if(Pixastic.Client.isIE()){if(typeof img.__pixastic_org_style!="undefined")
img.style.cssText=img.__pixastic_org_style;}},Client:{hasCanvas:hasCanvas,hasCanvasImageData:hasCanvasImageData,hasGlobalAlpha:hasGlobalAlpha,isIE:function(){return!!document.all&&!!window.attachEvent&&!window.opera;}},Actions:{}}})();Pixastic.Actions.blurfast={process:function(params){var amount=parseFloat(params.options.amount)||0;var clear=!!(params.options.clear&&params.options.clear!="false");amount=Math.max(0,Math.min(5,amount));if(Pixastic.Client.hasCanvas()){var rect=params.options.rect;var ctx=params.canvas.getContext("2d");ctx.save();ctx.beginPath();ctx.rect(rect.left,rect.top,rect.width,rect.height);ctx.clip();var scale=2;var smallWidth=Math.round(params.width/scale);var smallHeight=Math.round(params.height/scale);var copy=document.createElement("canvas");copy.width=smallWidth;copy.height=smallHeight;var clear=false;var steps=Math.round(amount*20);var copyCtx=copy.getContext("2d");for(var i=0;i<steps;i++){var scaledWidth=Math.max(1,Math.round(smallWidth-i));var scaledHeight=Math.max(1,Math.round(smallHeight-i));copyCtx.clearRect(0,0,smallWidth,smallHeight);copyCtx.drawImage(params.canvas,0,0,params.width,params.height,0,0,scaledWidth,scaledHeight);if(clear)
ctx.clearRect(rect.left,rect.top,rect.width,rect.height);ctx.drawImage(copy,0,0,scaledWidth,scaledHeight,0,0,params.width,params.height);}
ctx.restore();params.useData=false;return true;}else if(Pixastic.Client.isIE()){var radius=10*amount;params.image.style.filter+=" progid:DXImageTransform.Microsoft.Blur(pixelradius="+radius+")";if(params.options.fixMargin||1){params.image.style.marginLeft=(parseInt(params.image.style.marginLeft,10)||0)-Math.round(radius)+"px";params.image.style.marginTop=(parseInt(params.image.style.marginTop,10)||0)-Math.round(radius)+"px";}
return true;}},checkSupport:function(){return(Pixastic.Client.hasCanvas()||Pixastic.Client.isIE());}}


jQuery(document).ready(function($){
/* !Magnific popup gallery */
	dtGlobals.magnificPopupBaseConfig = {
		type: 'image',
		tLoading: 'Loading image ...',
		mainClass: 'mfp-img-mobile',
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return this.st.dt.getItemTitle(item);
			}
		},
		iframe: {
			markup: '<div class="mfp-iframe-scaler">'+
					'<div class="mfp-close"></div>'+
					'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
					'<div class="mfp-bottom-bar">'+
					'<div class="mfp-title"></div>'+
					'<div class="mfp-counter"></div>'+
					'</div>'+
					'</div>'
		},
		callbacks: {
			markupParse: function(template, values, item) {
				if ( 'iframe' == item.type ) {
					template.find('.mfp-title').html( this.st.dt.getItemTitle(item) );
				}

				if ( !this.ev.attr('data-pretty-share') ) {
					template.addClass("no-share-buttons");
				}
			},
			beforeOpen: function() {

				var magnificPopup = this;
				// create settings container
				if ( typeof this.st.dt == 'undefined' ) {
					this.st.dt = {};
				}

				// save share buttons array
				this.st.dt.shareButtonsList = this.ev.attr('data-pretty-share') ? this.ev.attr('data-pretty-share').split(',') : new Array();

				// share buttons template
				this.st.dt.shareButtonsTemplates = {
					twitter : '<a href="http://twitter.com/home?status={location_href}%20{share_title}" class="share-button twitter" target="_blank" title="twitter"><svg class="icon" viewBox="0 0 26 26"><path d="M19.537 8.12c-0.484 0.23-1.009 0.385-1.559 0.455c0.562-0.359 0.988-0.927 1.191-1.602 c-0.521 0.331-1.103 0.573-1.722 0.702c-0.491-0.562-1.196-0.915-1.976-0.915c-1.748 0-3.032 1.745-2.638 3.6 c-2.249-0.121-4.243-1.275-5.58-3.029c-0.707 1.303-0.367 3 0.8 3.869c-0.444-0.016-0.861-0.146-1.227-0.362 c-0.03 1.3 0.9 2.6 2.2 2.875c-0.38 0.111-0.799 0.138-1.224 0.054c0.347 1.1 1.3 2 2.5 2 c-1.139 0.955-2.572 1.384-4.009 1.199c1.198 0.8 2.6 1.3 4.2 1.306c5.029 0 7.866-4.546 7.697-8.621 C18.715 9.2 19.2 8.7 19.5 8.12z"/></svg></a>',
					facebook : '<a href="http://www.facebook.com/sharer.php?s=100&amp;p[url]={location_href}&amp;p[title]={share_title}&amp;p[images][0]={image_src}" class="share-button facebook" target="_blank" title="facebook"><svg class="icon" viewBox="0 0 26 26" ><path d="M10.716 10.066H9.451v2.109h1.263v6.199h2.436v-6.225h1.695l0.185-2.084h-1.88c0 0 0-0.778 0-1.187 c0-0.492 0.099-0.686 0.562-0.686c0.37 0 1.657-0.064 1.657-0.064V6.032c0 0-1.729 0-2.03 0c-1.809 0-2.626 0.813-2.626 2.4 C10.716 9.8 10.7 10.1 10.7 10.066z"/></svg></a>',
					google : '<a href="http:////plus.google.com/share?url={location_href}&amp;title={share_title}" class="share-button google" target="_blank" title="google+"><svg class="icon" viewBox="0 0 26 26" ><path d="M18.691 9.857h-1.793l0.017 1.797h-1.233l-0.019-1.778l-1.702-0.018l-0.021-1.154l1.74-0.007V6.845h1.233v1.833 l1.776 0.038L18.691 9.857L18.691 9.857z M13.195 15.173c0 1.167-1.064 2.591-3.746 2.591c-1.962 0-3.599-0.849-3.599-2.271 c0-1.1 0.696-2.52 3.945-2.52c-0.481-0.397-0.6-0.946-0.306-1.541c-1.902 0-2.876-1.12-2.876-2.54c0-1.39 1.034-2.653 3.141-2.653 c0.534 0 3.4 0 3.4 0L12.377 7.03H11.49c0.625 0.4 1 1.1 1 1.91c0 0.747-0.41 1.351-0.995 1.8 c-1.042 0.805-0.775 1.3 0.3 2.048C12.842 13.6 13.2 14.2 13.2 15.173z M10.899 8.9 c-0.145-0.888-0.861-1.615-1.698-1.636c-0.838-0.02-1.4 0.659-1.255 1.546c0.145 0.9 0.9 1.5 1.8 1.5 C10.561 10.4 11 9.8 10.9 8.91z M11.553 15.35c0-0.68-0.749-1.326-2.005-1.326c-1.131-0.012-2.093 0.592-2.093 1.3 c0 0.7 0.8 1.3 1.9 1.307C10.853 16.6 11.6 16.1 11.6 15.35z"/></svg></a>',
					pinterest : '<a href="//pinterest.com/pin/create/button/?url={location_href}&amp;description={share_title}&amp;media={image_src}" class="share-button pinterest" target="_blank" title="pin it"><svg class="icon" viewBox="0 0 26 26"><path d="M13.322 5.418c-3.738 0-5.622 2.631-5.622 4.824c0 1.3 0.5 2.5 1.6 3 c0.18 0.1 0.3 0 0.394-0.197c0.038-0.132 0.125-0.476 0.161-0.615c0.052-0.195 0.031-0.264-0.115-0.432 c-0.315-0.367-0.332-0.849-0.332-1.523c0-1.95 1.302-3.69 3.688-3.69c2.112 0 3.3 1.3 3.3 3 c0 2.228-1.006 4.105-2.494 4.105c-0.824 0-1.44-0.668-1.243-1.487c0.236-0.979 0.696-2.034 0.696-2.741 c0-0.631-0.346-1.158-1.062-1.158c-0.843 0-1.518 0.855-1.518 1.999c0 0.7 0.2 1.2 0.2 1.221s-1.063 3.676-1.213 4.3 c-0.301 1.3 0.2 2.7 0.2 2.844c0.015 0.1 0.1 0.1 0.2 0.046c0.077-0.103 1.08-1.316 1.42-2.527 c0.1-0.345 0.556-2.122 0.556-2.122c0.272 0.5 1.1 1 1.9 0.965c2.529 0 4.246-2.266 4.246-5.295 C18.305 7.6 16.3 5.4 13.3 5.418z" /></svg></a>'
				};

				// share buttons
				this.st.dt.getShareButtons = function ( itemData ) {

					var shareButtons = magnificPopup.st.dt.shareButtonsList,
						pinterestIndex = -1,
						shareButtonsLemgth = shareButtons.length,
						html = '';

					for( var i = 0; i < shareButtons.length; i++ ) {

						if ( 'pinterest' == shareButtons[i] ) {
							pinterestIndex = i;
							break;
						}
					}

					if ( shareButtonsLemgth <= 0 ) {
						return '';
					}

					for ( var i = 0; i < shareButtonsLemgth; i++ ) {

						// exclude pinterest button for iframes
						if ( 'iframe' == itemData['type'] && pinterestIndex == i ) {
							continue;
						}

						var	itemTitle = itemData['title'],
							itemSrc = itemData['src'],
							itemLocation = itemData['location'];

						if ( 'google' == shareButtons[i] ) {
							itemTitle = itemTitle.replace(' ', '+');
						}

						html += magnificPopup.st.dt.shareButtonsTemplates[ shareButtons[i] ].replace('{location_href}', encodeURIComponent(itemLocation)).replace('{share_title}', itemTitle).replace('{image_src}', itemSrc);
					}

					return '<div class="entry-share"><div class="soc-ico">' + html + '<div></div>';
				}

				// item title
				this.st.dt.getItemTitle = function(item) {
					var imgTitle = item.el.attr('title') || '',
						imgSrc = item.el.attr('href'),
						imgDesc = item.el.attr('data-dt-img-description') || '',
						imgLocation = item.el.attr('data-dt-location') || location.href,
						shareButtons = magnificPopup.st.dt.getShareButtons( { 'title': imgTitle, 'src': imgSrc, 'type': item.type, 'location': imgLocation } );

					return imgTitle + '<small>' + imgDesc + '</small>' + shareButtons;
				}
			}
		}
	};

	// trigger click on first anchor in the gallery container
	// work only for posts list
	$('.dt-gallery-mfp-popup').addClass('mfp-ready').on('click', function(){
		var $this = $(this),
			$container = $this.parents('article.post');

		if ( $container.length > 0 ) {
			var $target = $container.find('.dt-gallery-container a.dt-mfp-item');

			if ( $target.length > 0 ) {
				$target.first().trigger('click');
			}
		}

		return false;
	});

	// trigger click on first a.dt-mfp-item in the container
	$('.dt-trigger-first-mfp').addClass('mfp-ready').on('click', function(){
		var $this = $(this),
			$container = $this.parents('article.post');

		if ( $container.length > 0 ) {
			var $target = $container.find('a.dt-mfp-item');

			if ( $target.length > 0 ) {
				$target.first().trigger('click');
			}
		}

		return false;
	});

	// single opup
	$('.dt-single-image').addClass('mfp-ready').magnificPopup({
		type: 'image'
	});

	$('.dt-single-video').addClass('mfp-ready').magnificPopup({
		type: 'iframe'
	});


	$('.dt-single-mfp-popup').addClass('mfp-ready').magnificPopup(dtGlobals.magnificPopupBaseConfig);

	$(".dt-gallery-container").each(function(){
		$(this).addClass('mfp-ready').magnificPopup( $.extend( {}, dtGlobals.magnificPopupBaseConfig, {
			delegate: 'a.dt-mfp-item',
			tLoading: 'Loading image #%curr%...',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			}
		} ) );
	});

/* Magnific popup gallery: end */



	/*!Trigger click on album item */
	$.fn.triggerAlbumsClick = function() {
		return this.each(function() {
			var $this = $(this);
			if ($this.hasClass("this-ready")) {
				return;
			}

			var $thisSingleLink = $this.find("a").first();
			$this.on("click", function(){
				$thisSingleLink.trigger("click")
				return false;
			});
			$this.addClass("this-ready");
		});
	};
	$(".albums .rollover-content").triggerAlbumsClick();
	/*Trigger click on album item:end */
	
jQuery(document).ready(function($) {
	var $suspects = $("#content").find(".wf-usr-cell"),
		jail = [],
		i = 0;

	$suspects.each(function() {
		var $this = $(this);

		jail[i] = $this;

		if (!$this.next().hasClass("wf-usr-cell")) {
			if (!$this.parent().hasClass("wf-container")) {
				$(jail).map(function () {return this.toArray(); }).wrapAll('<div class="wf-container">');
			}
			jail = [];
			i = 0;
		} else {
			i++;
		};
	});

		$(window).load(function() {
	/* !Blur */
		// if(!dtGlobals.isMobile){
			$.fn.blurImage = function() {
				// alert("blur")
				return this.each(function() {
					var $_this = $(this);
					if ($_this.hasClass("blur-ready")) {
						return;
					}

					var img = $_this.find("> img");

					$_this.addClass('blur-this');
					img.clone().addClass("blur-effect").css('opacity', '').prependTo(this);
							
					var blur_this = $(".blur-effect", this);
						blur_this.each(function(index, element){
							if (img[index].complete == true) {
								Pixastic.process(blur_this[index], "blurfast", {amount:0.3});					
							}else {
								blur_this.load(function () {
									Pixastic.process(blur_this[index], "blurfast", {amount:0.3});
								});
							}
						});

					$_this.addClass("blur-ready");
					//$img.trigger("heightReady");
				});
			};

			var total_images = $("body img").length;
			var images_loaded = 0;
			$("body").find('img').each(function() {
				var fakeSrc = $(this).attr('src');
				$("<img/>").attr("src", fakeSrc).css('display', 'none').load(function() {
					images_loaded++;
					if (images_loaded >= total_images) {
						// now all images are loaded.
						$(".image-blur .fs-entry-img:not(.shortcode-instagram .fs-entry-img), .image-blur .shortcode-instagram a, .image-blur .rollover-project a:not(.hover-style-two .rollover-project a), .image-blur .rollover, .image-blur .rollover > div, .image-blur .post-rollover, .image-blur .rollover-video").blurImage();
					}
				});

			});
			
		// };
		/* Blur: end */
	});
	});

});