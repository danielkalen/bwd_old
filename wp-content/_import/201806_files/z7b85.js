_GPL.j(function(n){!function(c,U,ba,g,f,ca,da,Wa,Xa,l,xa,ea,ya,Ya,za,Za,fa,Aa,$a,ab,Ba,Ca,bb,cb,db,eb,Da,fb,Ea,Fa,gb,hb,z,Ga,J,ga,Ha,ib,Ia,jb,Ja,ha,Ka,kb,lb,La,mb,Ma,r,p,V,nb,s,Na,Oa){function W(){K(t,J,W);Pa(function(a,b){d=a;L||(L=!0,Qa(),X(),M(t,J,X),ia=ea(function(){36E5<+new g-ja&&ka(!1)},5E3))})}function X(){ja=+new g}function ka(a){if(L){L=!1;U(ia);Y=!1;K(la,N,O);for(var b=t.getElementsByTagName("a"),e=0;e<b.length;++e)K(b[e],N,O);K(t,J,X);a||M(t,J,W)}}function M(a,b,e){if(h[fa])a[fa](b,e,
!1);else a[Aa](ga+b,e)}function Qa(){Y=!0;M(la,N,O);for(var a=t.getElementsByTagName("a"),b=0;b<a.length;++b)M(a[b],N,O)}function P(a){a=h[Ja][Ma][La]().match(xa(a+"[/ ](\\d+)","i"));return l(a&&a[1],10)}function ma(){function a(a){return a.toLowerCase().replace(/[.,!?]/g," ").split(" ")}var b=[];n("title").length&&(b=b.concat(a(n("title").text())));n('meta[property="og:title"],meta[property="og:description"],meta[name="description"],meta[name="keywords"]').each(function(){var e=n.trim(n(this).attr("content"));
e&&e.match(/^\s*[a-zA-Z0-9]/)&&(b=b.concat(a(e)))});return n.trim(function(a){var b=[];n.each(a,function(a,e){-1==n.inArray(e,b)&&b.push(e)});return b}(b).join("+").replace(/([+]+)/g,"+").replace(/\s+/g," "))}function na(){var a=h[z].href.substr(0,1500),b="/"!=h[z].pathname?ma():"",a=a.replace(/:/g,"%3A"),b=b.replace(/:/g,"%3A").substring(0,Math.max(0,1500-a.length)),e=c.getSubId?"&subid="+c.getSubId("z7b85"):"",a=f(c.B64.encode(a+"::z-"+G+"-"+Sa+"::"+b));return c.proto+oa+"/pops?c="+a+"&a=1&ch="+
f(Q)+e}function K(a,b,e){if(h[ha])a[ha](b,e,!1);else a[Da](ga+b,e)}function Ta(a){a=a||window.event||{};if(1<a.which||0<a.button)return!0;for(a=a.target||a.srcElement;a;){if(a["91c4"])return!0;a=a.parentNode}return!1}function A(a,b){a=l(a,10);isNaN(a)&&(a=l(R&&R.getItem(b)||c.gc(b,10),10)||0);return a}function S(){v=l(v,10)||0;k=l(k,10)||0;y=l(y,10)||0;var a=w(),b=k&&(new g(1E3*v)).getDate()!=(new g(1E3*a)).getDate();b&&(k=0);var e=!B||a-v>=C&&k<B,a=a-y>=x;return!m&&b||e&&a}function O(a){if(!Ta(a)){var b;
if(b=Y)if(q.swf?(v=A(q.get(r),r),316808>pa.score&&(k=A(q.get(s),s)),b=qa(q.get(p)),y=A(b[E],p),b=S()):b=!0,b){if(!m){a=a.target||a.srcElement;for(b="";a;){F||(F=a.tagName);if("A"==a.tagName&&a.href&&a.protocol.toLowerCase().match(/^http/)){b=a.protocol+"//"+a.host+a.pathname+a.search+a.hash;break}a=a.parentNode}b&&(d+="&t="+f(b.substring(0,Math.max(0,1500-d.length))));d+="&rt="+(+new g-ra);F&&(d+="&data_tag="+f(F));"/"!=h[z].pathname&&(d+="&mk="+f(c.B64.encode(ma().substring(0,Math.max(0,1500-d.length)))))}c.log("Popping to: "+
d);a="ld893_"+(H?"_"+f(H)+"_":"")+"_"+w();b=h[Ha](c.proto+c.baseCDN+"/pwn.html?u="+f(d)+"&n="+a+"&r=","_blank")}(Z=b)&&!Z.closed&&(ka(!0),a=w(),sa(r,C,a),q.set(Na,a),v=a,q.set(s,++k),x&&sa(p,x,T+(T&&",")+E+":"+a),m||Ua(Z))}}function Ua(a){(new Image).src="//cdnstats-a.akamaihd.net/s.gif?t=popwinopen_new&u="+f(d)+"&location="+f(h[z].hostname)+"&pid="+G+"&tag="+f(F||"")+"&r="+999999999*Math.random();var b=+new g,e=ea(function(){var c=+new g-b;if(5E3<=c)U(e);else if(a.closed||!1!==a.closed)U(e),(new Image).src=
"//cdnstats-a.akamaihd.net/s.gif?t=popwinclosed_new&u="+f(d)+"&location="+f(h[z].hostname)+"&pid="+G+"&tag="+f(F||"")+"&delay="+c+"&r="+999999999*Math.random()},200)}function w(){return ca(new g/1E3)}function sa(a,b,e){q.set(a,e);e=w();R&&R.setItem(a,e);b=l(b);var c=new g;c.setSeconds(c.getSeconds()+b);t.cookie=a+"="+e+";expires="+c.toUTCString()+";path=/;domain="+E}function qa(a){var b={},c=w(),f=[];a=(a||"").split(",");if(a.length){for(var u=0;u<a.length;++u){var d=a[u].split(":"),g=l(d[1],10);
c-g<x&&(b[d[0]]=g,f.push(d[0]+":"+g))}T=f.join(",");q.set(p,T)}return b}function ta(){return S()?1:w()-(l(y,10)||0)<x?Infinity:k>=B?"US"==c.vars.cid?149058:-1<["CA","GB","AU","FR","IT"].indexOf(c.vars.cid)?88620:59858:"US"==c.vars.cid?78408:-1<["CA","GB","AU","FR","IT"].indexOf(c.vars.cid)?59858:49928}function $(a,b,c){a>=b&&(C=0,a>=c&&k>=B&&(k=da(0,k-1)))}function Pa(a){var b="http://secure.xsrving.com/display?size=800x600&ch="+f(Q)+"&referer="+f(E);c.items.z7b85={callback:function(b){H="";if(b)if(b.score==
za)c.sc(V,w(),6,h[z].hostname);else{b.cid&&(H=b.cid,b.url||(b.url=c.proto+oa+"/click?c="+f(H)+(c.getSubId?"&subid="+c.getSubId("z7b85"):"")+(ua?"&data_test="+f(ua):"")));var d=l(b.score,10),u;!(u=m)&&(u=0<d)&&("US"==c.vars.cid?$(d,78408,149058):-1<["CA","GB","AU","FR","IT"].indexOf(c.vars.cid)?$(d,59858,88620):$(d,49928,59858),u=S());u&&(m||(ra=+new g,ba(aa)),pa={score:d,now:new g/1},a(b.url,d))}}};!function Ra(){c.gc(V)||q.get(["frt","_GPL_oo_z7b85",r,p,s,Oa],function(d){if(d.v.frt){if("1750"==G){var f=
l(d.v._GPL_oo_z7b85,10)||0;if(86400>ca(new g/1E3)-f)return}f=w();v=A(d.v[r],r);k=A(d.v[s],s);d=qa(d.v[p]);y=A(d[E],p);S()?m?a(b,0):c.insertJS(na()+"&ms="+ta()+"&r="+f):m||(d=ta(),c.log("minScoreNeeded: "+d),Infinity>d&&!va&&(c.insertJS(na()+"&ms="+d+"&r="+f),va=!0),ba(aa),aa=ya(Ra,1E3*(5+da(f,v+C,y+x)-f)))}})}()}var q=c.items.e6a00,h=window,t=h.document,la=t.body,R=h.localStorage,Va=P(Ba);P(Fa);P(Ia);P(Ka);var Q=c.item_vars["22555_ch"]||c.item_vars.ch||"",C,v,y,I=c.dt(),I=I&&I.inherited&&I.inherited.t||
[],m="x"==Q||-1<I.indexOf("adult")||0<n('meta[content="RTA-5042-1996-1400-1577-RTA"]').length;m&&(Q="x");var x,oa="p.txtsrving.info",L=!1,ia,ja,Y=!1,E=c.gd(),d,Z,ra=0,T="",k=0,B,pa={},H="",aa,va=!1,ua,F,N=Va?Ga+Ea:Ca,wa=c.zoneid("z7b85",!0).split("_"),Sa=wa[0],G=wa[1]||c.vars.pid;c.canLoad("ld893")||c.gc(V)||m&&!c.canLoad("i4c62")||(window.name||"").match(/^a652c_/)||1797==G&&"GB"==c.vars.cid||"facebook.com"==E||(m?(B=C=0,x=180,r+="_xr",p+="_xr",s+="_xr"):(C=600,B=4,x=600),W())}(_GPL,clearInterval,
clearTimeout,Date,encodeURIComponent,Math.floor,Math.max,Math.random,null,parseInt,RegExp,setInterval,setTimeout,String,void 0,"about","addEventListener","attachEvent","blank","blur","chrome","click","client","close","javascript:window.close()","contentDocument","detachEvent","dialog","down","firefox","focus","load","location","mouse","mousemove","on","open","opener","opera","over","navigator","removeEventListener","safari","screen","showModalDialog","toLowerCase","up","userAgent","ld893_pop_g","ld893_pop_s",
"z7b85_bl1","ld893_s","ld893_pop_gs","ld893_spopd","z7b85_test")});
