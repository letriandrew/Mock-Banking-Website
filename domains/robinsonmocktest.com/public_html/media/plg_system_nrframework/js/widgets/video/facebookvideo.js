function _inheritsLoose(t,e){t.prototype=Object.create(e.prototype),(t.prototype.constructor=t).__proto__=e}var TF_FacebookVideo_Video=function(o){function t(t){var e;return(e=o.call(this,t)||this).player=null,e}_inheritsLoose(t,o);var e=t.prototype;return e.init=function(){var t=this.initFacebookVideo.bind(this);this.maybeLoadFbRoot(),this.maybeLoadAPIScript(t)},e.initFacebookVideo=function(){if(this.addFacebookVideo(),"object"==typeof FB){var o=this;FB.XFBML.parse(this.video),FB.Event.subscribe("xfbml.ready",function(t){var e=o.video.id+"_"+o.video.querySelector(".tf-video-embed").dataset.videoId;"video"===t.type&&e===t.id&&(o.player=t.instance)})}},e.pause=function(){this.player&&this.player.pause()},e.maybeLoadFbRoot=function(){if(!document.getElementById("fb-root")){var t=document.createElement("div");t.id="fb-root",document.body.appendChild(t)}},e.maybeLoadAPIScript=function(t){if(document.querySelector(".tf-fb-sdk-api-script"))t&&t();else{var e=document.createElement("script");e.className="tf-fb-sdk-api-script",e.id="tf-fb-sdk-api-script",e.async=!0,e.src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2",t&&(e.onload=t);var o=document.getElementsByTagName("script")[0];o.parentNode.insertBefore(e,o)}},e.addFacebookVideo=function(){var t='\n\t\t\t<div\n\t\t\t\tclass="fb-video"\n\t\t\t\tid="'+this.video.id+"_"+this.dataset.videoId+'"\n\t\t\t\tdata-href="'+this.dataset.videoId+'"\n\t\t\t\tdata-allowfullscreen="'+this.dataset.videoFs+'"\n\t\t\t\tdata-autoplay="'+this.dataset.videoAutoplay+'"\n\t\t\t\tdata-width="'+this.dataset.videoWidth+'"\n\t\t\t\tdata-show-text="'+this.dataset.videoShowText+'"\n\t\t\t\tdata-show-captions="'+this.dataset.videoShowCaptions+'"\n\t\t\t\tdata-lazy="false"\n\t\t\t\t>\n\t\t\t</div>\n\t\t';this.videoElement.innerHTML=t},t}(TF_Video);
