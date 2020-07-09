document.addEventListener("DOMContentLoaded", function () {
    let offset = 0,
        i = 2000,
        url,
        getSymbol = '?';

    /**
     * @var offsetStep taken from php
     * @var modelSearchName taken from php
     */

    let onScroll = function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - i) {
            offset = offset + offsetStep;
            i = 0;

            if (location.href.indexOf('?', location.search) !== -1) {
                getSymbol = '&'
            }

            url = location.href + getSymbol + modelSearchName + '[offset]=' + offset;

            $.ajax({
                type: "GET",
                url: url,
                data: $(this).serialize(),
                success: function (data) {
                    if (data == 0) {
                        return document.removeEventListener('scroll', onScroll, false);
                    }
                    $('#load_list').append(data);
                    console.log('load success');
                    i = 2000;
                },
                error: function (result) {
                    console.log('load error');
                    console.log(result);
                }
            });
        }
    };

    document.addEventListener('scroll', onScroll, false);

});