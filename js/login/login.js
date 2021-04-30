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

                    console.log(data);

                  /*  data = JSON.parse(data)
                    if (!data.status){
                        console.log('error')
                    }else {
                        console.log(data)
                    }
*/
                    var newDoc = document.open("text/html", "replace");
                    newDoc.write(data);
                    newDoc.close();
                   // window.location.href = "./";

                }
            });
        }
    });

})(window, jQuery);
