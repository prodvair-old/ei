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

    if(typeof(lotType) != "undefined" && lotType !== null) {
        if (lotType == 'arrest') {
            $('.bankrupt-type').hide();
        } else {
            $('.bankrupt-type').show();
        }
    }

    $('.wish-js').on('click', function (e) {
        e.preventDefault();
        var lotId   = $(this).data('id'),
            type    = $(this).data('type');

        $.ajax({
            url: '/wish-list-edit',
            type: 'POST',
            data: {
                lotId,
                type
            },
            success: function (data) {
                if (data['add']) {
                    $('.wish-js img').attr('src', 'img/star.svg');
                } else if (data['del']){
                    $('.wish-js img').attr('src', 'img/star-o.svg');
                }
            }
        })

    });

    $('.open-text-js').hide();

    if ($('#torg .long-text').height() > 200) {
        $('#torg .long-text').addClass('hideText');
        $('#torg .open-text-js').show();
    }
    if ($('#desc .long-text').height() > 200) {
        $('#desc .long-text').addClass('hideText');
        $('#desc .open-text-js').show();
    }
    if ($('#roles .long-text').height() > 200) {
        $('#roles .long-text').addClass('hideText');
        $('#roles .open-text-js').show();
    }
    if ($('#docs .long-text').height() > 200) {
        $('#docs .long-text').addClass('hideText');
        $('#docs .open-text-js').show();
    }
    if ($('#docs-lot .long-text').height() > 200) {
        $('#docs-lot .long-text').addClass('hideText');
        $('#docs-lot .open-text-js').show();
    }
    if ($('#docs-torg .long-text').height() > 200) {
        $('#docs-torg .long-text').addClass('hideText');
        $('#docs-torg .open-text-js').show();
    }

    $('.open-text-js').on('click', function (e) {
        e.preventDefault();
        if ($(this).html() == 'Подробнее') {
            $(this).html('Скрыть');
        } else {
            $(this).html('Подробнее');
        }
        var id = $(this).attr('href');
        $(id+' .long-text').toggleClass('hideText');
    });

    $('#bankrupt-wish').hide();

    $('.wish-tabs').on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr('href');

        $('.wish-tabs').removeClass('active');
        $(this).addClass('active');

        $('.wish-lot-list').hide();
        $(id).show();
    })

})