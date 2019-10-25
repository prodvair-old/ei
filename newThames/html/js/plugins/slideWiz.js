$.fn.slideWiz = function (e) {
    const PREV = 0;
    const NEXT = 1;
    var move = true;

    var cl = function (n, i, c) {
        move = false;
        if (c === NEXT) {
            switch (i) {
                case"card":
                    var t = $(n).children(), r = $(n);
                    $(t[0]).css({top: $(t[0]).innerHeight() + "px", "z-index": "0"}), $(t[1]).css({
                        top: "0px",
                        "z-index": "10"
                    }), $($(t[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(t[0]).children()[1]).children().hide(1000), $($(t[1]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(t[1]).children()[1]).children()[0]).fadeIn(200, function () {
                        $($($(t[1]).children()[1]).children()[1]).fadeIn(300, function () {
                            $($($(t[1]).children()[1]).children()[2]).fadeIn(400);
                            var tm = setTimeout(function () {
                                move = true;
                                clearTimeout(tm);
                            },600);
                        });
                    }), $(r).append(t[0]);
                    break;
                case"fade":
                    var t = $(n).children(), r = $(n);
                    $($(t[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(t[0]).children()[1]).children().hide(1000), $(t[0]).hide(1000, function () {
                        $($(t[1]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(t[1]).children()[1]).children()[0]).fadeIn(200, function () {
                            $($($(t[1]).children()[1]).children()[1]).fadeIn(300, function () {
                                $($($(t[1]).children()[1]).children()[2]).fadeIn(400);
                                var tm = setTimeout(function () {
                                    move = true;
                                    clearTimeout(tm);
                                },600);
                            });
                        }), $(r).append(t[0]), $(t[0]).show();
                    });
                    break;
                case"box3D":
                    var t = $(n).children(), r = $(n);
                    $($(t[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(t[0]).children()[1]).children().hide(1000), $(t[0]).slideUp(1000, function () {
                        $($(t[1]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(t[1]).children()[1]).children()[0]).fadeIn(200, function () {
                            $($($(t[1]).children()[1]).children()[1]).fadeIn(300, function () {
                                $($($(t[1]).children()[1]).children()[2]).fadeIn(400);
                                var tm = setTimeout(function () {
                                    move = true;
                                    clearTimeout(tm);
                                },600);
                            });
                        }), $(r).append(t[0]), $(t[0]).show();
                    });
            }
        } else if (c === PREV) {
            switch (i) {
                case"card":
                    var t = $(n).children(), r = $(n);
                    $(t[0]).css({
                        top: $(t[0]).innerHeight() + "px",
                        "z-index": "0"
                    }), $($(t[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(t[0]).children()[1]).children().hide(1000), $(t[t.length - 1]).hide(), $(r).prepend(t[t.length - 1]);
                    var t = $(n).children();
                    $(t[0]).show(), $(t[0]).css({
                        top: "0px",
                        "z-index": "10"
                    }), $($(t[0]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(t[0]).children()[1]).children()[0]).fadeIn(200, function () {
                        $($($(t[0]).children()[1]).children()[1]).fadeIn(300, function () {
                            $($($(t[0]).children()[1]).children()[2]).fadeIn(400);
                            var tm = setTimeout(function () {
                                move = true;
                                clearTimeout(tm);
                            },600);
                        });
                    });
                    break;
                case"fade":
                    var t = $(n).children(), r = $(n);
                    $($(t[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(t[0]).children()[1]).children().hide(1000), $(t[t.length - 1]).hide(), $(r).prepend(t[t.length - 1]);
                    var t = $(n).children();
                    $(t[0]).show(1000, function () {
                        $($(t[0]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(t[0]).children()[1]).children()[0]).fadeIn(200, function () {
                            $($($(t[0]).children()[1]).children()[1]).fadeIn(300, function () {
                                $($($(t[0]).children()[1]).children()[2]).fadeIn(400);
                                var tm = setTimeout(function () {
                                    move = true;
                                    clearTimeout(tm);
                                },600);
                            });
                        });
                    });
                    break;
                case"box3D":
                    var t = $(n).children(), r = $(n);
                    $($(t[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(t[0]).children()[1]).children().hide(1000), $(t[t.length - 1]).hide(), $(r).prepend(t[t.length - 1]);
                    var t = $(n).children();
                    $(t[0]).slideDown(1000, function () {
                        $($(t[0]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(t[0]).children()[1]).children()[0]).fadeIn(200, function () {
                            $($($(t[0]).children()[1]).children()[1]).fadeIn(300, function () {
                                $($($(t[0]).children()[1]).children()[2]).fadeIn(400);
                                var tm = setTimeout(function () {
                                    move = true;
                                    clearTimeout(tm);
                                },600);
                            });
                        });
                    });
            }
        }
    };

    var au = function (n, i) {
        move = false;
        switch (i) {
            case"card":
                var c = $(n).children(), t = $(n);
                $(c[0]).css({top: $(c[0]).innerHeight() + "px", "z-index": "0"}), $(c[1]).css({
                    top: "0px",
                    "z-index": "10"
                }), $($(c[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(c[0]).children()[1]).children().hide(1000), $($(c[1]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(c[1]).children()[1]).children()[0]).fadeIn(200, function () {
                    $($($(c[1]).children()[1]).children()[1]).fadeIn(300, function () {
                        $($($(c[1]).children()[1]).children()[2]).fadeIn(400);
                        move = true;
                    });
                }), $(t).append(c[0]), $(c[0]).show();
                break;
            case"fade":
                var c = $(n).children(), t = $(n);
                $($(c[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(c[0]).children()[1]).children().hide(1000), $(c[0]).hide(1000, function () {
                    $($(c[1]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(c[1]).children()[1]).children()[0]).fadeIn(200, function () {
                        $($($(c[1]).children()[1]).children()[1]).fadeIn(300, function () {
                            $($($(c[1]).children()[1]).children()[2]).fadeIn(400);
                            move = true;
                        });
                    }), $(t).append(c[0]), $(c[0]).show()
                });
                break;
            case"box3D":
                var c = $(n).children(), t = $(n);
                $($(c[0]).children()[1]).css("top", $(d).innerHeight() + "px"), $($(c[0]).children()[1]).children().hide(1000), $(c[0]).slideUp(1000, function () {
                    $($(c[1]).children()[1]).css("top", $(d).innerHeight() / 3 + "px"), $($($(c[1]).children()[1]).children()[0]).fadeIn(200, function () {
                        $($($(c[1]).children()[1]).children()[1]).fadeIn(300, function () {
                            $($($(c[1]).children()[1]).children()[2]).fadeIn(400);
                            move = true;
                        });
                    }), $(t).append(c[0]), $(c[0]).show()
                })
        }
    };

    var d = $(this), c = $('<div>').attr("class", "slide-holder");
    var len = e.file.length;
    for (var t = 0; t < len; t++) {
        var r = $('<div>').attr("class", e.type), l = $('<img>').attr("src", e.file[t].src),
            h = $('<div>').attr("class", "detail-box").css("top", $(d).innerHeight() + "px");
        if (e.file[t].title !== false) {
            $(h).append(
                $('<div>').attr("class", "detail-title").html(e.file[t].title)
            );
        }
        if (e.file[t].desc !== false) {
            $(h).append(
                $('<span>').attr("class", "detail-desc").html(e.file[t].desc.substring(0, e.file[t].desc.substring(0, 130).lastIndexOf(" ")) + (e.file[t].desc.length > 130 ? "..." : ""))
            );
        }
        if (e.file[t].btnTitle !== false) {
            if (e.file[t].btnUrl !== false) {
                $(h).append(
                    $('<a>').attr({'href' : e.file[t].btnUrl, 'target' : '_blanck'}).html(
                        $('<button>').attr("class", "detail-button").html(e.file[t].btnTitle)
                    )
                );
            } else {
                $(h).append(
                    $('<button>').attr("class", "detail-button").html(e.file[t].btnTitle)
                );
            }
        }
        $(r).append(l);
        $(r).append(h);
        $(c).append(r);

        if (t > 0 && e.type === "card") {
            $(r).css({
                top: $(d).innerHeight() + "px",
                "z-index": "0"
            });
        } else {
            $(r).css({top: "0px", "z-index": "10"})
        }
    }
    $(this).html(c);
    $(this).append(
        $('<button>').attr({
            class: "half-circle-prev",
            title: "Previous"
        }).html(
            $('<span>').html("&#10094;")
        ).click(function () {
            if (move === true) {
                cl(c, e.type, PREV);
            }
        })
    ).append(
        $('<button>').attr({
            class: "half-circle-next",
            title: "Next"
        }).html(
            $('<span>').html("&#10095;")
        ).click(function () {
            if (move === true) {
                cl(c, e.type, NEXT);
            }
        })
    );

    $(function () {
        var e = $(c).children();
        $($(e[0]).children()[1]).css("top", $(d).innerHeight() / 3 + "px");
        $($($(e[0]).children()[1]).children()[0]).fadeIn(200, function () {
            $($($(e[0]).children()[1]).children()[1]).fadeIn(300, function () {
                $($($(e[0]).children()[1]).children()[2]).fadeIn(400)
            });
        });
    });

    if (e.auto === true) {
        var tm = null;
        var inv = function () {
            if (tm !== null) {
                if (move === true) {
                    au(c, e.type);
                }
                clearTimeout(tm);
            }
            tm = setTimeout(inv,e.speed);
        };
        inv();
    }
};