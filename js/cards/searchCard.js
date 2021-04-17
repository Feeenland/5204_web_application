console.log('register.js');

$(document).ready(function() {
    new SearchCard();
});

(function(window, $) {

    window.SearchCard = function () {
        $('#searchcard__form').on(
            'submit',//typing?
            this.handleSearchCard.bind(this)
        );
    };
    $.extend(window.SearchCard.prototype, {
        handleSearchCard: function (e) {
            e.preventDefault();

            $.ajax({
                url: 'index?p=card&method=searchcount',
                method: 'POST',
                data: $('search__input').value,
                success: function(data) {
                    // data = 120
                    $('search__counter').html(data);
                }
            })
        }
    });

})(window, jQuery);
