console.log('searchCards.js');

$(document).ready(function() {
    new Search();
});

(function(window, $) {
    let search;

    window.Search = function () {
        search = this;
        this.addCardListeners();
        $('#search__cards_form').on(
            'submit',//typing?
            this.handleSearch.bind(this)
        );

        $('#card__color input[type="checkbox"]').on('change', this.handleSearchCount.bind(this));
        //$('#card__name').on('keyup', this.handleSearchCount.bind(this));
        $('#search__cards_form input[type="search"]').on('keyup', this.handleSearchCount.bind(this));
        $('#search__cards_form select').on('change', this.handleSearchCount.bind(this));
        //$('#card__set select').on('change', this.handleSearchCount.bind(this));
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
                    //console.log(data);
                    $('#search__card_result').html(data);
                    search.addCardListeners();
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
        },

        addCardListeners: function () {
            $('button[name="cards__add_to_deck"]').on('click', this.handleAddCardToDeck.bind(this));
        },

        handleAddCardToDeck: function (e) {
            let $button = $(e.currentTarget);
            console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=allCards&method=add_card&addCard=' + $button.attr('value'),
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#popup_container').html(data);
                    $('#popup_container').addClass('show');
                    search.addDeckListeners();
                }
            });
        },

        addDeckListeners: function () {
            $('button[name="deck__select_deck"]').on('click', this.handleSelectDeck.bind(this));
        },

        handleSelectDeck: function(e) {
            let $button = $(e.currentTarget);
            console.log($button.attr('data-card'));
            console.log($button.attr('data-deck'));
            $.ajax({
                url: 'index.php?p=allCards&method=select_deck&data-card=' + $button.attr('data-card') + '&data-deck=' + $button.attr('data-deck'),
                method: 'GET',
                success: function(data) {
                    console.log(data);
                    $('#popup_container').html(data);
                    const createP = document.createElement("p");
                    createP.innerText = data;
                    $('#popup_container').appendChild(createP);
                    $('#popup_container > p').addClass('title_colorless');
                    //$('#popup_container').removeClass('show')
                }
            });

        }
    });

})(window, jQuery);
