function uploadLotImage(lotId) {
  var formData = new FormData(document.getElementById('lot-' + lotId + '-zalog-upload-images'));

  
  $.ajax({
    type: 'POST',
    contentType: false,
    processData: false,
    url: $('lot-' + lotId + '-zalog-upload-images').attr('action'),
    data: formData
  }).done(function (data) {
    if (data.status) {

      var imagesTag = '';

      data.src.map(function (src) {
        imagesTag = imagesTag + `<img class="profile-pic d-block" src="` + src.min + `" alt="" />`;
      });


      $('.lot-' + lotId + '-upload-image-tag').html(imagesTag);
      $('.lot-' + lotId + '-zalog-image-info').html('Успешно загружено');

      toastr.success("Фотографии успешно загружены");
    } else {
      $('.lot-' + lotId + '-zalog-image-info').html('Ошибка загрузки');

      toastr.warning("Не удалось загрузить фотографии");
    }
  }).fail(function () {
    toastr.error("Ошибка при загрузки фотографии");
  })
}

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

  $('.remove-zalog-lot').on('click', function (e) {
    e.preventDefault();
    var lotId = $(this).data('lotid');

    $.ajax({
      type: 'GET',
      url: $(this).attr('href'),
      data: {
        lotId
      }
    }).done(function (data) {
      if (data) {
        $('#zalog-' + lotId).hide()
        toastr.success("Лот №" + lotId + " успешно удалён!");
      } else {
        toastr.warning("Не удалось удалить лот №" + lotId);
      }
    }).fail(function () {
      toastr.error("Ошибка при удалении лота №" + lotId);
    })
  })
  $('.status-zalog-lot').on('click', function (e) {
    e.preventDefault();
    var lotId = $(this).data('lotid'),
      element = $(this);


    $.ajax({
      type: 'GET',
      url: element.attr('href'),
      data: {
        lotId
      }
    }).done(function (data) {
        if (data['status'] == null) {
        element.html('Опубликовать');
        toastr.warning("Не удаётся опубликовать лот!");
      } else if (data['status']) {
        element.html('Снять с публикации');
        $('.lot-'+lotId+'-link').attr('href',data['url']);
        toastr.success("Лот №"+lotId+" успешно опубликован!");
      } else {
        element.html('Опубликовать');
        $('.lot-'+lotId+'-link').attr('href',data['url']);
        toastr.success("Лот №"+lotId+" успешно снят с публикации!");

      }
    }).fail(function () {
      toastr.error("Ошибка при публикации/снятия с публикации лота №" + lotId);
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
  var zalogWish = $('#zalog-wish')






  if (!!+bankruptWish.data('count')) {
    arrestWish.hide();
    zalogWish.hide();
    $('#bankrupt-wish-btn').addClass('active');

  } else if (!!+arrestWish.data('count')) {
    bankruptWish.hide();
    zalogWish.hide();
    $('#arrest-wish-btn').addClass('active');
  } else {
    bankruptWish.hide();
    arrestWish.hide();
    $('#zalog-wish-btn').addClass('active');
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

  $('.dropdown-btn').on("click", function (e) {
    $(this).next('ul').toggleClass('open')
  })



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

  $(".image-galery").each(function (index) {
    const images = $(this).children('img');
    const count = images.length;
    if (count <= 1) return;

    const control = $(this).children('.image-galery__control')
    const stepLength = 140;
    let activeEl = 0;
    let buttons;
    let position;

    function valide(value) {
      if (value < 0) {
        return count - 1;
      } else if (count - 1 < value) {
        return 0;
      } else {
        return value;
      }
    }

    function selectedImage(item) {
      if (item !== activeEl) {
        $(buttons[activeEl]).removeClass('active');
        $(buttons[item]).addClass('active');
        $(images[activeEl]).hide();
        $(images[item]).show();
        activeEl = item;
      }
    }

    images.each((i, el) => {
      const image = $(el);
      buttons = control.append(`<div class="image-galery__control__item"></div>`).children()
      if (i !== activeEl) {
        image.hide();
      } else {
        $(buttons[activeEl]).addClass('active')
      }
      $(buttons[i]).on('click', function (e) {
        e.preventDefault();
        selectedImage(i);
      });

      $(buttons[i]).on('mousemove', function (e) {
        selectedImage(i);
      });
    });
  });

  // Уведомления <-End
  
  // Загрузка фото для залогового Start->

  $('#lot-upload').on('change', function () {
    var input = $(this)
    if (!window.FileReader) return false // check for browser support
    
    $.each( input[0].files, function( key, value ) {
      var reader = new FileReader()
        reader.onload = function (e) {
            $('.lot-load-images').append(`<div class="col-2 load-image-lot">
                <img class="profile-pic d-block setting-image-tag" src="`+e.target.result+`" alt="avatar" />
                <a href="#"><i class="fa fa-trash"></i></a>
            </div>`)
        }
        reader.readAsDataURL(value);
    });
  });
  
  // Загрузка фото для залогового <-End

  $('.custom-file-input').on('change', function () {
    $('.custom-file-label').html($(this).prop("files")['name']);
  });

  $('.sidebar-box__label').on('click', function() {
    $(this).parent(".sidebar-box__collaps").toggleClass('collaps')
  })

  var app = new Vue({
    el: '#help-steps',
    data: {
      step: 1,
      steps: [
        {
          text: "<h4>Решили участвовать в торгах самостоятельно? Тогда вот вам 13 шагов для победы. Желаем удачи!</h4>",
         
          
          
        },
        {
          text: "Хорошо, что вы определились с лотом на ei.ru, у нас самая полная информация по торгам",
         
          
          
        },
        {
          text: "Оценить ликвидность лота, проверить, не находится ли имущество в залоге, соблюдает ли организатор требования законодательства",
         
          
          
        },
        {
          text: "Осмотреть имущество либо изучить документы, запросив их у арбитражного управляющего",
         
          
          
        },
        {
          text: "Зарегистрироваться на сайте площадки, на которой проходят торги. Для этого вы должны иметь электронную цифровую подпись, свежую выписку из ЕГРЮЛ, учредительные документы, решение совета директоров о подтверждении полномочий директора, паспорт руководителя",
         
          buttonECP: true,
          
        },
        {
          text: "Аккредитоваться на сайте площадки как участник торгов по банкротству. Регистрация, как правило, бесплатная и проходит в течение от 1-5 раб. дней",
         
          
          
        },
        {
          text: "Внести задаток, установленный организатором. От 5 до 20% от стоимости лота",
         
          
          
        },
        {
          text: "Выставить ценовое предложение. Вы можете его менять до окончания приёма заявок",
         
          
          
        },
        {
          text: "Получить протокол подведения торгов",
         
          
          
        },
        {
          text: "Перечислить оставшуюся сумму",
         
          
          
        },
        {
          text: "Заключить договор купли-продажи через торговлю площадку и отправить оригинал договора по почте. Если проиграли - в течение 5 дней должны вернуть задаток",
         
          
          
        },
        {
          text: "При необходимости обжаловать нарушения в ФАС, Росреестр или арбитражный суд",
         
          
          
        },
        {
          text: "<div class='h4'>Если вы расхотели участвовать в торгах самостоятельно, то предлагаем Вам - Услуги агента по торгам!</div>",
          fullSize: true,
          
          buttonPay: true,
        },
      ]
    },
    methods: {
      prevStep() {
        if(this.step != 1) this.step--
      },
      nextStep(){
        if(this.steps.length > this.step)  this.step++
      }
    }
  })

})