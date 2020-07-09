jQuery(function ($) {
  "use strict";

  var $window = $(window);
  // wish list start->
  $(".wish-js").on("click", function (e) {
    e.preventDefault();
    var lotId = $(this).data("id"),
      star = $(this).children("img"),
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
          number.html(Number(num) + 1);
          toastr.success("Лот добавлен в избранное");
        } else if (data["method"] === "delete") {
          star.attr("src", "img/star-o.svg");
          number.html(Number(num) - 1);
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
    { offset: "-20px" }
  );

  /**
   *	Dropdown effect
   */

  var dropdownSmooth02 = $(".dropdown.dropdown-smooth-02");

  dropdownSmooth02.on("show.bs.dropdown", function (e) {
    var $dropdownSmooth02Menu = $(this).find(".dropdown-menu");
    var orig_margin_top = parseInt($dropdownSmooth02Menu.css("margin-top"));
    $dropdownSmooth02Menu
      .css({ "margin-top": orig_margin_top + 10 + "px", opacity: 0 })
      .animate(
        { "margin-top": orig_margin_top + "px", opacity: 1 },
        300,
        function () {
          $(this).css({ "margin-top": "" });
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
        { "margin-top": orig_margin_top + 10 + "px", opacity: 0 },
        300,
        function () {
          $(this).css({ "margin-top": "", display: "" });
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

  $(".chosen-the-basic").chosen({ disable_search_threshold: 10 });
  $(".chosen-no-search").chosen({ disable_search: true });
  $(".chosen-sort-select")
    .chosen({ disable_search_threshold: 10 })
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
    .chosen({ disable_search_threshold: 10, allow_single_deselect: true })
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
    .chosen({ disable_search_threshold: 10, allow_single_deselect: true })
    .change(function (e, id) {
      if (id.selected == 0) {
        $("#searchlot-subcategory")
          .prop("disabled", true)
          .trigger("chosen:updated");
      } else {
        $("#searchlot-subcategory").load(
          "/load-category",
          { id: id.selected },
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
    .chosen({ disable_search_threshold: 10, allow_single_deselect: true })
    .change(function (e, id) {
      if (id.selected == 0) {
        $("#searchlot-subcategory")
          .prop("disabled", true)
          .trigger("chosen:updated");
      } else {
        $("#searchlot-subcategory").load(
          "/lot/load-sub-categories",
          { id: id.selected },
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
    .chosen({ disable_search_threshold: 10, allow_single_deselect: true })
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
          { id: id.selected, type: lotType },
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
    .chosen({ disable_search_threshold: 10, allow_single_deselect: true })
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
