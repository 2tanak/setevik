/**
 * jquery.Jcrop.min.js v0.9.10 (build:20120626)
 * jQuery Image Cropping Plugin - released under MIT License
 * Copyright (c) 2008-2012 Tapmodo Interactive LLC
 * https://github.com/tapmodo/Jcrop
 */
(function(a){a.Jcrop=function(b,c){function h(a){return Math.round(a)+"px"}function i(a){return d.baseClass+"-"+a}function j(){return a.fx.step.hasOwnProperty("backgroundColor")}function k(b){var c=a(b).offset();return[c.left,c.top]}function l(a){return[a.pageX-e[0],a.pageY-e[1]]}function m(b){typeof b!="object"&&(b={}),d=a.extend(d,b),a.each(["onChange","onSelect","onRelease","onDblClick"],function(a,b){typeof d[b]!="function"&&(d[b]=function(){})})}function n(a,b){e=k(C),bb.setCursor(a==="move"?a:a+"-resize");if(a==="move")return bb.activateHandlers(p(b),u);var c=Z.getFixed(),d=q(a),f=Z.getCorner(q(d));Z.setPressed(Z.getCorner(d)),Z.setCurrent(f),bb.activateHandlers(o(a,c),u)}function o(a,b){return function(c){if(!d.aspectRatio)switch(a){case"e":c[1]=b.y2;break;case"w":c[1]=b.y2;break;case"n":c[0]=b.x2;break;case"s":c[0]=b.x2}else switch(a){case"e":c[1]=b.y+1;break;case"w":c[1]=b.y+1;break;case"n":c[0]=b.x+1;break;case"s":c[0]=b.x+1}Z.setCurrent(c),ba.update()}}function p(a){var b=a;return bc.watchKeys(),function(
a){Z.moveOffset([a[0]-b[0],a[1]-b[1]]),b=a,ba.update()}}function q(a){switch(a){case"n":return"sw";case"s":return"nw";case"e":return"nw";case"w":return"ne";case"ne":return"sw";case"nw":return"se";case"se":return"nw";case"sw":return"ne"}}function r(a){return function(b){return d.disabled?!1:a==="move"&&!d.allowMove?!1:(e=k(C),V=!0,n(a,l(b)),b.stopPropagation(),b.preventDefault(),!1)}}function s(a,b,c){var d=a.width(),e=a.height();d>b&&b>0&&(d=b,e=b/a.width()*a.height()),e>c&&c>0&&(e=c,d=c/a.height()*a.width()),S=a.width()/d,T=a.height()/e,a.width(d).height(e)}function t(a){return{x:a.x*S,y:a.y*T,x2:a.x2*S,y2:a.y2*T,w:a.w*S,h:a.h*T}}function u(a){var b=Z.getFixed();b.w>d.minSelect[0]&&b.h>d.minSelect[1]?(ba.enableHandles(),ba.done()):ba.release(),bb.setCursor(d.allowSelect?"crosshair":"default")}function v(a){if(d.disabled)return!1;if(!d.allowSelect)return!1;V=!0,e=k(C),ba.disableHandles(),bb.setCursor("crosshair");var b=l(a);return Z.setPressed(b),ba.update(),bb.activateHandlers(w,u),bc.watchKeys(),a.stopPropagation
(),a.preventDefault(),!1}function w(a){Z.setCurrent(a),ba.update()}function x(){var b=a("<div></div>").addClass(i("tracker"));return a.browser.msie&&b.css({opacity:0,backgroundColor:"white"}),b}function bd(a){F.removeClass().addClass(i("holder")).addClass(a)}function be(a,b){function t(){window.setTimeout(u,l)}var c=a[0]/S,e=a[1]/T,f=a[2]/S,g=a[3]/T;if(W)return;var h=Z.flipCoords(c,e,f,g),i=Z.getFixed(),j=[i.x,i.y,i.x2,i.y2],k=j,l=d.animationDelay,m=h[0]-j[0],n=h[1]-j[1],o=h[2]-j[2],p=h[3]-j[3],q=0,r=d.swingSpeed;c=k[0],e=k[1],f=k[2],g=k[3],ba.animMode(!0);var s,u=function(){return function(){q+=(100-q)/r,k[0]=Math.round(c+q/100*m),k[1]=Math.round(e+q/100*n),k[2]=Math.round(f+q/100*o),k[3]=Math.round(g+q/100*p),q>=99.8&&(q=100),q<100?(bg(k),t()):(ba.done(),ba.animMode(!1),typeof b=="function"&&b.call(br))}}();t()}function bf(a){bg([a[0]/S,a[1]/T,a[2]/S,a[3]/T]),d.onSelect.call(br,t(Z.getFixed())),ba.enableHandles()}function bg(a){Z.setPressed([a[0],a[1]]),Z.setCurrent([a[2],a[3]]),ba.update()}function bh(){return t
(Z.getFixed())}function bi(){return Z.getFixed()}function bj(a){m(a),bq()}function bk(){d.disabled=!0,ba.disableHandles(),ba.setCursor("default"),bb.setCursor("default")}function bl(){d.disabled=!1,bq()}function bm(){ba.done(),bb.activateHandlers(null,null)}function bn(){F.remove(),z.show(),a(b).removeData("Jcrop")}function bo(a,b){ba.release(),bk();var c=new Image;c.onload=function(){var e=c.width,f=c.height,g=d.boxWidth,h=d.boxHeight;C.width(e).height(f),C.attr("src",a),G.attr("src",a),s(C,g,h),D=C.width(),E=C.height(),G.width(D).height(E),L.width(D+K*2).height(E+K*2),F.width(D).height(E),_.resize(D,E),bl(),typeof b=="function"&&b.call(br)},c.src=a}function bp(a,b,c){var e=b||d.bgColor;d.bgFade&&j()&&d.fadeTime&&!c?a.animate({backgroundColor:e},{queue:!1,duration:d.fadeTime}):a.css("backgroundColor",e)}function bq(a){d.allowResize?a?ba.enableOnly():ba.enableHandles():ba.disableHandles(),bb.setCursor(d.allowSelect?"crosshair":"default"),ba.setCursor(d.allowMove?"move":"default"),d.hasOwnProperty("trueSize")&&
(S=d.trueSize[0]/D,T=d.trueSize[1]/E),d.hasOwnProperty("setSelect")&&(bf(d.setSelect),ba.done(),delete d.setSelect),_.refresh(),d.bgColor!=M&&(bp(d.shade?_.getShades():F,d.shade?d.shadeColor||d.bgColor:d.bgColor),M=d.bgColor),N!=d.bgOpacity&&(N=d.bgOpacity,d.shade?_.refresh():ba.setBgOpacity(N)),O=d.maxSize[0]||0,P=d.maxSize[1]||0,Q=d.minSize[0]||0,R=d.minSize[1]||0,d.hasOwnProperty("outerImage")&&(C.attr("src",d.outerImage),delete d.outerImage),ba.refresh()}var d=a.extend({},a.Jcrop.defaults),e,f,g=!1;a.browser.msie&&a.browser.version.split(".")[0]==="6"&&(g=!0),typeof b!="object"&&(b=a(b)[0]),typeof c!="object"&&(c={}),m(c);var y={border:"none",visibility:"visible",margin:0,padding:0,position:"absolute",top:0,left:0},z=a(b),A=!0;if(b.tagName=="IMG"){if(z[0].width!=0&&z[0].height!=0)z.width(z[0].width),z.height(z[0].height);else{var B=new Image;B.src=z[0].src,z.width(B.width),z.height(B.height)}var C=z.clone().removeAttr("id").css(y).show();C.width(z.width()),C.height(z.height()),z.after(C).hide()}else C=z.css
(y).show(),A=!1,d.shade===null&&(d.shade=!0);s(C,d.boxWidth,d.boxHeight);var D=C.width(),E=C.height(),F=a("<div />").width(D).height(E).addClass(i("holder")).css({position:"relative",backgroundColor:d.bgColor}).insertAfter(z).append(C);d.addClass&&F.addClass(d.addClass);var G=a("<div />"),H=a("<div />").width("100%").height("100%").css({zIndex:310,position:"absolute",overflow:"hidden"}),I=a("<div />").width("100%").height("100%").css("zIndex",320),J=a("<div />").css({position:"absolute",zIndex:600}).dblclick(function(){var a=Z.getFixed();d.onDblClick.call(br,a)}).insertBefore(C).append(H,I);A&&(G=a("<img />").attr("src",C.attr("src")).css(y).width(D).height(E),H.append(G)),g&&J.css({overflowY:"hidden"});var K=d.boundary,L=x().width(D+K*2).height(E+K*2).css({position:"absolute",top:h(-K),left:h(-K),zIndex:290}).mousedown(v),M=d.bgColor,N=d.bgOpacity,O,P,Q,R,S,T,U=!0,V,W,X;e=k(C);var Y=function(){function a(){var a={},b=["touchstart","touchmove","touchend"],c=document.createElement("div"),d;try{for(d=0;d<b.length
;d++){var e=b[d];e="on"+e;var f=e in c;f||(c.setAttribute(e,"return;"),f=typeof c[e]=="function"),a[b[d]]=f}return a.touchstart&&a.touchend&&a.touchmove}catch(g){return!1}}function b(){return d.touchSupport===!0||d.touchSupport===!1?d.touchSupport:a()}return{createDragger:function(a){return function(b){return b.pageX=b.originalEvent.changedTouches[0].pageX,b.pageY=b.originalEvent.changedTouches[0].pageY,d.disabled?!1:a==="move"&&!d.allowMove?!1:(V=!0,n(a,l(b)),b.stopPropagation(),b.preventDefault(),!1)}},newSelection:function(a){return a.pageX=a.originalEvent.changedTouches[0].pageX,a.pageY=a.originalEvent.changedTouches[0].pageY,v(a)},isSupported:a,support:b()}}(),Z=function(){function h(d){d=n(d),c=a=d[0],e=b=d[1]}function i(a){a=n(a),f=a[0]-c,g=a[1]-e,c=a[0],e=a[1]}function j(){return[f,g]}function k(d){var f=d[0],g=d[1];0>a+f&&(f-=f+a),0>b+g&&(g-=g+b),E<e+g&&(g+=E-(e+g)),D<c+f&&(f+=D-(c+f)),a+=f,c+=f,b+=g,e+=g}function l(a){var b=m();switch(a){case"ne":return[b.x2,b.y];case"nw":return[b.x,b.y];case"se":return[
b.x2,b.y2];case"sw":return[b.x,b.y2]}}function m(){if(!d.aspectRatio)return p();var f=d.aspectRatio,g=d.minSize[0]/S,h=d.maxSize[0]/S,i=d.maxSize[1]/T,j=c-a,k=e-b,l=Math.abs(j),m=Math.abs(k),n=l/m,r,s,t,u;return h===0&&(h=D*10),i===0&&(i=E*10),n<f?(s=e,t=m*f,r=j<0?a-t:t+a,r<0?(r=0,u=Math.abs((r-a)/f),s=k<0?b-u:u+b):r>D&&(r=D,u=Math.abs((r-a)/f),s=k<0?b-u:u+b)):(r=c,u=l/f,s=k<0?b-u:b+u,s<0?(s=0,t=Math.abs((s-b)*f),r=j<0?a-t:t+a):s>E&&(s=E,t=Math.abs(s-b)*f,r=j<0?a-t:t+a)),r>a?(r-a<g?r=a+g:r-a>h&&(r=a+h),s>b?s=b+(r-a)/f:s=b-(r-a)/f):r<a&&(a-r<g?r=a-g:a-r>h&&(r=a-h),s>b?s=b+(a-r)/f:s=b-(a-r)/f),r<0?(a-=r,r=0):r>D&&(a-=r-D,r=D),s<0?(b-=s,s=0):s>E&&(b-=s-E,s=E),q(o(a,b,r,s))}function n(a){return a[0]<0&&(a[0]=0),a[1]<0&&(a[1]=0),a[0]>D&&(a[0]=D),a[1]>E&&(a[1]=E),[a[0],a[1]]}function o(a,b,c,d){var e=a,f=c,g=b,h=d;return c<a&&(e=c,f=a),d<b&&(g=d,h=b),[e,g,f,h]}function p(){var d=c-a,f=e-b,g;return O&&Math.abs(d)>O&&(c=d>0?a+O:a-O),P&&Math.abs(f)>P&&(e=f>0?b+P:b-P),R/T&&Math.abs(f)<R/T&&(e=f>0?b+R/T:b-R/T),Q/S&&Math.
abs(d)<Q/S&&(c=d>0?a+Q/S:a-Q/S),a<0&&(c-=a,a-=a),b<0&&(e-=b,b-=b),c<0&&(a-=c,c-=c),e<0&&(b-=e,e-=e),c>D&&(g=c-D,a-=g,c-=g),e>E&&(g=e-E,b-=g,e-=g),a>D&&(g=a-E,e-=g,b-=g),b>E&&(g=b-E,e-=g,b-=g),q(o(a,b,c,e))}function q(a){return{x:a[0],y:a[1],x2:a[2],y2:a[3],w:a[2]-a[0],h:a[3]-a[1]}}var a=0,b=0,c=0,e=0,f,g;return{flipCoords:o,setPressed:h,setCurrent:i,getOffset:j,moveOffset:k,getCorner:l,getFixed:m}}(),_=function(){function f(a,b){e.left.css({height:h(b)}),e.right.css({height:h(b)})}function g(){return i(Z.getFixed())}function i(a){e.top.css({left:h(a.x),width:h(a.w),height:h(a.y)}),e.bottom.css({top:h(a.y2),left:h(a.x),width:h(a.w),height:h(E-a.y2)}),e.right.css({left:h(a.x2),width:h(D-a.x2)}),e.left.css({width:h(a.x)})}function j(){return a("<div />").css({position:"absolute",backgroundColor:d.shadeColor||d.bgColor}).appendTo(c)}function k(){b||(b=!0,c.insertBefore(C),g(),ba.setBgOpacity(1,0,1),G.hide(),l(d.shadeColor||d.bgColor,1),ba.isAwake()?n(d.bgOpacity,1):n(1,1))}function l(a,b){bp(p(),a,b)}function m(){
b&&(c.remove(),G.show(),b=!1,ba.isAwake()?ba.setBgOpacity(d.bgOpacity,1,1):(ba.setBgOpacity(1,1,1),ba.disableHandles()),bp(F,0,1))}function n(a,e){b&&(d.bgFade&&!e?c.animate({opacity:1-a},{queue:!1,duration:d.fadeTime}):c.css({opacity:1-a}))}function o(){d.shade?k():m(),ba.isAwake()&&n(d.bgOpacity)}function p(){return c.children()}var b=!1,c=a("<div />").css({position:"absolute",zIndex:240,opacity:0}),e={top:j(),left:j().height(E),right:j().height(E),bottom:j()};return{update:g,updateRaw:i,getShades:p,setBgColor:l,enable:k,disable:m,resize:f,refresh:o,opacity:n}}(),ba=function(){function k(b){var c=a("<div />").css({position:"absolute",opacity:d.borderOpacity}).addClass(i(b));return H.append(c),c}function l(b,c){var d=a("<div />").mousedown(r(b)).css({cursor:b+"-resize",position:"absolute",zIndex:c}).addClass("ord-"+b);return Y.support&&d.bind("touchstart.jcrop",Y.createDragger(b)),I.append(d),d}function m(a){var b=d.handleSize;return l(a,c++).css({opacity:d.handleOpacity}).width(b).height(b).addClass(i("handle"
))}function n(a){return l(a,c++).addClass("jcrop-dragbar")}function o(a){var b;for(b=0;b<a.length;b++)g[a[b]]=n(a[b])}function p(a){var b,c;for(c=0;c<a.length;c++){switch(a[c]){case"n":b="hline";break;case"s":b="hline bottom";break;case"e":b="vline right";break;case"w":b="vline"}e[a[c]]=k(b)}}function q(a){var b;for(b=0;b<a.length;b++)f[a[b]]=m(a[b])}function s(a,b){d.shade||G.css({top:h(-b),left:h(-a)}),J.css({top:h(b),left:h(a)})}function u(a,b){J.width(Math.round(a)).height(Math.round(b))}function v(){var a=Z.getFixed();Z.setPressed([a.x,a.y]),Z.setCurrent([a.x2,a.y2]),w()}function w(a){if(b)return y(a)}function y(a){var c=Z.getFixed();u(c.w,c.h),s(c.x,c.y),d.shade&&_.updateRaw(c),b||A(),a?d.onSelect.call(br,t(c)):d.onChange.call(br,t(c))}function z(a,c,e){if(!b&&!c)return;d.bgFade&&!e?C.animate({opacity:a},{queue:!1,duration:d.fadeTime}):C.css("opacity",a)}function A(){J.show(),d.shade?_.opacity(N):z(N,!0),b=!0}function B(){F(),J.hide(),d.shade?_.opacity(1):z(1),b=!1,d.onRelease.call(br)}function D(){j&&I.
show()}function E(){j=!0;if(d.allowResize)return I.show(),!0}function F(){j=!1,I.hide()}function K(a){a?(W=!0,F()):(W=!1,E())}function L(){K(!1),v()}var b,c=370,e={},f={},g={},j=!1;d.dragEdges&&a.isArray(d.createDragbars)&&o(d.createDragbars),a.isArray(d.createHandles)&&q(d.createHandles),d.drawBorders&&a.isArray(d.createBorders)&&p(d.createBorders),a(document).bind("touchstart.jcrop-ios",function(b){a(b.currentTarget).hasClass("jcrop-tracker")&&b.stopPropagation()});var M=x().mousedown(r("move")).css({cursor:"move",position:"absolute",zIndex:360});return Y.support&&M.bind("touchstart.jcrop",Y.createDragger("move")),H.append(M),F(),{updateVisible:w,update:y,release:B,refresh:v,isAwake:function(){return b},setCursor:function(a){M.css("cursor",a)},enableHandles:E,enableOnly:function(){j=!0},showHandles:D,disableHandles:F,animMode:K,setBgOpacity:z,done:L}}(),bb=function(){function f(){L.css({zIndex:450}),Y.support&&a(document).bind("touchmove.jcrop",k).bind("touchend.jcrop",m),e&&a(document).bind("mousemove.jcrop"
,h).bind("mouseup.jcrop",i)}function g(){L.css({zIndex:290}),a(document).unbind(".jcrop")}function h(a){return b(l(a)),!1}function i(a){return a.preventDefault(),a.stopPropagation(),V&&(V=!1,c(l(a)),ba.isAwake()&&d.onSelect.call(br,t(Z.getFixed())),g(),b=function(){},c=function(){}),!1}function j(a,d){return V=!0,b=a,c=d,f(),!1}function k(a){return a.pageX=a.originalEvent.changedTouches[0].pageX,a.pageY=a.originalEvent.changedTouches[0].pageY,h(a)}function m(a){return a.pageX=a.originalEvent.changedTouches[0].pageX,a.pageY=a.originalEvent.changedTouches[0].pageY,i(a)}function n(a){L.css("cursor",a)}var b=function(){},c=function(){},e=d.trackDocument;return e||L.mousemove(h).mouseup(i).mouseout(i),C.before(L),{activateHandlers:j,setCursor:n}}(),bc=function(){function e(){d.keySupport&&(b.show(),b.focus())}function f(a){b.hide()}function h(a,b,c){d.allowMove&&(Z.moveOffset([b,c]),ba.updateVisible(!0)),a.preventDefault(),a.stopPropagation()}function i(a){if(a.ctrlKey||a.metaKey)return!0;X=a.shiftKey?!0:!1;var b=X?10
:1;switch(a.keyCode){case 37:h(a,-b,0);break;case 39:h(a,b,0);break;case 38:h(a,0,-b);break;case 40:h(a,0,b);break;case 27:d.allowSelect&&ba.release();break;case 9:return!0}return!1}var b=a('<input type="radio" />').css({position:"fixed",left:"-120px",width:"12px"}).addClass("jcrop-keymgr"),c=a("<div />").css({position:"absolute",overflow:"hidden"}).append(b);return d.keySupport&&(b.keydown(i).blur(f),g||!d.fixedSupport?(b.css({position:"absolute",left:"-20px"}),c.append(b).insertBefore(C)):b.insertBefore(C)),{watchKeys:e}}();Y.support&&L.bind("touchstart.jcrop",Y.newSelection),I.hide(),bq(!0);var br={setImage:bo,animateTo:be,setSelect:bf,setOptions:bj,tellSelect:bh,tellScaled:bi,setClass:bd,disable:bk,enable:bl,cancel:bm,release:ba.release,destroy:bn,focus:bc.watchKeys,getBounds:function(){return[D*S,E*T]},getWidgetSize:function(){return[D,E]},getScaleFactor:function(){return[S,T]},getOptions:function(){return d},ui:{holder:F,selection:J}};return a.browser.msie&&F.bind("selectstart",function(){return!1}),z.data
("Jcrop",br),br},a.fn.Jcrop=function(b,c){var d;return this.each(function(){if(a(this).data("Jcrop")){if(b==="api")return a(this).data("Jcrop");a(this).data("Jcrop").setOptions(b)}else this.tagName=="IMG"?a.Jcrop.Loader(this,function(){a(this).css({display:"block",visibility:"hidden2"}),d=a.Jcrop(this,b),a.isFunction(c)&&c.call(d)}):(a(this).css({display:"block",visibility:"hidden"}),d=a.Jcrop(this,b),a.isFunction(c)&&c.call(d))}),this},a.Jcrop.Loader=function(b,c,d){function g(){f.complete?(e.unbind(".jcloader"),a.isFunction(c)&&c.call(f)):window.setTimeout(g,50)}var e=a(b),f=e[0];e.bind("load.jcloader",g).bind("error.jcloader",function(b){e.unbind(".jcloader"),a.isFunction(d)&&d.call(f)}),f.complete&&a.isFunction(c)&&(e.unbind(".jcloader"),c.call(f))},a.Jcrop.defaults={allowSelect:!0,allowMove:!0,allowResize:!0,trackDocument:!0,baseClass:"jcrop",addClass:null,bgColor:"black",bgOpacity:.6,bgFade:!1,borderOpacity:.4,handleOpacity:.5,handleSize:7,aspectRatio:0,keySupport:!0,createHandles:["n","s","e","w","nw","ne"
,"se","sw"],createDragbars:["n","s","e","w"],createBorders:["n","s","e","w"],drawBorders:!0,dragEdges:!0,fixedSupport:!0,touchSupport:null,shade:null,boxWidth:0,boxHeight:0,boundary:2,fadeTime:400,animationDelay:20,swingSpeed:3,minSelect:[0,0],maxSize:[0,0],minSize:[0,0],onChange:function(){},onSelect:function(){},onDblClick:function(){},onRelease:function(){}}})(jQuery);