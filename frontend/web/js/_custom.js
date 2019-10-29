$(document).ready(function() {
    $('#login-form').on('beforeSubmit', function(e) {
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : '/login',
            data : $(this).serializeArray()
        }).done(function(data) {
                if (data.result) {
                    console.log(data)
                        location.reload();
                        $('.login-form-error').html('');
                } else {
                        $('.login-form-error').html(data.error);
                }
        }).fail(function() {
            console.log('fail');
        })
        return false;
    });

    $('#signup-form').on('beforeSubmit', function(e) {
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : '/signup',
            data : $(this).serializeArray()
        }).done(function(data) {
                if (data.result) {
                    console.log(data)
                        location.reload();
                        $('.signup-form-error').html('');
                } else {
                        $('.signup-form-error').html(data.error);
                }
        }).fail(function() {
            console.log('fail');
        })
        return false;
    });

    if (lotType == 'arrest') {
        $('.bankrupt-type').hide();
    } else {
        $('.bankrupt-type').show();
    }
})