console.log('showHomeDecks.js');

$(document).ready(function() {
    new Search();
});

(function(window, $) {
    let search;

    window.Search = function () {
        search = this;
        $('#search__form').on(
            'submit',//typing?
            this.handleSearch.bind(this)
        );
        this.DeckShowSingleListeners();
        $('#search__form input[type="checkbox"]').on('change', this.handleSearchCount.bind(this));
        $('#search__form input[type="search"]').on('keyup', this.handleSearchCount.bind(this));
        $('#search__form select').on('change', this.handleSearchCount.bind(this));
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
                    $('#search__result').html(data);
                    search.DeckShowSingleListeners();
                    search.addCloseListeners();
                }
            });
        },

        handleSearchCount: function(e) {
            e.preventDefault();
            let $form = $('#search__form');
            $.ajax({
                url: $form.attr('action') + '_count',
                method: 'POST',
                data: $form.serialize(),
                success: function(data) {
                    $('#search__count').html(data);
                    if(data < 15 ) {
                        $form.submit();
                    }
                }
            });
        },

        DeckShowSingleListeners: function () {
            $('button[name="show_deck"]').on('click', this.handleDeckShowSingle.bind(this));
        },

        handleDeckShowSingle: function (e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=home&method=show_deck&showDeck=' + $button.attr('value'),
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#popup_container').html(data);
                    $('#popup_container').addClass('show');
                    search.addCloseListeners();
                }
            });
        },

        addCloseListeners: function () {
            $('button[name="close"]').on('click', this.handleClose.bind(this));
        },

        handleClose: function(e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            $.ajax({
                url: 'index.php?p=home&' ,
                method: 'GET',
                success: function(data) {
                    $('#popup_container').removeClass('show')
                }
            });
        },

    });

})(window, jQuery);
