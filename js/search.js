console.log('Search.js');

$(document).ready(function() {
    new Search();
});

(function(window, $) {

    window.Search = function () {
        $('#search__form').on(
            'submit',//typing?
            this.handleSearch.bind(this)
        );

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
        }
    });

})(window, jQuery);
