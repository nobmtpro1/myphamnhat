!function(t){var e=t(".shopengine-sticky-fly-cart"),i=e.find(".shopengine-sticky-fly-cart--fixed-cart");$fixedCartBackdrop=e.find(".shopengine-sticky-fly-cart--backdrop"),e.on("click",(function(n){let o=t(n.target).closest(i),s=t(n.target).closest($fixedCartBackdrop);(o.length||s.length)&&(e.toggleClass("is--active"),t(this).closest("html").toggleClass("is--offcanvas-open"),a("stickyFlyCartClicked",""))})),t(window).on("resize load stickyFlyCartClicked",(function(){let i=e.find(".shopengine-sticky-fly-cart--container"),a=i.css("padding-right"),n=i.find("ul.woocommerce-mini-cart"),o=i.find(".mini-cart-header"),s=i.find(".woocommerce-mini-cart__total"),c=i.find(".woocommerce-mini-cart__buttons"),r=i.innerWidth()-i.width()+s.innerHeight()+c.innerHeight()+o.innerHeight(),d=Math.round(r+n.innerHeight());t(window).height()<=d?n.css({"overflow-y":"auto","margin-right":`-${a}`,"max-height":`calc( 100vh - ${r}px)`}):n.removeAttr("style")})),"yes"===e.data("fly")&&t(".add_to_cart_button, .single_add_to_cart_button").on("click",(function(o){let s=t(this),c=s.hasClass("single_add_to_cart_button")?t(".woocommerce-product-gallery__wrapper").find("img.wp-post-image"):s.closest(".has-post-thumbnail").find("img").first();if(s.hasClass("ajax_add_to_cart")||s.hasClass("single_add_to_cart_button")){if(s.hasClass("single_add_to_cart_button")&&"yes"==e.data("single-ajax")){s.hasClass("single_add_to_cart_button")&&o.preventDefault();var r=t(this).closest("form"),d=t(".grouped_form"),l=t(".variations_form");product_id=r.find("input[name=product_id]");var f=r.serialize();if(l.length){let e=f.replace(`product_id=${t(product_id).val()}`,"variation_id");n(e)}else if(d.length)n(f);else{let e=`${f}&product_id=${t(s).val()}`;n(e)}}if(c&&!s.hasClass("disabled"))c.clone().offset({top:c.offset().top,left:c.offset().left}).css({opacity:"0.8",position:"absolute",height:"150px",width:"150px","z-index":"100"}).appendTo(t("body")).animate({top:i.offset().top,left:i.offset().left,width:"50px",height:"50px"},1e3,(function(){i.addClass("shopengine-shake")})).animate({width:0,height:0},(function(){t(this).detach(),i.removeClass("shopengine-shake"),a("flyingDone","")}))}})),t(window).on("added_to_cart removed_from_cart wc_fragments_refreshed",(function(i,a,n){t.get(e.data("cart-total-api"),(function(t,i){e.find(".shopengine-fixed-cart--count").html(t.items_count),e.find(".shopengine-cart-count").html(t.items_html)}))}));var a=function(t,e){let i=new CustomEvent(t,{detail:e});window.dispatchEvent(i)},n=function(e){t.ajax({type:"POST",url:wc_add_to_cart_params.wc_ajax_url.toString().replace("%%endpoint%%","add_to_cart"),data:e,success:function(e){t(document.body).trigger("wc_fragment_refresh"),t(document.body).trigger("added_to_cart")}})}}(jQuery);