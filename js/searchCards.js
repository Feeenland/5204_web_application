console.log('searchCards.js');

$(document).ready(function() {
    new Search();
});

(function(window, $) {

    window.Search = function () {
        $('#search__cards_form').on(
            'submit',//typing?
            this.handleSearch.bind(this)
        );

        $('#card__color input[type="checkbox"]').on('change', this.handleSearchCount.bind(this));
        $('#card__name input[type="search"]').on('keyup', this.handleSearchCount.bind(this));
        $('#card__creature input[type="search"]').on('keyup', this.handleSearchCount.bind(this));
        $('#card__legality select').on('change', this.handleSearchCount.bind(this));
        $('#card__set select').on('change', this.handleSearchCount.bind(this));
    };

    $.extend(window.Search.prototype, {
        handleSearch: function (e) {
            e.preventDefault();
            let $form = $(e.currentTarget);

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {
                    console.log(data);
                    $('#search__card_result').html(data);
                }
            });
        },

        handleSearchCount: function(e) {
            let $form = $('#search__cards_form');

            $.ajax({
                url: $form.attr('action') + '_count',
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {
                    console.log('success ?');
                    $('#search__card_count').html(data);
                    if(data < 15) {
                        $form.submit();
                    }
                }
            });
        }
    });

})(window, jQuery);
