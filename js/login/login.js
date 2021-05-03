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
            console.log($form.attr('action'));
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {

                    console.log('success');

                    data = JSON.parse(data);
                    if (data.status == 'error'){
                        console.log('error');
                        if ( data.errors.password.length > 0){
                            $('.error_password').html(data.errors.password[0]);
                        }
                        if ( data.errors.nickname.length > 0){
                            $('.error_nickname').html(data.errors.nickname[0]);
                        }

                    }else {
                        console.log('else');
                       // console.log(data)
                     /*   var newDoc = document.open("text/html", "replace");
                        newDoc.write(data);
                        newDoc.close();*/
                        window.location.href = "./index?p=decks";

                    }

                }
            });
        }
    });

})(window, jQuery);
