console.log('Search.js');

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
                }
            });
        },

        handleSearchCount: function(e) {
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
            let $button = $(e.currentTarget);
            console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=decks&method=show_deck&showDeck=' + $button.attr('value'),
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#popup_container').html(data);
                    $('#popup_container').addClass('show');
                    search.DeckDeleteCardListeners();
                    search.addCloseListeners();
                }
            });
        },

        DeckDeleteCardListeners: function () {
            $('button[name="delete"]').on('click', this.handleDeckDeleteCard.bind(this));
        },

        handleDeckDeleteCard: function (e) {
            let $button = $(e.currentTarget);
            console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=decks&method=delete_card&dataCard=' + $button.attr('data-card') + '&data-deck=' + $button.attr('data-deck'),
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    //delete!
                    $button.html('deleted!');
                    search.addCloseListeners();
                }
            });
        },

        addCloseListeners: function () {
            $('button[name="close"]').on('click', this.handleClose.bind(this));
        },

        handleClose: function(e) {
            let $button = $(e.currentTarget);
            $.ajax({
                url: 'index.php?p=decks&' ,
                method: 'GET',
                success: function(data) {
                    $('#popup_container').removeClass('show')
                }
            });
        },

    });

})(window, jQuery);
