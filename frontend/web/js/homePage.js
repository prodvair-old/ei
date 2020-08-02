document.addEventListener("DOMContentLoaded", function () {

    request('/lot/unique?type=lotWithReports', 'lotWithReports');
    request('/lot/unique?type=lotWithPriceDown', 'lotWithPriceDown');
    request('/lot/unique?type=lotLowPrice', 'lotLowPrice');
    request('/lot/unique?type=lotWithCheapRealEstate', 'lotWithCheapRealEstate');
    request('/lot/unique?type=lotWithEndedTorg', 'lotWithEndedTorg');

    function request(url, id) {
        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {
                $('#' + id).html(data);
                console.log('load success');
            },
            error: function (result) {
                console.log('load error');
                $('#' + id).html('');
                console.log(result);
            }
        });
    }
});