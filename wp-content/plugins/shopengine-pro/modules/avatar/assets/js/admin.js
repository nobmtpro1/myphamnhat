jQuery((function(t){let e=t(".user-profile-picture .avatar"),r=t(".user-profile-picture td .shopengine-avatar-close"),a=e.attr("src");e.closest("form").attr("enctype","multipart/form-data"),r.on("click",(function(){r.hide(),e.attr("src",a),e.attr("srcset",a),t(".shopengine-avatar-input").val("")})),t(".shopengine-avatar-input").on("change",(function(){let a=t(this)[0].files[0],c=URL.createObjectURL(a);e.attr("src",c),e.attr("srcset",c),r.css("display","block")}))}));