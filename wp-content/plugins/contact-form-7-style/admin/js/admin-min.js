jQuery(document).ready(function(e){function t(t){t.find("label").length<1?e('.button[data-property="label"]').hide():e('.button[data-property="label"]').show(),t.find("p").length<1?e('.button[data-property="p"]').hide():e('.button[data-property="p"]').show(),t.find("fieldset").length<1?e('.button[data-property="fieldset"]').hide():e('.button[data-property="fieldset"]').show(),t.find("select").length<1?e('.button[data-property="select"]').hide():e('.button[data-property="select"]').show(),t.find('input[type="checkbox"]').length<1?e('.button[data-property="checkbox"]').hide():e('.button[data-property="checkbox"]').show(),t.find('input[type="radio"]').length<1?e('.button[data-property="radio"]').hide():e('.button[data-property="radio"]').show()}function a(e,t){"valid"==t?e.css("border-color","#ddd"):e.css("border-color","red")}var n=e(".cf7style-name"),i=e(".cf7style-email"),r=e(".cf7style-message"),o=e(".cf7style-status-submit");function s(t){e(".google-fontos").remove(),"none"!=t&&void 0!==t&&(e("head").append('<link class="google-fontos" rel="stylesheet" href="https://fonts.googleapis.com/css?family='+t+':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese" />'),e(".cf7-style.preview-zone p").css("font-family","'"+t+"', sans-serif"),e(".preview-form-container .wpcf7").css("font-family","'"+t+"', sans-serif"))}function l(){e("input[type='number']").on("change",function(){var t=e(this),a=t.val(),n=t.parent().index(),i=t.parent().parent().find("input[type=number]");switch(n){case 1:i.each(function(){parseFloat(e(this).attr("step"))==parseFloat(t.attr("step"))&&e(this).val(a)});break;case 2:parseFloat(i.eq(3).attr("step"))==parseFloat(t.attr("step"))&&i.eq(3).val(a)}})}function c(t){if(e('input[name="cf7styleallvalues"]').length>0){var a=e('input[name="cf7styleallvalues"]').val(),n=e.parseJSON(a.replace(/'/g,'"'));e(".place-style").remove(),e.each(n,function(a,i){if(a.indexOf("unit")<0&&("hover"==t&&a.indexOf("hover")>0||"hover"!=t&&a.indexOf("hover")<0)){var r=a.split("_"),o=r[0],s="hover"==t&&a.indexOf("hover")>0?n[a.replace("hover","")+"unit_hover"]:n[a+"_unit"];if("placeholder"==r[0]&&""!=i){var l=i+(s=void 0===s||""==i?"":s),c=e("<style>").attr("class","place-style");return c.text(".preview-form-container ::-webkit-input-placeholder { "+r[1]+": "+l+";}.preview-form-container ::-moz-placeholder { "+r[1]+": "+l+";}.preview-form-container :-ms-input-placeholder { "+r[1]+": "+l+";}.preview-form-container :-moz-placeholder { "+r[1]+": "+l+";}"),void c.appendTo("head")}"submit"==r[0]&&(o="input[type='submit']"),"form"==r[0]&&(o=".wpcf7"),"wpcf7-not-valid-tip"!=r[0]&&"wpcf7-validation-errors"!=r[0]&&"wpcf7-mail-sent-ok"!=r[0]||(o="."+r[0]);l=i+(s=void 0===s||""==i?"":s);"background-image"==r[1]&&(l="url("+i+")"),e(".preview-form-container "+(o="radio"==o?'input[type="radio"]':"checkbox"==o?'input[type="checkbox"]':o)).css(r[1],l)}})}}function p(t){var a="",n=e.parseJSON(e('input[name="cf7styleallvalues"]').val().replace(/'/g,'"'));e.each(t.serializeObject(),function(e,t){0==n.length&&(n={}),n[e.replace(/cf7stylecustom\[/g,"").replace(/]/g,"")]=t}),a=(a=JSON.stringify(n)).replace(/cf7stylecustom\[/g,"").replace(/]/g,"").replace(/"/g,"'"),e('input[name="cf7styleallvalues"]').val(a),e('input[name="cf7styleallvalues"]').attr("value",a)}function d(){e('.wpcf7 input[aria-required="true"]').each(function(){e('<span role="alert" class="wpcf7-not-valid-tip">Required field message example.</span>').insertAfter(e(this))}),e(".wpcf7").each(function(){e('<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;" role="alert">Error message example.</div>').appendTo(e(this)),e('<div class="wpcf7-response-output wpcf7-display-none wpcf7-mail-sent-ok" style="display: block;" role="alert">Thank you message example.</div>').appendTo(e(this))})}function u(){var t=e(".cf7-style-upload-field");t.addClass("hidden"),t.each(function(){var t=e(this);e('<span class="image-info-box"></span>').insertAfter(t),""!=t.val()&&t.parent().find(".image-info-box").text(t.val().filename("yes"))}),e(".upload-btn").length<=0&&(e("<a href='javascript: void(0);' class='remove-btn button'>Remove</a>").insertAfter(t),e("<a href='javascript: void(0);' class='upload-btn button'>Upload</a>").insertAfter(t)),e(".upload-btn").on("click",function(){var t=e(this),a=t.parent().find(".cf7-style-upload-field");tb_show("New Banner","media-upload.php?type=image&TB_iframe=1"),window.send_to_editor=function(n){a.val(e(n).attr("src")),a.trigger("change"),t.parent().find(".image-info-box").text(e(n).attr("src").filename("yes")),tb_remove()}}),e(".remove-btn").on("click",function(){var t=e(this),a=t.parent().find(".cf7-style-upload-field");a.val(" "),a.attr("value"," "),a.trigger("change"),t.parent().find(".image-info-box").text("")})}function f(){e(".wp-picker-container").each(function(){e(this).parent().find('label[for*="_color"]').length<1&&e('<label><input type="checkbox" class="transparent-box" name="transparent-box">Transparent</label>').insertAfter(e(this))}),e(".transparent-box").each(function(){"transparent"==e(this).parent().parent().find(".cf7-style-color-field").val()&&e(this).prop("checked",!0)}),e(".transparent-box").on("click",function(){var t=e(this).parent().parent();e(this).is(":checked")?(t.find(".cf7-style-color-field").val("transparent"),t.find(".cf7-style-color-field").attr("value","transparent"),t.find(".wp-color-result").css("background-color","transparent")):(t.find(".cf7-style-color-field").val(""),t.find(".cf7-style-color-field").attr("value","")),p(e(this).parents(".panel").find('[name^="cf7stylecustom"]'))})}function h(e){return"%"==e.val()||"em"==e.val()?"0.01":"1"}function v(){e('.panel input[type="number"]:not([id*="opacity"])').each(function(){var t=e(this);t.attr("step",h(t.next()))}),e('.panel select[name*="unit"]').off("change").on("change",function(){var t=e(this);if(t.prev().attr("step",h(t)),"px"==t.val()){var a=Math.floor(t.prev().val());t.prev().val(a),t.prev().attr("value",a)}})}o.on("click",function(t){if(t.preventDefault(),e(".cf7style-input").each(function(t,n){""==e(this).val()?a(e(this),"error"):a(e(this),"valid")}),""!==n.val()&&""!==i.val())if(l=i.val(),/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i.test(l)){a(i,"valid");var s=e("<div />");e(".cf7style-status-table").each(function(t,a){var n=e("<table />");n.html(e(this).html()),s.append(n)}),e.ajax({url:ajaxurl,method:"POST",data:{action:"cf7_style_send_status_report",name:n.val(),email:i.val(),message:r.val(),report:s.html()},beforeSend:function(){o.text("Sending...")},success:function(t){"success"==e.trim(t)?o.text("Report sent").removeClass("cf7style-status-submit").attr("disabled","disabled"):o.text("Something went wrong!").removeClass("cf7style-status-submit").attr("disabled","disabled")}})}else a(i,"error");else console.log("error 1");var l}),e(".cf7style-status-info").on("click",function(t){t.preventDefault(),e(".cf7style-status-table").toggle()}),String.prototype.filename=function(e){var t=this.replace(/\\/g,"/");return t=t.substring(t.lastIndexOf("/")+1),e?t.replace(/[?#].+$/,""):t.split(".")[0]},e.fn.serializeObject=function(){var t={},a=this.serializeArray();return e.each(a,function(){void 0!==t[this.name]?(t[this.name].push||(t[this.name]=[t[this.name]]),t[this.name].push(this.value||"")):t[this.name]=this.value||""}),t},e(".cf7style-no-forms-added").length>0?e(".generate-preview-button, .generate-preview-option").show():e(".generate-button-hidden").show(),e(".generate-preview-button").on("click",function(a){a.preventDefault(),e(".cf7style-no-forms-added").hide();var n=e(this).attr("data-attr-id"),i=e(this).attr("data-attr-title");e(this).prop("disabled",!0),e(this).parents("tr").find("input").prop("checked",!0);var r=e("<p />");e(".preview-form-tag").prepend(r),e.ajax({url:ajaxurl,method:"POST",data:{action:"cf7_style_generate_preview_dashboard",form_id:n,form_title:i},beforeSend:function(){r.text("Loading..."),e(".multiple-form-generated-preview").hide()},success:function(a){a&&(r.remove(),e(".preview-form-tag").append(a),e(".multiple-form-generated-preview").eq(e(".multiple-form-generated-preview").length-1).show(),c(),d(),t(e(".preview-form-container form:visible")))}})});var m,y,F,b,g,w,x,k,_,C,D,A,z,O=e(".generate-preview"),E=e(".post-type-cf7_style"),S=e("#select_all"),T=e('select[name="cf7_style_font_selector"]'),j=e(".cf7-style-slider-wrap"),q=e(".preview-form-container"),N={change:function(t,a){var n=e(this);n.parents(".wp-picker-container").parent().find(".transparent-box").prop("checked",!1),setTimeout(function(){p(n.parents(".panel").find('[name^="cf7stylecustom"]'))},0),"hover"==e('input[name="element-type"]:checked').val()?c("hover"):c()}};if(e(".cf7-style-color-field").wpColorPicker(N),O.length>0&&(m=O,e(window).scroll(function(){if(e(window).width()>1600){m.find(".panel-header").offset();var t=e("#cf7_style_meta_box_style_customizer").offset(),a=e(window).scrollTop()-t.top;a>0&&m.find(".panel-header").css("top",a),a<=0&&m.find(".panel-header").css("top",0)}e(window).scrollTop()>700?e(".fixed-save-style").show():e(".fixed-save-style").hide()}).trigger("scroll")),E.length>0){e("#cf7_style_manual_style").length>0&&CodeMirror.fromTextArea(document.getElementById("cf7_style_manual_style"),{lineNumbers:!0,theme:"default",mode:"text/css"}),u(),l(),d();q=e(".preview-form-container").not(".hidden");e(".post-new-php").length<1&&t(q),S.on("click",function(){e(".cf7style_body_select_all input").prop("checked",!!e(this).is(":checked"))}),s(T.val()),T.on("change",function(){s(e(this).val())}),function(){c(),e("#form-tag a.button").on("click",function(t){t.preventDefault();var a=e(this),n=e("."+a.attr("data-property")+"-panel"),i=0;0==e(".modified-style-here").length?(a.hasClass("button-primary")||(e(".panel").stop(!0,!0).animate({opacity:0},300,function(){0===i&&(i++,e(".panel").addClass("hidden"),e(".panel").html(""),n.css("opacity","0"),n.removeClass("hidden"),e.ajax({url:ajaxurl,method:"POST",data:{action:"cf7_style_load_property",property:a.attr("data-property")},beforeSend:function(){a.parent().find("a").prop("disabled","true"),e(".panel-options .loading").removeClass("hidden")},success:function(t){a.parent().find("a").prop("disabled","false"),i=0,n.html(t),e(".panel-options .loading").addClass("hidden");var r=e('input[name="cf7styleallvalues"]').val(),o=e.parseJSON(r.replace(/'/g,'"'));n.find('[name^="cf7stylecustom"]').each(function(){e(this).attr("id")in o&&""!=o[e(this).attr("id")]&&e(this).val(o[e(this).attr("id")])}),n.find(".cf7-style-color-field").wpColorPicker(N),l(),u(),n.stop(!0,!0).animate({opacity:1},300),f(),v()}}))}),e(".element-selector input:eq(0)").prop("checked",!0)),e("#form-tag a.button").removeClass("button-primary"),a.addClass("button-primary"),e('input[name="cf7styleactivepane"]').val(a.attr("data-property"))):e(".panel-options .decision").removeClass("hidden")}),e(".panel-options .cancel-btn").on("click",function(t){t.preventDefault(),e(".panel-options .decision").addClass("hidden")}),e(".element-selector input").on("change",function(){e(".element-selector input").prop("checked",!1),e(this).prop("checked",!0),"hover"==e(this).val()?(e(".panel:visible li").addClass("hidden"),e(".panel:visible li.hover-element").removeClass("hidden"),c("hover")):(e(".panel:visible li.hover-element").addClass("hidden"),e(".panel:visible li").not(".hover-element").removeClass("hidden"),c())}),e("#form-preview").on("change",function(){e(".preview-form-container").addClass("hidden"),e(".preview-form-container").eq(e(this).val()).removeClass("hidden"),t(e(".preview-form-container").eq(e(this).val()))});var a=0;e(document).on("change",'[name^="cf7stylecustom"]',function(){0==a&&(a++,e(this).parents(".panel").addClass("modified-style-here")),p(e(this).parents(".panel").find('[name^="cf7stylecustom"]')),"hover"==e('input[name="element-type"]:checked').val()?c("hover"):c()}),e(document).on("keyup",'[name^="cf7stylecustom"]',function(){p(e(this).parents(".panel").find('[name^="cf7stylecustom"]')),"hover"==e('input[name="element-type"]:checked').val()?c("hover"):c()})}(),q.find('input[type="hidden"]').remove(),q.find('input[type="submit"]').on("click",function(e){e.preventDefault()})}j.length>0&&(b=202,g=500,w=!0,x=(F=y=j).find(".active").index()+1,k=F.find("li"),_=F.find("ul"),C=F.find(".narrow"),D=F.find(".narrow.left"),A=F.find(".narrow.right"),z=F.find("li").length,A.addClass("visible"),_.css("width",z*b),0==w&&F.mouseenter(function(){F.find(".visible").stop().show()}).mouseleave(function(){F.find(".visible").stop().hide()}),C.on("click",function(t){t.stopPropagation(),t.preventDefault();var a=e(this).attr("data-direction");"left"==a&&1!==x&&(_.stop(!0,!0).animate({marginLeft:"+="+b+"px"},g),x--),"right"==a&&x!==z&&(_.stop(!0,!0).animate({marginLeft:-b*x+"px"},g),x++),1==x&&(D.hide().removeClass("visible"),A.show().addClass("visible")),x==z&&A.hide().removeClass("visible"),x<z&&A.show().addClass("visible"),x>1&&D.show().addClass("visible"),k.removeClass("active").eq(x-1).addClass("active")}),_.css({"margin-left":"-"+(x-1)*b+"px"}),y.find("li").on("click",function(){e(this).hasClass("current-saved")||(y.find("li").removeClass("current-saved"),e(this).addClass("current-saved"),y.find(".overlay em").html("Not Active"),e(this).find(".overlay em").html("Active"),e(".cf7style_template").removeAttr("checked"),e(this).find(".cf7style_template").attr("checked","checked"))})),e(".close-cf7-panel").on("click",function(t){t.preventDefault(),e.ajax({url:ajaxurl,method:"POST",data:{action:"cf7_style_remove_welcome_box"},success:function(t){e(".welcome-container").fadeOut("slow")}})}),f(),v()});