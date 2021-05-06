console.log('searchCards.js');

$(document).ready(function() {
    new Search();
});

(function(window, $) {
    let search;
    let timer;
    window.Search = function () {
        search = this;
        this.addCardListeners();
        this.CardShowSingleListeners();
        this.UserDeleteCardListeners();
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
                    search.CardShowSingleListeners();
                }
            });
        },

        handleSearchCount: function(e) {
            e.preventDefault();
            //console.log(e.currentTarget['checked']);
            if (e.currentTarget['checked'] === true){
                //console.log('color-button!');
                $(e.currentTarget).parent().toggleClass('checked');
            }else if (e.currentTarget['checked'] === false){
                $(e.currentTarget).parent().toggleClass('checked');
            }
            //console.log(e);
            let $form = $('#search__cards_form');

            if (timer > 0 ){
                clearTimeout(timer);
                //console.log('timer clear');
            }

            timer = setTimeout( function () {
                //console.log('2sek past! = ajax');

                $.ajax({
                    url: $form.attr('action') + '_count',
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(data) {
                        //console.log('success !');
                        $('#search__card_count').html(data);
                        if(data < 25) {
                            $form.submit();
                        }
                    }
                });
            }, 2000)
        },

        CardShowSingleListeners: function () {
            $('button[name="cards__show_detail"]').on('click', this.handleCardShowSingle.bind(this));
        },

        handleCardShowSingle: function (e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            //console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=allCards&method=show_card&showCard=' + $button.attr('value'),
                method: 'GET',
                success: function(data) {
                    //console.log(data);
                    $('#popup_container').html(data);
                    $('#popup_container').addClass('show');
                    search.addCardListeners();
                    search.addCloseListeners();
                    search.UserDeleteCardListeners();
                }
            });
        },

        addCardListeners: function () {
            $('button[name="cards__add_to_deck"]').on('click', this.handleAddCardToDeck.bind(this));
        },

        handleAddCardToDeck: function (e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            //console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=allCards&method=add_card&addCard=' + $button.attr('value'),
                method: 'GET',
                success: function(data) {
                    $('#popup_container').html(data);
                    $('#popup_container').addClass('show');
                    search.addDeckListeners();
                    search.addCloseListeners();
                }
            });
        },

        addDeckListeners: function () {
            $('button[name="deck__select_deck"]').on('click', this.handleSelectDeck.bind(this));
        },

        handleSelectDeck: function(e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            //console.log($button.attr('data-card'));
            //console.log($button.attr('data-deck'));
            $.ajax({
                url: 'index.php?p=allCards&method=select_deck&data-card=' + $button.attr('data-card') + '&data-deck=' + $button.attr('data-deck'),
                method: 'GET',
                success: function(data) {
                    //console.log(data);
                    $('#popup_container').html(data);
                    search.addCloseListeners();
                    //$('#popup_container').removeClass('show')
                }
            });
        },

        UserDeleteCardListeners: function () {
            $('button[name="delete_user_card"]').on('click', this.handleUserDeleteCard.bind(this));
        },

        handleUserDeleteCard: function (e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            //console.log($button.attr('value'));
            $.ajax({
                url: 'index.php?p=allCards&method=delete_user_card&cardId=' + $button.attr('value'),
                method: 'GET',
                success: function(data) {
                    //console.log(data);
                    //delete!
                    $button.html('Deleted! Notice: card was only deleted from user cards, not from any decks!' +
                        ' If the Delete was a mistake, you can add the card again as long as the page has not been reloaded');
                    search.addCloseListeners();
                }
            });
        },
        addCloseListeners: function () {
            $('button[name="finished"]').on('click', this.handleClose.bind(this));
        },

        handleClose: function(e) {
            e.preventDefault();
            let $button = $(e.currentTarget);
            $.ajax({
                url: 'index.php?p=allCards&' ,
                method: 'GET',
                success: function(data) {
                    $('#popup_container').removeClass('show')
                }
            });
        },
    });

})(window, jQuery);
