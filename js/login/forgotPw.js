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
                    var newDoc = document.open("text/html", "replace");
                    newDoc.write(data);
                    newDoc.close();
                }
            });
        }
    });

})(window, jQuery);
