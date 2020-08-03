jQuery(function ($) {
  "use strict";

  var $window = $(window);
  // Report slider Start->
  $(".report__body__images__slider").slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
    prevArrow:
      '<button type="button" class="report__body__images__arrow-prev slick-prev"></button>',
    nextArrow:
      '<button type="button" class="report__body__images__arrow-next slick-next"></button>',
  });
  $(".zoom-gallery .slick-track").magnificPopup({
    delegate: "div",
    type: "image",
    closeOnContentClick: false,
    closeBtnInside: false,
    mainClass: "mfp-with-zoom mfp-img-mobile",
    image: {
      verticalFit: true,
    },
    gallery: {
      enabled: true,
    },
    zoom: {
      enabled: true,
      duration: 300, // don't foget to change the duration also in CSS
      opener: function (element) {
        return element.find("img");
      },
    },
  });
  // Report slider <-End

  // diogramm procent Start->
  // const circle = document.querySelector('.progress-ring__circle');
  // const r = circle.r.baseVal.value;
  // const circumference = 2 * Math.PI * r;
  // const input = document.querySelector('.percente');

  // circle.style.strokeDasharray = `${circumference} ${circumference}`;
  // circle.style.strokeDashoffset = circumference;

  // function setProgress(percent) {
  //   const offset = circumference - percent / 100 * circumference;
  //   circle.style.strokeDashoffset = offset;
  // }

  // setProgress(20);

  // input.addEventListener('input', function () {
  //   setProgress(input.value);
  // });
  // diogramm procent <-End

  // wish list start->
  $(".lot__block__img__favorite").on("click", function (e) {
    e.preventDefault();
  });
  $(".wish-js").on("click", function (e) {
    e.preventDefault();
    var lotId = $(this).data("id"),
      star = $(this).children("img"),
      svgStar = $(this).children("svg").children("path"),
      number = $(this).children("span"),
      item = $(this).parents(".col");

    console.log($(this).children("img"));
    $.ajax({
      url: "/wish-list-edit",
      type: "GET",
      data: {
        lotId,
      },
      success: function (data) {
        var num = number.html();

        if (data["method"] === "save") {
          star.attr("src", "img/star.svg");
          svgStar
            .attr(
              "d",
              `M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757
              c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042
              c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685
              c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528
              c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956
              C22.602,0.567,25.338,0.567,26.285,2.486z`
            )
            .css({
              fill: "#FFB436",
            })
            .removeClass("lot__block__img__favorite-path");
          number.html(Number(num) + 1);
          toastr.success("Лот добавлен в избранное");
        } else if (data["method"] === "delete") {
          star.attr("src", "img/star-o.svg");
          svgStar
            .attr(
              "d",
              `M48.856,22.731c0.983-0.958,1.33-2.364,0.906-3.671c-0.425-1.307-1.532-2.24-2.892-2.438l-12.092-1.757
              c-0.515-0.075-0.96-0.398-1.19-0.865L28.182,3.043c-0.607-1.231-1.839-1.996-3.212-1.996c-1.372,0-2.604,0.765-3.211,1.996
              L16.352,14c-0.23,0.467-0.676,0.79-1.191,0.865L3.069,16.623C1.71,16.82,0.603,17.753,0.178,19.06
              c-0.424,1.307-0.077,2.713,0.906,3.671l8.749,8.528c0.373,0.364,0.544,0.888,0.456,1.4L8.224,44.702
              c-0.232,1.353,0.313,2.694,1.424,3.502c1.11,0.809,2.555,0.914,3.772,0.273l10.814-5.686c0.461-0.242,1.011-0.242,1.472,0
              l10.815,5.686c0.528,0.278,1.1,0.415,1.669,0.415c0.739,0,1.475-0.231,2.103-0.688c1.111-0.808,1.656-2.149,1.424-3.502
              L39.651,32.66c-0.088-0.513,0.083-1.036,0.456-1.4L48.856,22.731z M37.681,32.998l2.065,12.042c0.104,0.606-0.131,1.185-0.629,1.547
              c-0.499,0.361-1.12,0.405-1.665,0.121l-10.815-5.687c-0.521-0.273-1.095-0.411-1.667-0.411s-1.145,0.138-1.667,0.412l-10.813,5.686
              c-0.547,0.284-1.168,0.24-1.666-0.121c-0.498-0.362-0.732-0.94-0.629-1.547l2.065-12.042c0.199-1.162-0.186-2.348-1.03-3.17
              L2.48,21.299c-0.441-0.43-0.591-1.036-0.4-1.621c0.19-0.586,0.667-0.988,1.276-1.077l12.091-1.757
              c1.167-0.169,2.176-0.901,2.697-1.959l5.407-10.957c0.272-0.552,0.803-0.881,1.418-0.881c0.616,0,1.146,0.329,1.419,0.881
              l5.407,10.957c0.521,1.058,1.529,1.79,2.696,1.959l12.092,1.757c0.609,0.089,1.086,0.491,1.276,1.077
              c0.19,0.585,0.041,1.191-0.4,1.621l-8.749,8.528C37.866,30.65,37.481,31.835,37.681,32.998z`
            )
            .css({
              fill: "",
            })
            .addClass("lot__block__img__favorite-path");
          var num = Number(num) - 1;
          if (num > 0) {
            number.html(num);
          } else {
            number.html("");
          }
          toastr.success("Лот удалён из избранных");
          item.fadeOut();
        }
      },
    }).fail(function () {
      toastr.error("Произошла ошибка");
    });
  });
  // wish list <-end
  // save search start->
  $(".save-lot-search-js").on("click", function (e) {
    e.preventDefault();
    $.ajax({
      url: "/lot/save-search",
      type: "GET",
      data: {
        url: document.location.href,
        send_email: $("#search-preset-agree").is(":checked"),
      },
      success: function (res) {
        if (res) {
          toastr.success("Поиск сохранён");
        } else {
          toastr.warning("не удалось сохранить поиск");
        }
      },
      error: function (res) {
        console.log(res);
        toastr.error("Ошибка при сохранении поиска");
      },
    });
  });
  $(".search-preset-box__check input").on("change", function (e) {
    $.ajax({
      url: "/profile/search-preset-change",
      type: "GET",
      data: {
        id: $(this).data("id"),
        send_email: $(this).is(":checked"),
      },
      success: function (res) {
        if (res) {
          toastr.success("Успешно изменено");
        } else {
          toastr.warning("Не удалось изменить");
        }
      },
      error: function (res) {
        console.log(res);
        toastr.error("Ошибка при изменении");
      },
    });
  });
  $(".search-preset-box__del-js").on("click", function (e) {
    e.preventDefault();
    var presetId = $(this).data("id");
    var list = $(".search-preset-list");
    $.ajax({
      url: "/profile/search-preset-del",
      type: "GET",
      data: {
        id: presetId,
      },
      success: function (res) {
        if (res) {
          $(".search-preset-box-" + presetId).addClass("del");
          var count =
            list.children(".search-preset-box").length -
            list.children(".search-preset-box.del").length;
          if (count === 0) {
            $(".search-preset-sender").removeClass("d-md-block");
            $(".search-preset__info").addClass("active");
          }
          toastr.success("Успешно удалено");
        } else {
          toastr.warning("Не удалось удаленть");
        }
      },
      error: function (res) {
        console.log(res);
        toastr.error("Ошибка при удалении");
      },
    });
  });
  // save search <-end

  /**
   * Main Menu Slide Down Effect
   */
  var navbar = $(".main-nav-menu");

  // Main Menu
  navbar.find("li").on("mouseenter", function () {
    $(this)
      .find("ul")
      .first()
      .stop(true, true)
      .delay(250)
      .slideDown(500, "easeInOutQuad");
  });
  navbar.find("li").on("mouseleave", function () {
    $(this)
      .find("ul")
      .first()
      .stop(true, true)
      .delay(100)
      .slideUp(150, "easeInOutQuad");
  });

  // Arrow for Menu has sub-menu
  $(".navbar-arrow ul li")
    .has("ul")
    .children("a")
    .append("<span class='arrow-indicator'></span>");
  $(".navbar-arrow ul li .arrow-indicator").on("click", function (e) {
    e.preventDefault();
  });

  // Phone editor
  $(".phone_mask").mask("+7 (999) 999-99-99");
  $(".code_mask").mask("9-9-9-9");
  if (typeof edit_phone != undefined) {
    $(".resend-code").on("click", function (e) {
      e.preventDefault();
      $(".loader").hide();
      $.ajax({
        url: "/profile/get-code",
        type: "GET",
        success: function (res) {
          $(".loader").hide();
          toastr.success("Код отправлен");
          console.log(res);
          timer(60);
        },
        error: function (res) {
          $(".phone-form-error").html("Серверная ошибка");
        },
      });
    });
  }

  /**
   * Sticky Header
   */
  $(".with-waypoint-sticky").waypoint(
    function () {
      $("#header-waypoint-sticky").toggleClass("header-waypoint-sticky");
      return false;
    },
    {
      offset: "-20px",
    }
  );

  /**
   *	Dropdown effect
   */

  var dropdownSmooth02 = $(".dropdown.dropdown-smooth-02");

  dropdownSmooth02.on("show.bs.dropdown", function (e) {
    var $dropdownSmooth02Menu = $(this).find(".dropdown-menu");
    var orig_margin_top = parseInt($dropdownSmooth02Menu.css("margin-top"));
    $dropdownSmooth02Menu
      .css({
        "margin-top": orig_margin_top + 10 + "px",
        opacity: 0,
      })
      .animate(
        {
          "margin-top": orig_margin_top + "px",
          opacity: 1,
        },
        300,
        function () {
          $(this).css({
            "margin-top": "",
          });
        }
      );
  });
  dropdownSmooth02.on("hide.bs.dropdown", function (e) {
    var $dropdownSmooth02Menu = $(this).find(".dropdown-menu");
    var orig_margin_top = parseInt($dropdownSmooth02Menu.css("margin-top"));
    $dropdownSmooth02Menu
      .css({
        "margin-top": orig_margin_top + "px",
        opacity: 1,
        display: "block",
      })
      .animate(
        {
          "margin-top": orig_margin_top + 10 + "px",
          opacity: 0,
        },
        300,
        function () {
          $(this).css({
            "margin-top": "",
            display: "",
          });
        }
      );
  });

  $('[data-toggle="tooltip"]').tooltip();

  /**
   *  Tab in dropdown
   */
  $(".tab-in-dropdown").on("click", ".nav a", function () {
    $(this).closest(".dropdown").addClass("dontClose");
  });

  $(".dropdown-tab").on("hide.bs.dropdown", function (e) {
    if ($(this).hasClass("dontClose")) {
      e.preventDefault();
    }
    $(this).removeClass("dontClose");
  });

  $("a.tab-external-link").on("click", function (e) {
    e.preventDefault();
    var tabPattern = /#.+/gi; //use regex to get anchor(==selector)
    var tabContentID = e.target.toString().match(tabPattern)[0]; //get anchor
    $('.external-link-navs a[href="' + tabContentID + '"]').tab("show");
  });

  /**
   *  Open specific tab in modal
   */
  $("a[data-toggle=modal][data-target]").on("click", function () {
    var tabTargetInModal = $(this).attr("href");
    $("a[data-toggle=tab][href=" + tabTargetInModal + "]").tab("show");
  });

  /**
   * Chosen
   */

  $(".chosen-the-basic").chosen({
    disable_search_threshold: 10,
  });
  $(".chosen-no-search").chosen({
    disable_search: true,
  });
  $(".chosen-sort-select")
    .chosen({
      disable_search_threshold: 10,
    })
    .change(function () {
      $(".load-list").html(
        '<div class="spinner-wrapper"><div class="spinner"></div>Сортируем лоты...</div>'
      );
      $("#sort-lot-form").submit();
    });

  if (typeof lotType !== "undefined") {
    filterParams();
  }

  $(".chosen-type-select")
    .chosen({
      disable_search_threshold: 10,
      allow_single_deselect: true,
    })
    .change(function (e, type) {
      $("#search-lot-form").submit();

      if (!$(".zalog-js").hasClass("d-none")) {
        $(".zalog-js").addClass("d-none");
      }
      if (!$(".bankrupt-js").hasClass("d-none")) {
        $(".bankrupt-js").addClass("d-none");
      }

      if (type !== null && type.selected === "1") {
        $(".bankrupt-js").removeClass("d-none");
      } else if (type.selected === "3") {
        $(".zalog-js").removeClass("d-none");
      }
    });

  function filterParams() {
    if (lotType == "arrest") {
      $(".bankrupt-type").hide();
      $(".zalog-type").hide();
    } else if (lotType == "bankrupt") {
      $(".bankrupt-type").show();
      $(".zalog-type").hide();
    } else if (lotType == "zalog") {
      $(".zalog-type").show();
      $(".bankrupt-type").hide();
    } else if (lotType == "all") {
      $(".bankrupt-type").show();
      $(".zalog-type").show();
    }
  }

  $(".chosen-category-select")
    .chosen({
      disable_search_threshold: 10,
      allow_single_deselect: true,
    })
    .change(function (e, id) {
      if (id.selected == 0) {
        $("#searchlot-subcategory")
          .prop("disabled", true)
          .trigger("chosen:updated");
      } else {
        $("#searchlot-subcategory").load(
          "/load-category",
          {
            id: id.selected,
          },
          function (data) {
            if (data == '<option value="0">Все подкатегории</option>') {
              $("#searchlot-subcategory")
                .prop("disabled", true)
                .trigger("chosen:updated");
            } else {
              $("#searchlot-subcategory")
                .prop("disabled", false)
                .trigger("chosen:updated");
            }
          }
        );
      }
    });

  $(".chosen-category-select-lot")
    .chosen({
      disable_search_threshold: 10,
      allow_single_deselect: true,
    })
    .change(function (e, id) {
      if (id.selected == 0) {
        $("#searchlot-subcategory-wrapper").addClass("hidden");
        $("#searchlot-subcategory")
          .prop("disabled", true)
          .trigger("chosen:updated");
      } else {
        $("#searchlot-subcategory-wrapper").removeClass("hidden");
        $("#searchlot-subcategory").load(
          "/lot/load-sub-categories",
          {
            id: id.selected,
          },
          function (data) {
            if (data == '<option value="0">Все подкатегории</option>') {
              $("#searchlot-subcategory")
                .prop("disabled", true)
                .trigger("chosen:updated");
            } else {
              $("#searchlot-subcategory")
                .prop("disabled", false)
                .trigger("chosen:updated");
            }
          }
        );
      }
    });

  if (
    $(".map .chosen-category-select-lot").val() === "0" &&
    $(".map .chosen-type-select").val() === "0"
  ) {
    $(".map .chosen-category-select-lot").val("2").trigger("chosen:updated");
  }

  $(".chosen-zalog-category-select")
    .chosen({
      disable_search_threshold: 10,
      allow_single_deselect: true,
    })
    .change(function (e, id) {
      var lotId = $(this).data("lotid");
      var lotType = $(this).data("lottype");

      if (id.selected == 0) {
        $(".subcategory-" + lotId + "-load")
          .prop("disabled", true)
          .trigger("chosen:updated");
      } else {
        $(".subcategory-" + lotId + "-load").load(
          "/load-category",
          {
            id: id.selected,
            type: lotType,
          },
          function (data) {
            if (data == '<option value="0">Все подкатегории</option>') {
              $(".subcategory-" + lotId + "-load")
                .prop("disabled", true)
                .trigger("chosen:updated");
            } else {
              $(".subcategory-" + lotId + "-load")
                .prop("disabled", false)
                .trigger("chosen:updated");
            }
          }
        );
      }
    });

  $(".chosen-zalog-subcategory-select")
    .chosen({
      disable_search_threshold: 10,
      allow_single_deselect: true,
    })
    .change(function (e, id) {
      var lotId = $(this).data("lotid");
      var lotType = $(this).data("lottype");
      var formData = new FormData(
        document.getElementById("lot-" + lotId + "-zalog-categorys")
      );

      $.ajax({
        type: "POST",
        contentType: false,
        processData: false,
        url: $("#lot-" + lotId + "-zalog-categorys").attr("action"),
        data: formData,
      })
        .done(function (data) {
          if (data) {
            toastr.success("Категории успешно присвоины на лот №" + lotId);
          } else {
            toastr.warning("Не удалось приминенить категории на лот №" + lotId);
          }
        })
        .fail(function () {
          toastr.error("Ошибка при приминении категории на лот №" + lotId);
        });
    });

  /**
   * Input Spinner
   */

  $(".touch-spin-03").TouchSpin({
    min: 1,
    max: 10,
    buttondown_class: "btn btn-light btn-touch-spin",
    buttonup_class: "btn btn-light btn-touch-spin",
  });

  /**
   * Sticky sidebar
   */

  $(".sticky-kit").stick_in_parent({
    offset_top: 105,
  });

  $(".sticky-kit-02").stick_in_parent({
    offset_top: 130,
  });

  /**
   * Date and month Picker
   */

  $(".air-datepicker").datepicker({
    minDate: new Date(),
  });

  /**
   * Slick carousel
   */

  $(".slick-the-basic").slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    dots: true,
  });

  $(".slick-testimonial-grid-arrows").slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    prevArrow: $(".testimonial-grid-prev"),
    nextArrow: $(".testimonial-grid-next"),
    responsive: [
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
        },
      },
    ],
  });

  $(".gallery-slideshow").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 500,
    arrows: true,
    fade: true,
    asNavFor: ".gallery-nav",
  });

  $(".gallery-nav").slick({
    slidesToShow: 7,
    slidesToScroll: 1,
    speed: 500,
    asNavFor: ".gallery-slideshow",
    dots: false,
    centerMode: true,
    focusOnSelect: true,
    infinite: true,
    responsive: [
      {
        breakpoint: 1199,
        settings: {
          slidesToShow: 7,
        },
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 5,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 5,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 3,
        },
      },
    ],
  });

  $(".slick-hero").slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    dots: false,
    arrows: true,
    variableWidth: true,
    centerMode: true,
  });

  $(".slick-hero-alt").slick({
    infinite: true,
    slidesToShow: 2,
    slidesToScroll: 2,
    dots: false,
    responsive: [
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: false,
          arrows: false,
        },
      },
    ],
  });

  $(".slick-hero-alt-02").slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: false,
    responsive: [
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: false,
          arrows: false,
        },
      },
    ],
  });

  $(".slick-top-destination").slick({
    infinite: true,
    slidesToShow: 2,
    slidesToScroll: 1,
    dots: false,
    arrows: true,
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 1,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          autoplay: true,
          autoplaySpeed: 5000,
        },
      },
      {
        breakpoint: 576,
        settings: {
          arrows: false,
          slidesToShow: 1,
          autoplay: true,
          autoplaySpeed: 5000,
        },
      },
    ],
  });

  $(".slick-top-destination-alt").slick({
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    dots: false,
    arrows: true,
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });

  /**
   * Counter - Number animation
   */

  $(".counter").countimator();

  /**
   * Range Slider
   */
  var priceMin = $(".lot__price-min"),
    priceMax = $(".lot__price-max");

  var saveResult = function (data) {
    priceMin.val(data.from);
    priceMax.val(data.to);
  };

  $("#price_range").ionRangeSlider({
    type: "double",
    grid: true,
    min: $("#price_range").data("min"),
    max: $("#price_range").data("max"),
    from: $("#price_range").data("min"),
    to: $("#price_range").data("max"),
    prefix: "₽",
    onLoad: function (data) {
      saveResult(data);
      onLoadPriceRange($("#price_range").data("ionRangeSlider"));
    },
    onChange: saveResult,
    onFinish: saveResult,
  });

  onLoadPriceRange($("#price_range").data("ionRangeSlider"));

  function onLoadPriceRange(price_range) {
    if (priceMin.val()) {
      var fromNumber = priceMin.val();
    } else {
      var fromNumber = $("#price_range").data("min");
    }
    if (priceMax.val()) {
      var toNumber = priceMax.val();
    } else {
      var toNumber = $("#price_range").data("max");
    }
    if (typeof price_range != "undefined" && price_range !== null) {
      price_range.update({
        from: fromNumber,
        to: toNumber,
      });
    }
  }

  $("#star_range").ionRangeSlider({
    type: "double",
    grid: false,
    from: 1,
    to: 2,
    values: [
      "<i class='ri-star'></i>",
      "<i class='ri-star'></i> <i class='ri-star'></i>",
      "<i class='ri-star'></i> <i class='ri-star'></i> <i class='ri-star'></i>",
      "<i class='ri-star'></i> <i class='ri-star'></i> <i class='ri-star'></i> <i class='ri-star'></i>",
      "<i class='ri-star'></i> <i class='ri-star'></i> <i class='ri-star'></i> <i class='ri-star'></i> <i class='ri-star'></i>",
    ],
  });

  /**
   * Smooth scroll to anchor
   */
  $("a.anchor[href*=#]:not([href=#])").on("click", function () {
    if (
      location.pathname.replace(/^\//, "") ==
        this.pathname.replace(/^\//, "") &&
      location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
      if (target.length) {
        $("html,body").animate(
          {
            scrollTop: target.offset().top - 140, // 70px offset for navbar menu
          },
          300
        );
        return false;
      }
    }
  });

  /**
   * Input masking
   */
  $(".mask-data-mask").mask();

  /**
   * Contact for validator
   */

  var contactFormValidator = $("#contact-form");

  contactFormValidator.validator();

  contactFormValidator.on("submit", function (e) {
    if (!e.isDefaultPrevented()) {
      var url = "contact.php";

      $.ajax({
        type: "POST",
        url: url,
        data: $(this).serialize(),
        success: function (data) {
          var messageAlert = "alert-" + data.type;
          var messageText = data.message;

          var alertBox =
            '<div class="alert ' +
            messageAlert +
            ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            messageText +
            "</div>";
          if (messageAlert && messageText) {
            $("#contact-form")
              .find(".contact-successful-messages")
              .html(alertBox);
            $("#contact-form")[0].reset();
          }
        },
      });
      return false;
    }
  });

  /**
   * Back To Top
   */

  // var backToTop = $("#back-to-top");

  //  $window.scroll(function () {
  // 	if ($(this).scrollTop() > 50) {
  // 		backToTop.fadeIn();
  // 	} else {
  // 		backToTop.fadeOut();
  // 	}
  // });

  // // scroll body to 0px on click
  // backToTop.on("click",function () {
  // 	backToTop.tooltip('hide');
  // 	$('body,html').animate({
  // 		scrollTop: 0
  // 	}, 800);
  // 	return false;
  // });

  // backToTop.tooltip('show');
});
