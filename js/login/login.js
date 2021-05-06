console.log('login.js');

$(document).ready(function() {
    new Login();
});

(function(window, $) {
    window.Login = function () {
        $('#login__form').on(
            'submit',
            this.handleLogin.bind(this)
        );
    };
    $.extend(window.Login.prototype, {
        handleLogin: function (e) {
            e.preventDefault();

            let $form = $(e.currentTarget);
            //console.log($form.attr('action'));
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {

                    //console.log('success');
                    data = JSON.parse(data);
                    if (data.status == 'error'){
                        $('.error_password').empty();
                        $('.password_has_error').removeClass('has_error');
                        $('.error_nickname').empty();
                        $('.nickname_has_error').removeClass('has_error');
                        $('.infos').empty();

                        if (typeof(data.info) != "undefined" && data.info.length > 0) {
                            $('.infos').html(data.info);
                        }
                        if (typeof(data.generalerr) != "undefined" &&  data.generalerr.length > 0) {
                            $('.error_password').html(data.generalerr);
                            $('.password_has_error').addClass('has_error');
                            $('.nickname_has_error').addClass('has_error');
                        }
                        //console.log('errors!');
                        if (typeof(data.errors.password) != "undefined" && data.errors.password.length > 0) {
                            $('.error_password').html(data.errors.password[0]);
                            $('.password_has_error').addClass('has_error');
                        }
                        if (typeof(data.errors.nickname) != "undefined" && data.errors.nickname.length > 0) {
                            $('.error_nickname').html(data.errors.nickname[0]);
                            $('.nickname_has_error').addClass('has_error');
                        }

                    }else {
                        //console.log('else = login');
                        window.location.href = "./index?p=decks&info=Welcome";
                    }
                }
            });
        }
    });

})(window, jQuery);
