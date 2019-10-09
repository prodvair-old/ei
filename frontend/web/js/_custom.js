$(document).ready(function() {
    $('.form-ajax-js').on('beforeSubmit', function(e) {
        e.preventDefault();
        $.ajax({
            type : $(this).attr('method'),
            url : '/login',
            data : $(this).serializeArray()
        }).done(function(data) {
                if (data.error == null) {
                    // location.reload();
                    console.log(data)
                } else {
                    console.log(data.error)
                }
        }).fail(function() {
            console.log('fail');
        })
        return false;
    })
})