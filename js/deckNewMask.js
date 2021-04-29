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
            console.log($form.attr('action'));
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {
                    console.log(data);
                    let newDoc = document.open("text/html", "replace");
                    newDoc.write(data);
                    newDoc.close();
                }
            });
        }
    });

})(window, jQuery);
