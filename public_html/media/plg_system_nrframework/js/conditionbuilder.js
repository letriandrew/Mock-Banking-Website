var TF_Condition_Builder=function(){function e(e){this.app_ajax_url="?option=com_ajax&format=raw&plugin=nrframework&task=ConditionBuilder",this.wrapper=e,this.isJ4=Joomla.Modal,this.root_url=this.wrapper.dataset.root,this.site_url=this.root_url.replace("/administrator",""),this.token=this.wrapper.dataset.token,this.init()}var t=e.prototype;return t.init=function(){this.initEvents(),this.initLoadConditions()},t.initEvents=function(){this.prepare(),document.addEventListener("click",function(e){this.addConditionEvent(e),this.deleteConditionEvent(e),this.deleteGroupConditionEvent(e)}.bind(this)),document.addEventListener("change",function(e){this.handleConditionSelector(e)}.bind(this)),jQuery(document).on("change",".condition_selector",function(e){this.handleConditionSelector(e)}.bind(this)),document.addEventListener("afterConditionSettings",function(e){this.loadConditionAssets(e.detail.condition_name,e.detail.element)}.bind(this))},t.prepare=function(){NRHelper.loadStyleSheet(this.site_url+"/media/plg_system_nrframework/css/toggle.css")},t.initLoadConditions=function(){var e=this.wrapper.previousElementSibling.value;if(e){var t={data:e,name:this.wrapper.previousElementSibling.getAttribute("name"),include_rules:this.wrapper.dataset.includeRules,exclude_rules:this.wrapper.dataset.excludeRules,exclude_rules_pro:this.wrapper.dataset.excludeRulesPro},r=this;this.call("init_load",t,function(e){var t=r.wrapper.querySelector(".tf-conditionbuilder-initial-message");t&&t.remove(),r.wrapper.querySelector(".cb-groups").innerHTML=e,r.beautifyProConditions(r.wrapper),r.getValidRules().forEach(function(e){r.loadConditionAssets(e.value,e.closest(".cb-item"))}),r.wrapper.classList.add("init-load-done")})}else this.wrapper.querySelector(".tf-cb-add-new-group").click()},t.loadConditionAssets=function(e,t){var r=this;switch(e){case"Joomla\\UserGroup":case"Joomla\\Menu":case"Component\\ContentCategory":case"Component\\K2Category":case"Component\\VirtueMartCategory":case"Component\\HikashopCategory":NRHelper.loadStyleSheet(this.site_url+"/media/plg_system_nrframework/css/treeselect.css"),NRHelper.loadScript(this.site_url+"/media/plg_system_nrframework/js/treeselect.js",function(){NRTreeselect.init(t)},!0);break;case"Date\\Date":NRHelper.loadStyleSheet(this.site_url+"/media/system/css/fields/calendar.css"),NRHelper.loadScript(this.site_url+"/media/system/js/fields/calendar-locales/en.js"),NRHelper.loadScript(this.site_url+"/media/system/js/fields/calendar-locales/date/gregorian/date-helper.min.js"),NRHelper.loadScript(this.site_url+"/media/system/js/fields/calendar.min.js",function(){t.querySelectorAll(".field-calendar").forEach(function(e){JoomlaCalendar.init(e)})},!0);break;case"Date\\Time":NRHelper.loadStyleSheet(this.site_url+"/media/plg_system_nrframework/css/vendor/jquery-clockpicker.min.css"),NRHelper.loadScript(this.site_url+"/media/plg_system_nrframework/js/vendor/jquery-clockpicker.min.js",function(){t.querySelectorAll(".clockpicker").forEach(function(e){jQuery(e).clockpicker()})},!0);break;case"URL":case"Joomla\\UserID":case"Geo\\City":case"Geo\\Region":case"Referrer":case"IP":case"Component\\VirtueMartCartContainsProducts":case"Component\\HikashopCartContainsProducts":this.isJ4?NRHelper.loadScript(this.site_url+"/media/system/js/fields/joomla-field-subform.js"):NRHelper.loadScript(this.site_url+"/media/jui/js/jquery.ui.core.min.js",function(){NRHelper.loadScript(r.site_url+"/media/jui/js/jquery.ui.sortable.min.js",function(){NRHelper.loadScript(r.site_url+"/media/system/js/subform-repeatable.js")})}),NRHelper.loadStyleSheet(this.site_url+"/media/plg_system_nrframework/css/tfinputrepeater.css"),NRHelper.loadScript(this.site_url+"/media/plg_system_nrframework/js/tfinputrepeater.js");case"Joomla\\UserID":this.isJ4?NRHelper.loadScript(this.site_url+"/media/system/js/fields/joomla-field-user.min.js"):NRHelper.loadScript(this.site_url+"/media/jui/js/fielduser.min.js");case"Component\\VirtueMartCartContainsProducts":case"Component\\HikashopCartContainsProducts":this.loadSelect2();break;case"Component\\K2Item":case"Component\\ContentArticle":case"Component\\VirtueMartSingle":case"Component\\HikashopSingle":this.loadSelect2()}NRHelper.loadStyleSheet(this.site_url+"/media/plg_system_nrframework/css/toggle.css"),"Date"!=e&&jQuery(document).trigger("subform-row-add",[t]),this.isJ4&&this.fixShowOnElements(t)},t.loadSelect2=function(){var e=this;NRHelper.loadStyleSheet(this.site_url+"/media/plg_system_nrframework/css/select2.css"),NRHelper.loadScript(this.site_url+"/media/plg_system_nrframework/js/vendor/select2.min.js",function(){NRHelper.loadScript(e.site_url+"/media/plg_system_nrframework/js/ajaxify.js",!1,!0)},!0)},t.fixShowOnElements=function(t){t.querySelectorAll("[data-showon]").forEach(function(e){e.removeAttribute("data-showon-initialised"),Joomla.Showon.initialise(t)})},t.handleConditionSelector=function(e){var t=e.target.closest(".condition_selector");if(t&&t.value){e.preventDefault();var r=t.closest(".cb-item");r.classList.add("ajax-loading");this.loadConditionSettings(r,t.value,function(){r.classList.remove("ajax-loading"),r.querySelector(".cb-item-content").querySelectorAll("select.hasChosen").forEach(function(e){jQuery(e).chosen("destroy"),jQuery(e).chosen({disable_search_threshold:10,inherit_select_classes:!0})})})}},t.loadConditionSettings=function(r,i,o){var e=parseInt(r.closest(".cb-group").dataset.key),t=parseInt(r.closest(".cb-item").dataset.key),n={conditionItemGroup:this.wrapper.previousElementSibling.getAttribute("name")+"["+e+"][rules]["+t+"]",name:i,request_option:this.wrapper.dataset.option,request_layout:this.wrapper.dataset.layout};this.call("options",n,function(e){e=""!==e?e:'<div class="select-condition-message">'+Joomla.JText._("NR_CB_SELECT_CONDITION_GET_STARTED")+"</div>",r.querySelector(".cb-item-content").innerHTML=e;var t=new CustomEvent("afterConditionSettings",{detail:{element:r,condition_name:i}});document.dispatchEvent(t),o&&o()})},t.beautifyProConditions=function(e){var t=e.querySelectorAll(".condition_selector:not(.tf-cb-prepared)");if(0!==t.length){function r(e){var t=e.nextElementSibling.querySelectorAll("li.disabled-result.group-option");0!==t.length&&t.forEach(function(e){e.classList.add("is-pro"),e.querySelector(".locks")||(e.setAttribute("data-pro-only",e.innerHTML),e.innerHTML+='<div class="locks"><svg xmlns="http://www.w3.org/2000/svg" width="20" fill="currentColor" viewBox="0 96 960 960"><path d="M220 976q-24.75 0-42.375-17.625T160 916V482q0-24.75 17.625-42.375T220 422h70v-96q0-78.85 55.606-134.425Q401.212 136 480.106 136T614.5 191.575Q670 247.15 670 326v96h70q24.75 0 42.375 17.625T800 482v434q0 24.75-17.625 42.375T740 976H220Zm0-60h520V482H220v434Zm260.168-140Q512 776 534.5 753.969T557 701q0-30-22.668-54.5t-54.5-24.5Q448 622 425.5 646.5t-22.5 55q0 30.5 22.668 52.5t54.5 22ZM350 422h260v-96q0-54.167-37.882-92.083-37.883-37.917-92-37.917Q426 196 388 233.917 350 271.833 350 326v96ZM220 916V482v434Z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="20" fill="currentColor" viewBox="0 96 960 960"><path d="M220 422h390v-96q0-54.167-37.882-92.083-37.883-37.917-92-37.917Q426 196 388 233.917 350 271.833 350 326h-60q0-79 55.606-134.5t134.5-55.5Q559 136 614.5 191.575T670 326v96h70q24.75 0 42.375 17.625T800 482v434q0 24.75-17.625 42.375T740 976H220q-24.75 0-42.375-17.625T160 916V482q0-24.75 17.625-42.375T220 422Zm0 494h520V482H220v434Zm260.168-140Q512 776 534.5 753.969T557 701q0-30-22.668-54.5t-54.5-24.5Q448 622 425.5 646.5t-22.5 55q0 30.5 22.668 52.5t54.5 22ZM220 916V482v434Z"/></svg></div>')})}var i=new MutationObserver(function(e,t){var r=e,i=Array.isArray(r),o=0;for(r=i?r:r[Symbol.iterator]();;){var n;if(i){if(o>=r.length)break;n=r[o++]}else{if((o=r.next()).done)break;n=o.value}var s=n;(s.target.closest(".chosen-results")||s.target.closest(".chzn-results"))&&s.target.closest(".hasChosen")&&s.target.closest(".hasChosen").previousElementSibling&&(jQuery(s.target.closest(".hasChosen").previousElementSibling).trigger("chosen:showing_dropdown").trigger("chzn:showing_dropdown"),t.disconnect())}}),o={attributes:!0,childList:!0,characterData:!0,subtree:!0};t.forEach(function(t){t.classList.add("tf-cb-prepared"),jQuery(t).on("chosen:ready",function(e){t.nextElementSibling.querySelector(".chosen-search input").addEventListener("keydown",function(e){i.observe(t.nextElementSibling.querySelector(".chosen-results"),o)})}),jQuery(t).on("chosen:showing_dropdown",function(e){r(t)}),jQuery(t).on("liszt:ready",function(e){t.nextElementSibling.querySelector(".chzn-search input").addEventListener("keydown",function(e){i.observe(t.nextElementSibling.querySelector(".chzn-results"),o)})}),jQuery(t).on("liszt:showing_dropdown",function(e){r(t)})})}},t.deleteGroupConditionEvent=function(e){if(e.target.closest(".removeGroupCondition")){e.preventDefault();var t=e.target.closest(".cb-group");this.getValidRules(t).length&&!confirm(Joomla.JText._("NR_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ITEM"))||(1==this.getTotalConditionGroups()?(t.querySelectorAll(".cb-item:not(:first-child)").forEach(function(e){e.remove()}),this.resetCondition(t.querySelector(".cb-item"))):t.remove())}},t.deleteConditionEvent=function(e){if(e.target.closest(".tf-cb-remove-condition")){e.preventDefault();var t=e.target.closest(".cb-item");0!==t.querySelector(".condition_selector").selectedIndex&&!confirm(Joomla.JText._("NR_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ITEM"))||(1!=this.getTotalConditionItems()?this.deleteCondition(t):this.resetCondition(t))}},t.deleteCondition=function(e){var t=e.closest(".cb-group");e.remove(),0==t.querySelectorAll(".cb-item").length&&t.remove()},t.resetCondition=function(e){e.querySelector(".condition_selector").selectedIndex=0,jQuery(e.querySelector(".condition_selector")).chosen("destroy"),jQuery(e.querySelector(".condition_selector")).chosen({disable_search_threshold:10,inherit_select_classes:!0}),jQuery(e.querySelector(".condition_selector")).trigger("change")},t.getTotalConditionGroups=function(){return this.wrapper.querySelectorAll(".cb-group").length},t.getValidRules=function(e){void 0===e&&(e=this.wrapper);var t=e.querySelectorAll("select.condition_selector"),r=[];return t.forEach(function(e){0!==e.selectedIndex&&r.push(e)}),r},t.getTotalConditionItems=function(){return this.wrapper.querySelectorAll(".cb-item").length},t.addConditionEvent=function(e){var t=e.target.closest(".tf-cb-add-new-group");if(t){e.preventDefault();var r=t.closest(".cb-item")||t,i=r.closest(".cb-group"),o=groupKey=0;o=this.addingNewGroup(r)?(groupKey=this.findHighestGroupKey()+1,0):(groupKey=parseInt(i.dataset.key),this.findHighestGroupItemKey(i)+1),r.classList.add("ajax-loading");var n=this;this.addCondition(r,this.wrapper.previousElementSibling.getAttribute("name"),groupKey,o,function(e){r.classList.remove("ajax-loading"),n.wrapper.classList.add("init-load-done"),n.beautifyProConditions(e)})}},t.findHighestGroupKey=function(){return Math.max.apply(Math,Array.from(this.wrapper.querySelectorAll(".cb-group[data-key]")).map(function(e){return parseInt(e.dataset.key)}))},t.findHighestGroupItemKey=function(e){return Math.max.apply(Math,Array.from(e.querySelectorAll(".cb-item[data-key]")).map(function(e){return parseInt(e.dataset.key)}))},t.addCondition=function(o,e,t,r,n){var s=this.addingNewGroup(o),i={conditionItemGroup:e,groupKey:t,conditionKey:r,include_rules:this.wrapper.dataset.includeRules,exclude_rules:this.wrapper.dataset.excludeRules,exclude_rules_pro:this.wrapper.dataset.excludeRulesPro,addingNewGroup:s},a=this;this.call("add",i,function(e){var t=document.createElement("div");t.innerHTML=e;var r=a.wrapper.querySelector(".tf-conditionbuilder-initial-message");r&&r.remove();var i=null;i=s?(a.wrapper.querySelector(".cb-groups").insertAdjacentHTML("beforeend",t.innerHTML),a.wrapper.querySelector(".cb-group:last-of-type")):(o.closest(".item-group-footer")?o.closest(".cb-group").querySelector(".cb-items").insertAdjacentHTML("beforeend",t.innerHTML):o.insertAdjacentHTML("afterend",t.innerHTML),o.closest(".cb-group")),n&&n(i)})},t.addingNewGroup=function(e){return!!e&&!e.closest(".cb-group")},t.call=function(e,t,r){var i=this,o=this.root_url+this.app_ajax_url+"&"+this.token+"=1";t.subtask=e,fetch(o,{method:"post",body:JSON.stringify(t)}).then(function(e){return e.text()}).then(function(e){r(e),i.wrapper.querySelectorAll("select.hasChosen").forEach(function(e){jQuery(e).chosen("destroy"),jQuery(e).chosen({disable_search_threshold:10,inherit_select_classes:!0})}),i.wrapper.querySelectorAll(".hasPopover").forEach(function(e){jQuery(e).popover({html:!0,trigger:"hover focus",container:"body"})})}).catch(function(e){alert(e)})},e}(),TF_Condition_Builder_Loader=function(){function e(){this.init()}return e.prototype.init=function(){!function(){if(window.IntersectionObserver){var t=new IntersectionObserver(function(e,t){e.forEach(function(e){e.isIntersecting&&(new TF_Condition_Builder(e.target),t.unobserve(e.target))})},{rootMargin:"0px 0px 0px 0px"});document.querySelectorAll("div.cb").forEach(function(e){t.observe(e)})}}()},e}();!function(){"use strict";document.addEventListener("DOMContentLoaded",function(){new TF_Condition_Builder_Loader})}(window);
