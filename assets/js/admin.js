!function(i){"use strict";i(function(){i(".vip-social-login-toggle input").change(function(){var e,n=i(this).data("nonce"),o=i(this).data("provider"),t=i(this).is(":checked");e=wp.template("updating"),i(".vip-social-login-admin").append(e()),i(".vip-social-login-update-indicator").fadeIn(),i.post(ajaxurl,{action:"vip-social-login-update-providers",nonce:n,provider:o,checked:t},function(e){e.sucess&&console.log("success"),i(".vip-social-login-update-indicator span").remove(),i(".vip-social-login-update-indicator em").text(TK.i18n.settings_updated),i(".vip-social-login-update-indicator").delay(2e3).fadeOut(function(){i(this).remove()})})})})}(jQuery);