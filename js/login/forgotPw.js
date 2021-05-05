console.log('register.js');

$(document).ready(function() {
    new ForgotPw();
});

(function(window, $) {
    window.ForgotPw = function () {
        $('#forgotpw__form').on(
            'submit',
            this.handleForgotPw.bind(this)
        );
    };
    $.extend(window.ForgotPw.prototype, {
        handleForgotPw: function (e) {
            e.preventDefault();

            let $form = $(e.currentTarget);
            console.log($form.attr('action'));
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {
                    console.log('success');
                    console.log(data);
                    data = JSON.parse(data);
                    if (data.status == 'error'){
                        console.log(data);
                        $('.error_name').empty();
                        $('.name_has_error').removeClass('has_error');
                        $('.error_favourite_card').empty();
                        $('.favourite_card_has_error').removeClass('has_error');
                        $('.error_nickname').empty();
                        $('.nickname_has_error').removeClass('has_error');
                        $('.error_password').empty();
                        $('.password_has_error').removeClass('has_error');
                        $('.infos').empty();


                        console.log('errors!');
                        if ( data.info.length > 0){
                            $('.infos').html(data.info);
                        }
                        if ( data.generalerr.length > 0){
                            $('.error_password').html(data.generalerr);
                            $('.password_has_error').addClass('has_error');
                            $('.nickname_has_error').addClass('has_error');
                            $('.favourite_card_has_error').addClass('has_error');
                            $('.name_has_error').addClass('has_error');
                        }
                        if (typeof(data.errors.name) != "undefined" && data.errors.name.length > 0) {
                            $('.error_name').html(data.errors.name[0]);
                            $('.name_has_error').addClass('has_error');
                        }
                        if (typeof(data.errors.favourite_card) != "undefined" && data.errors.favourite_card.length > 0) {
                            $('.error_favourite_card').html(data.errors.favourite_card[0]);
                            $('.favourite_card_has_error').addClass('has_error');
                        }
                        if (typeof(data.errors.nickname) != "undefined" && data.errors.nickname.length > 0) {
                            $('.error_nickname').html(data.errors.nickname[0]);
                            $('.nickname_has_error').addClass('has_error');
                        }
                        if (typeof(data.errors.password) != "undefined" && data.errors.password.length > 0) {
                            $('.error_password').html(data.errors.password[0]);
                            $('.password_has_error').addClass('has_error');
                        }


                    }else {
                        console.log('else = register');
                        window.location.href = "./index?p=login&info=forgotPw";
                    }


                    /* let newDoc = document.open("text/html", "replace");
                     newDoc.write(data);
                     newDoc.close();*/
                }
            });
        }
    });

})(window, jQuery);
