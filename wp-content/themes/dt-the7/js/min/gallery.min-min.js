var Pixastic=function(){function t(t,e,a){t.addEventListener?t.addEventListener(e,a,!1):t.attachEvent&&t.attachEvent("on"+e,a)}function e(e){var a=!1,i=function(){a||(a=!0,e())};document.write('<script defer src="//:" id="__onload_ie_pixastic__"></script>');var r=document.getElementById("__onload_ie_pixastic__");r.onreadystatechange=function(){"complete"==r.readyState&&(r.parentNode.removeChild(r),i())},document.addEventListener&&document.addEventListener("DOMContentLoaded",i,!1),t(window,"load",i)}function a(){for(var t=r("pixastic",null,"img"),e=r("pixastic",null,"canvas"),a=t.concat(e),i=0;i<a.length;i++)!function(){for(var t=a[i],e=[],r=t.className.split(" "),s=0;s<r.length;s++){var n=r[s];if("pixastic-"==n.substring(0,9)){var c=n.substring(9);""!=c&&e.push(c)}}if(e.length)if("img"==t.tagName.toLowerCase()){var o=new Image;if(o.src=t.src,o.complete)for(var l=0;l<e.length;l++){var d=Pixastic.applyAction(t,t,e[l],null);d&&(t=d)}else o.onload=function(){for(var a=0;a<e.length;a++){var i=Pixastic.applyAction(t,t,e[a],null);i&&(t=i)}}}else setTimeout(function(){for(var a=0;a<e.length;a++){var i=Pixastic.applyAction(t,t,e[a],null);i&&(t=i)}},1)}()}function r(t,e,a){var r=new Array;null==e&&(e=document),null==a&&(a="*");var s=e.getElementsByTagName(a),n=s.length,c=new RegExp("(^|\\s)"+t+"(\\s|$)");for(i=0,j=0;n>i;i++)c.test(s[i].className)&&(r[j]=s[i],j++);return r}function s(t,e){if(Pixastic.debug)try{switch(e){case"warn":console.warn("Pixastic:",t);break;case"error":console.error("Pixastic:",t);break;default:console.log("Pixastic:",t)}}catch(a){}}"undefined"!=typeof pixastic_parseonload&&pixastic_parseonload&&e(a);var n=function(){var t=document.createElement("canvas"),e=!1;try{e=!("function"!=typeof t.getContext||!t.getContext("2d"))}catch(a){}return function(){return e}}(),c=function(){var t,e=document.createElement("canvas"),a=!1;try{"function"==typeof e.getContext&&(t=e.getContext("2d"))&&(a="function"==typeof t.getImageData)}catch(i){}return function(){return a}}(),o=function(){var t=!1,e=document.createElement("canvas");if(n()&&c()){e.width=e.height=1;var a=e.getContext("2d");a.fillStyle="rgb(255,0,0)",a.fillRect(0,0,1,1);var i=document.createElement("canvas");i.width=i.height=1;var r=i.getContext("2d");r.fillStyle="rgb(0,0,255)",r.fillRect(0,0,1,1),a.globalAlpha=.5,a.drawImage(i,0,0);var s=a.getImageData(0,0,1,1).data;t=255!=s[2]}return function(){return t}}();return{parseOnLoad:!1,debug:!1,applyAction:function(t,e,a,i){i=i||{};var r="canvas"==t.tagName.toLowerCase();if(r&&Pixastic.Client.isIE())return Pixastic.debug&&s("Tried to process a canvas element but browser is IE."),!1;var n,c,o=!1;Pixastic.Client.hasCanvas()&&(o=!!i.resultCanvas,n=i.resultCanvas||document.createElement("canvas"),c=n.getContext("2d"));var l=t.offsetWidth,d=t.offsetHeight;if(r&&(l=t.width,d=t.height),0==l||0==d){if(null!=t.parentNode)return void(Pixastic.debug&&s("Image has 0 width and/or height."));var g=t.style.position,h=t.style.left;t.style.position="absolute",t.style.left="-9999px",document.body.appendChild(t),l=t.offsetWidth,d=t.offsetHeight,document.body.removeChild(t),t.style.position=g,t.style.left=h}if(a.indexOf("(")>-1){var p=a;a=p.substr(0,p.indexOf("("));var f=p.match(/\((.*?)\)/);if(f[1]){f=f[1].split(";");for(var u=0;u<f.length;u++)if(thisArg=f[u].split("="),2==thisArg.length)if("rect"==thisArg[0]){var m=thisArg[1].split(",");i[thisArg[0]]={left:parseInt(m[0],10)||0,top:parseInt(m[1],10)||0,width:parseInt(m[2],10)||0,height:parseInt(m[3],10)||0}}else i[thisArg[0]]=thisArg[1]}}i.rect?(i.rect.left=Math.round(i.rect.left),i.rect.top=Math.round(i.rect.top),i.rect.width=Math.round(i.rect.width),i.rect.height=Math.round(i.rect.height)):i.rect={left:0,top:0,width:l,height:d};var v=!1;if(Pixastic.Actions[a]&&"function"==typeof Pixastic.Actions[a].process&&(v=!0),!v)return Pixastic.debug&&s('Invalid action "'+a+'". Maybe file not included?'),!1;if(!Pixastic.Actions[a].checkSupport())return Pixastic.debug&&s('Action "'+a+'" not supported by this browser.'),!1;Pixastic.Client.hasCanvas()?(n!==t&&(n.width=l,n.height=d),o||(n.style.width=l+"px",n.style.height=d+"px"),c.drawImage(e,0,0,l,d),t.__pixastic_org_image?(n.__pixastic_org_image=t.__pixastic_org_image,n.__pixastic_org_width=t.__pixastic_org_width,n.__pixastic_org_height=t.__pixastic_org_height):(n.__pixastic_org_image=t,n.__pixastic_org_width=l,n.__pixastic_org_height=d)):Pixastic.Client.isIE()&&"undefined"==typeof t.__pixastic_org_style&&(t.__pixastic_org_style=t.style.cssText);var _={image:t,canvas:n,width:l,height:d,useData:!0,options:i},x=Pixastic.Actions[a].process(_);return x?Pixastic.Client.hasCanvas()?(_.useData&&Pixastic.Client.hasCanvasImageData()&&(n.getContext("2d").putImageData(_.canvasData,i.rect.left,i.rect.top),n.getContext("2d").fillRect(0,0,0,0)),i.leaveDOM||(n.title=t.title,n.imgsrc=t.imgsrc,r||(n.alt=t.alt),r||(n.imgsrc=t.src),n.className=t.className,n.style.cssText=t.style.cssText,n.name=t.name,n.tabIndex=t.tabIndex,n.id=t.id,t.parentNode&&t.parentNode.replaceChild&&t.parentNode.replaceChild(n,t)),i.resultCanvas=n,n):t:!1},prepareData:function(t,e){var a=t.canvas.getContext("2d"),i=t.options.rect,r=a.getImageData(i.left,i.top,i.width,i.height),s=r.data;return e||(t.canvasData=r),s},process:function(t,e,a,i){if("img"==t.tagName.toLowerCase()){var r=new Image;if(r.src=t.src,r.complete){var s=Pixastic.applyAction(t,r,e,a);return i&&i(s),s}r.onload=function(){var s=Pixastic.applyAction(t,r,e,a);i&&i(s)}}if("canvas"==t.tagName.toLowerCase()){var s=Pixastic.applyAction(t,t,e,a);return i&&i(s),s}},revert:function(t){if(Pixastic.Client.hasCanvas()){if("canvas"==t.tagName.toLowerCase()&&t.__pixastic_org_image)return t.width=t.__pixastic_org_width,t.height=t.__pixastic_org_height,t.getContext("2d").drawImage(t.__pixastic_org_image,0,0),t.parentNode&&t.parentNode.replaceChild&&t.parentNode.replaceChild(t.__pixastic_org_image,t),t}else Pixastic.Client.isIE()&&"undefined"!=typeof t.__pixastic_org_style&&(t.style.cssText=t.__pixastic_org_style)},Client:{hasCanvas:n,hasCanvasImageData:c,hasGlobalAlpha:o,isIE:function(){return!!document.all&&!!window.attachEvent&&!window.opera}},Actions:{}}}();Pixastic.Actions.blurfast={process:function(t){var e=parseFloat(t.options.amount)||0,a=!(!t.options.clear||"false"==t.options.clear);if(e=Math.max(0,Math.min(5,e)),Pixastic.Client.hasCanvas()){var i=t.options.rect,r=t.canvas.getContext("2d");r.save(),r.beginPath(),r.rect(i.left,i.top,i.width,i.height),r.clip();var s=2,n=Math.round(t.width/s),c=Math.round(t.height/s),o=document.createElement("canvas");o.width=n,o.height=c;for(var a=!1,l=Math.round(20*e),d=o.getContext("2d"),g=0;l>g;g++){var h=Math.max(1,Math.round(n-g)),p=Math.max(1,Math.round(c-g));d.clearRect(0,0,n,c),d.drawImage(t.canvas,0,0,t.width,t.height,0,0,h,p),a&&r.clearRect(i.left,i.top,i.width,i.height),r.drawImage(o,0,0,h,p,0,0,t.width,t.height)}return r.restore(),t.useData=!1,!0}if(Pixastic.Client.isIE()){var f=10*e;return t.image.style.filter+=" progid:DXImageTransform.Microsoft.Blur(pixelradius="+f+")",t.image.style.marginLeft=(parseInt(t.image.style.marginLeft,10)||0)-Math.round(f)+"px",t.image.style.marginTop=(parseInt(t.image.style.marginTop,10)||0)-Math.round(f)+"px",!0}},checkSupport:function(){return Pixastic.Client.hasCanvas()||Pixastic.Client.isIE()}},jQuery(document).ready(function(t){dtGlobals.magnificPopupBaseConfig={type:"image",tLoading:"Loading image ...",mainClass:"mfp-img-mobile",image:{tError:'<a href="%url%">The image #%curr%</a> could not be loaded.',titleSrc:function(t){return this.st.dt.getItemTitle(t)}},iframe:{markup:'<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></div>'},callbacks:{markupParse:function(t,e,a){"iframe"==a.type&&t.find(".mfp-title").html(this.st.dt.getItemTitle(a)),this.ev.attr("data-pretty-share")||t.addClass("no-share-buttons")},beforeOpen:function(){var t=this;"undefined"==typeof this.st.dt&&(this.st.dt={}),this.st.dt.shareButtonsList=this.ev.attr("data-pretty-share")?this.ev.attr("data-pretty-share").split(","):new Array,this.st.dt.shareButtonsTemplates={twitter:'<a href="http://twitter.com/home?status={location_href}%20{share_title}" class="share-button twitter" target="_blank" title="twitter"><svg class="icon" viewBox="0 0 26 26"><path d="M19.537 8.12c-0.484 0.23-1.009 0.385-1.559 0.455c0.562-0.359 0.988-0.927 1.191-1.602 c-0.521 0.331-1.103 0.573-1.722 0.702c-0.491-0.562-1.196-0.915-1.976-0.915c-1.748 0-3.032 1.745-2.638 3.6 c-2.249-0.121-4.243-1.275-5.58-3.029c-0.707 1.303-0.367 3 0.8 3.869c-0.444-0.016-0.861-0.146-1.227-0.362 c-0.03 1.3 0.9 2.6 2.2 2.875c-0.38 0.111-0.799 0.138-1.224 0.054c0.347 1.1 1.3 2 2.5 2 c-1.139 0.955-2.572 1.384-4.009 1.199c1.198 0.8 2.6 1.3 4.2 1.306c5.029 0 7.866-4.546 7.697-8.621 C18.715 9.2 19.2 8.7 19.5 8.12z"/></svg></a>',facebook:'<a href="http://www.facebook.com/sharer.php?s=100&amp;p[url]={location_href}&amp;p[title]={share_title}&amp;p[images][0]={image_src}" class="share-button facebook" target="_blank" title="facebook"><svg class="icon" viewBox="0 0 26 26" ><path d="M10.716 10.066H9.451v2.109h1.263v6.199h2.436v-6.225h1.695l0.185-2.084h-1.88c0 0 0-0.778 0-1.187 c0-0.492 0.099-0.686 0.562-0.686c0.37 0 1.657-0.064 1.657-0.064V6.032c0 0-1.729 0-2.03 0c-1.809 0-2.626 0.813-2.626 2.4 C10.716 9.8 10.7 10.1 10.7 10.066z"/></svg></a>',google:'<a href="http:////plus.google.com/share?url={location_href}&amp;title={share_title}" class="share-button google" target="_blank" title="google+"><svg class="icon" viewBox="0 0 26 26" ><path d="M18.691 9.857h-1.793l0.017 1.797h-1.233l-0.019-1.778l-1.702-0.018l-0.021-1.154l1.74-0.007V6.845h1.233v1.833 l1.776 0.038L18.691 9.857L18.691 9.857z M13.195 15.173c0 1.167-1.064 2.591-3.746 2.591c-1.962 0-3.599-0.849-3.599-2.271 c0-1.1 0.696-2.52 3.945-2.52c-0.481-0.397-0.6-0.946-0.306-1.541c-1.902 0-2.876-1.12-2.876-2.54c0-1.39 1.034-2.653 3.141-2.653 c0.534 0 3.4 0 3.4 0L12.377 7.03H11.49c0.625 0.4 1 1.1 1 1.91c0 0.747-0.41 1.351-0.995 1.8 c-1.042 0.805-0.775 1.3 0.3 2.048C12.842 13.6 13.2 14.2 13.2 15.173z M10.899 8.9 c-0.145-0.888-0.861-1.615-1.698-1.636c-0.838-0.02-1.4 0.659-1.255 1.546c0.145 0.9 0.9 1.5 1.8 1.5 C10.561 10.4 11 9.8 10.9 8.91z M11.553 15.35c0-0.68-0.749-1.326-2.005-1.326c-1.131-0.012-2.093 0.592-2.093 1.3 c0 0.7 0.8 1.3 1.9 1.307C10.853 16.6 11.6 16.1 11.6 15.35z"/></svg></a>',pinterest:'<a href="//pinterest.com/pin/create/button/?url={location_href}&amp;description={share_title}&amp;media={image_src}" class="share-button pinterest" target="_blank" title="pin it"><svg class="icon" viewBox="0 0 26 26"><path d="M13.322 5.418c-3.738 0-5.622 2.631-5.622 4.824c0 1.3 0.5 2.5 1.6 3 c0.18 0.1 0.3 0 0.394-0.197c0.038-0.132 0.125-0.476 0.161-0.615c0.052-0.195 0.031-0.264-0.115-0.432 c-0.315-0.367-0.332-0.849-0.332-1.523c0-1.95 1.302-3.69 3.688-3.69c2.112 0 3.3 1.3 3.3 3 c0 2.228-1.006 4.105-2.494 4.105c-0.824 0-1.44-0.668-1.243-1.487c0.236-0.979 0.696-2.034 0.696-2.741 c0-0.631-0.346-1.158-1.062-1.158c-0.843 0-1.518 0.855-1.518 1.999c0 0.7 0.2 1.2 0.2 1.221s-1.063 3.676-1.213 4.3 c-0.301 1.3 0.2 2.7 0.2 2.844c0.015 0.1 0.1 0.1 0.2 0.046c0.077-0.103 1.08-1.316 1.42-2.527 c0.1-0.345 0.556-2.122 0.556-2.122c0.272 0.5 1.1 1 1.9 0.965c2.529 0 4.246-2.266 4.246-5.295 C18.305 7.6 16.3 5.4 13.3 5.418z" /></svg></a>'},this.st.dt.getShareButtons=function(e){for(var a=t.st.dt.shareButtonsList,i=-1,r=a.length,s="",n=0;n<a.length;n++)if("pinterest"==a[n]){i=n;break}if(0>=r)return"";for(var n=0;r>n;n++)if("iframe"!=e.type||i!=n){var c=e.title,o=e.src,l=e.location;"google"==a[n]&&(c=c.replace(" ","+")),s+=t.st.dt.shareButtonsTemplates[a[n]].replace("{location_href}",encodeURIComponent(l)).replace("{share_title}",c).replace("{image_src}",o)}return'<div class="entry-share"><div class="soc-ico">'+s+"<div></div>"},this.st.dt.getItemTitle=function(e){var a=e.el.attr("title")||"",i=e.el.attr("href"),r=e.el.attr("data-dt-img-description")||"",s=e.el.attr("data-dt-location")||location.href,n=t.st.dt.getShareButtons({title:a,src:i,type:e.type,location:s});return a+"<small>"+r+"</small>"+n}}}},t(".dt-gallery-mfp-popup").addClass("mfp-ready").on("click",function(){var e=t(this),a=e.parents("article.post");if(a.length>0){var i=a.find(".dt-gallery-container a.dt-mfp-item");i.length>0&&i.first().trigger("click")}return!1}),t(".dt-trigger-first-mfp").addClass("mfp-ready").on("click",function(){var e=t(this),a=e.parents("article.post");if(a.length>0){var i=a.find("a.dt-mfp-item");i.length>0&&i.first().trigger("click")}return!1}),t(".dt-single-image").addClass("mfp-ready").magnificPopup({type:"image"}),t(".dt-single-video").addClass("mfp-ready").magnificPopup({type:"iframe"}),t(".dt-single-mfp-popup").addClass("mfp-ready").magnificPopup(dtGlobals.magnificPopupBaseConfig),t(".dt-gallery-container").each(function(){t(this).addClass("mfp-ready").magnificPopup(t.extend({},dtGlobals.magnificPopupBaseConfig,{delegate:"a.dt-mfp-item",tLoading:"Loading image #%curr%...",gallery:{enabled:!0,navigateByImgClick:!0,preload:[0,1]}}))}),t.fn.triggerAlbumsClick=function(){return this.each(function(){var e=t(this);if(!e.hasClass("this-ready")){var a=e.find("a").first();e.on("click",function(){return a.trigger("click"),!1}),e.addClass("this-ready")}})},t(".albums .rollover-content").triggerAlbumsClick(),jQuery(document).ready(function(t){var e=t("#content").find(".wf-usr-cell"),a=[],i=0;e.each(function(){var e=t(this);a[i]=e,e.next().hasClass("wf-usr-cell")?i++:(e.parent().hasClass("wf-container")||t(a).map(function(){return this.toArray()}).wrapAll('<div class="wf-container">'),a=[],i=0)}),t(window).load(function(){t.fn.blurImage=function(){return this.each(function(){var e=t(this);if(!e.hasClass("blur-ready")){var a=e.find("> img");e.addClass("blur-this"),a.clone().addClass("blur-effect").css("opacity","").prependTo(this);var i=t(".blur-effect",this);i.each(function(t){1==a[t].complete?Pixastic.process(i[t],"blurfast",{amount:.3}):i.load(function(){Pixastic.process(i[t],"blurfast",{amount:.3})})}),e.addClass("blur-ready")}})};var e=t("body img").length,a=0;t("body").find("img").each(function(){var i=t(this).attr("src");t("<img/>").attr("src",i).css("display","none").load(function(){a++,a>=e&&t(".image-blur .fs-entry-img:not(.shortcode-instagram .fs-entry-img), .image-blur .shortcode-instagram a, .image-blur .rollover-project a:not(.hover-style-two .rollover-project a), .image-blur .rollover, .image-blur .rollover > div, .image-blur .post-rollover, .image-blur .rollover-video").blurImage()})})})})});