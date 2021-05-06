console.log('NewDeck.js');

$(document).ready(function() {
    new NewDeck();
});

(function(window, $) {
    window.NewDeck = function () {
        $('#new__deck_form').on(
            'submit',
            this.handleNewDeck.bind(this)
        );
    };
    $.extend(window.NewDeck.prototype, {
        handleNewDeck: function (e) {
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
                        //console.log(data);
                        $('.name_has_error').empty();
                        $('.error_name').removeClass('has_error');
                        $('.description_has_error').empty();
                        $('.error_description').removeClass('has_error');

                        //console.log('errors!');
                        if (typeof(data.errors.name) != "undefined" && data.errors.name.length > 0) {
                            $('.name_has_error').html(data.errors.name[0]);
                            $('.error_name').addClass('has_error');
                        }
                        if (typeof(data.errors.description) != "undefined" && data.errors.description.length > 0) {
                            $('.description_has_error').html(data.errors.description[0]);
                            $('.error_description').addClass('has_error');
                        }

                    }else {
                        //console.log('else = register');
                        window.location.href = "./index?p=cards&info=newDeck";
                    }
                }
            });
        }
    });

})(window, jQuery);
