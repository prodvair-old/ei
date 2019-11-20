$(document).ready(function () {
  $('#login-form').on('beforeSubmit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: '/login',
      data: $(this).serializeArray()
    }).done(function (data) {
      if (data.result) {
        location.reload();
        $('.login-form-error').html('');
        toastr.success("Авторизация прошла успешно");
      } else {
        toastr.warning("Не удалось авторизоваться");
        $('.login-form-error').html(data.error);
      }
    }).fail(function () {
      toastr.error("Ошибка при авторизации");
    })
    return false;
  });

  $('#signup-form').on('beforeSubmit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: '/signup',
      data: $(this).serializeArray()
    }).done(function (data) {
      if (data.result) {
        toastr.success("Вы успешно зарегистрировались");
        location.reload();
        $('.signup-form-error').html('');
      } else {
        toastr.warning("Не удалось зарегистрироваться");
        $('.signup-form-error').html(data.error);
      }
    }).fail(function () {
      toastr.error("Ошибка при регистрации");
    })
    return false;
  });

  $('#password-reset-form').on('beforeSubmit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: $(this).serializeArray()
    }).done(function (data) {
      if (data.result) {
        toastr.success("Сообщение для восстановления пароля отправлено");
        location.reload();
        $('.password-reset-form-error').html('');
      } else {
        toastr.warning("Не удалось отправить сообщение");
        $('.password-reset-form-error').html(data.error);
      }
    }).fail(function () {
      toastr.error("Ошибка при отправке сообщения");
    })
    return false;
  });

  $('#avatar-upload').on('change', function () {
    var formData = new FormData(document.getElementById('setting-image'));

    // formData.append('_csrf',$('input[name="_csrf"').prop('files')[0]);
    formData.append('photo', $('#avatar-upload').prop('files')[0]);

    $.ajax({
      type: 'POST',
      contentType: false,
      processData: false,
      url: $('#setting-image').attr('action'),
      data: formData
    }).done(function (data) {
      if (data) {
        $('.setting-image-tag').attr('src', data.src);
        $('.setting-image-info').html('Успешно загружено');
        toastr.success("Фотография успешно загружена");
      } else {
        $('.setting-image-info').html(data.error);
        toastr.warning("Не удалось загрузить фото");
      }
    }).fail(function () {
      toastr.error("Ошибка при загрузки фото");
    })
  })

  $('.form-confirm').hide();

  $('#lot-service-form').on('beforeSubmit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data: $(this).serializeArray()
    }).done(function (data) {
      if (data) {
        $('.form-service .form-header').hide();
        $('.form-service .form-body').hide();
        $('.form-service .form-confirm').show();
        toastr.success("Ваша заявку отправлена");
        setTimeout(location.reload(), 1000);
      } else {
        toastr.error("Не удалось отправить");
      }
    }).fail(function () {
      toastr.error("Ошибка при отправке");
    })
    return false;
  });

  if (typeof (lotType) != "undefined" && lotType !== null) {
    if (lotType == 'arrest') {
      $('.bankrupt-type').hide();
    } else {
      $('.bankrupt-type').show();
    }
  }

  $('.wish-js').on('click', function (e) {
    e.preventDefault();
    var lotId = $(this).data('id'),
      type = $(this).data('type');
    star = $(this).children("img");
    item = $(this).parents(".col");
    $.ajax({
      url: '/wish-list-edit',
      type: 'POST',
      data: {
        lotId,
        type
      },
      success: function (data) {
        if (data['add']) {
          star.attr('src', 'img/star.svg');
          toastr.success("Лот добавлен в избранное");
        } else if (data['del']) {
          star.attr('src', 'img/star-o.svg');
          toastr.success("Лот удалён из избранных");
          item.fadeOut()
        }
      }
    }).fail(function () {
      toastr.error("Произошла ошибка");
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
    } else if ($(this).html() == 'Все документы') {
      $(this).html('Скрыть документы');
    } else if ($(this).html() == 'Скрыть документы') {
      $(this).html('Все документы');
    } else {
      $(this).html('Подробнее');
    }
    var id = $(this).attr('href');
    $(id + ' .long-text').toggleClass('hideText');
  });

  var bankruptWish = $('#bankrupt-wish')
  var arrestWish = $('#arrest-wish')






  if (!!+bankruptWish.data('count')) {
    arrestWish.hide();
    $('#bankrupt-wish-btn').addClass('active');

  } else {
    bankruptWish.hide();
    $('#arrest-wish-btn').addClass('active');
  }


  $('.wish-tabs').on('click', function (e) {
    e.preventDefault();
    var id = $(this).attr('href');

    $('.wish-tabs').removeClass('active');
    $(this).addClass('active');

    $('.wish-lot-list').hide();
    $(id).show();
  })


  var lotServicePrice = 0;

  $('.service-check-inpurt').on('change', function () {
    var price = Number($(this).data('price'));
    if ($(this).is(':checked')) {
      lotServicePrice = lotServicePrice + price;
    } else {
      lotServicePrice = lotServicePrice - price;
      if (lotServicePrice < 0) {
        lotServicePrice = 0;
      }
    }
    $('.service-lot-itog').html(lotServicePrice);
    $('.service-lot-itog-input').val(lotServicePrice);
  });

  $('.load-list-click').on('click', function () {
    $('.load-list').html('<div class="spinner-wrapper"><div class="spinner"></div>Ищем лоты...</div>');
  });



  // Уведомления Start-> 

  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };

  // Уведомления <-End
})