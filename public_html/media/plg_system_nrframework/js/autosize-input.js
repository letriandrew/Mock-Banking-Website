!function(){var a=/\s/g,d=/>/g,f=/</g;var l="__autosizeInputGhost";function s(){var t=document.createElement("div");return t.id=l,t.style.cssText="display:inline-block;height:0;overflow:hidden;position:absolute;top:0;visibility:hidden;white-space:nowrap;",document.body.appendChild(t),t}var g="";document.addEventListener("DOMContentLoaded",function(){g=s()}),window.tfAutosizeInput=function(e,t){var n=window.getComputedStyle(e),i="box-sizing:"+n.boxSizing+";border-left:"+n.borderLeftWidth+" solid black;border-right:"+n.borderRightWidth+" solid black;font-family:"+n.fontFamily+";font-feature-settings:"+n.fontFeatureSettings+";font-kerning:"+n.fontKerning+";font-size:"+n.fontSize+";font-stretch:"+n.fontStretch+";font-style:"+n.fontStyle+";font-variant:"+n.fontVariant+";font-variant-caps:"+n.fontVariantCaps+";font-variant-ligatures:"+n.fontVariantLigatures+";font-variant-numeric:"+n.fontVariantNumeric+";font-weight:"+n.fontWeight+";letter-spacing:"+n.letterSpacing+";margin-left:"+n.marginLeft+";margin-right:"+n.marginRight+";padding-left:"+n.paddingLeft+";padding-right:"+n.paddingRight+";text-indent:"+n.textIndent+";text-transform:"+n.textTransform;function o(t){t=t||e.value||e.getAttribute("placeholder")||"",null===document.getElementById(l)&&(g=s()),g.style.cssText+=i,g.innerHTML=function(t){return t.replace(a,"&nbsp;").replace(d,"&lt;").replace(f,"&gt;")}(t);var n=window.getComputedStyle(g).width;return e.style.width=n}e.addEventListener("input",function(){o()});var r=o();return t&&t.minWidth&&"0px"!==r&&(e.style.minWidth=r),o}}(window,document);

