let bFrame,bConf=badgeConfObj||{i18n:{title:"Choose an image",b_txt:"Use image"},dummy:"",multi:!1};jQuery((function(e){function t(t){t||(t=e("#term-se_bdg_badge_type").val()),console.log("running for - ",t),"bdg_type_img"===t?(e("#term-se_bdg_badge_text").closest(".form-field").hide(),e("#term-se_bdg_badge_bg_color").closest(".form-field").hide(),e("#term-se_bdg_badge_txt_color").closest(".form-field").hide(),e("#term-se_bdg_badge_type_img").closest(".form-field").slideDown(),console.log("how come...")):(e("#term-se_bdg_badge_text").closest(".form-field").show(),e("#term-se_bdg_badge_bg_color").closest(".form-field").show(),e("#term-se_bdg_badge_txt_color").closest(".form-field").show(),e("#term-se_bdg_badge_type_img").closest(".form-field").hide())}e(document).on("click",".se_img_upload_btn",(function(t){if(t.preventDefault(),bFrame)return void bFrame.open();bFrame=wp.media.frames.downloadable_file=wp.media({title:bConf.i18n.title,button:{text:bConf.i18n.b_txt},multiple:bConf.multi});let i=e(this).closest("div.shopengine_term_img_grp");bFrame.on("select",(function(){let e=bFrame.state().get("selection").first().toJSON();i.find("input.shopengine_term_img").val(e.id),i.find(".se_remove_img_btn").show(),"undefined"!=typeof e.sizes.thumbnail?i.find(".se_term_thumbnail").find("img").attr("src",e.sizes.thumbnail.url):i.find(".se_term_thumbnail").find("img").attr("src",e.url)})),bFrame.open()})),e(document).on("click",".se_remove_img_btn",(function(t){t.preventDefault();let i=e(this).closest("div.shopengine_term_img_grp");return i.find("input.shopengine_term_img").val(""),i.find(".se_remove_img_btn").hide(),i.find(".se_term_thumbnail").find("img").attr("src",bConf.dummy),!1})),t(),e(document).on("change","#term-se_bdg_badge_type",(function(e){e.preventDefault(),t(this.value)}))}));