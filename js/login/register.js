console.log('register.js');

$(document).ready(function() {
    new Registration();
});

(function(window, $) {
    window.Registration = function () {
        $('#register__form').on(
            'submit',
            this.handleRegistration.bind(this)
        );
    };
    $.extend(window.Registration.prototype, {
        handleRegistration: function (e) {
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
