$(document).ready(function () {
    'use strict';

    $.doScroll();
    $(document).data("lastPage", null).data("curPage", $("body").attr('id'));
    $.notify.addStyle("bootstrap", {
        html: '<div><div class="info"><button type="button" class="close" aria-hidden="true">&times;</button><span data-notify-text/></div></div>',
        classes: {
            base: {
                "padding": "10px",
                "font-weight": "700",
                "font-size": "small",
                "line-height": "21px",
                "-webkit-border-radius": "4px",
                "-moz-border-radius": "4px",
                "border-radius": "4px",
                "-webkit-background-clip": "padding-box",
                "-moz-background-clip": "padding",
                "background-clip": "padding-box",
                "-webkit-box-shadow": "0 1px 0 rgba(0, 0, 0, 0.05)",
                "-moz-box-shadow": "0 1px 0 rgba(0,0,0,.05)",
                "box-shadow": "0 1px 0 rgba(0, 0, 0, 0.05)",
                "overflow": "hidden",
                "border": "1px solid rgba(0, 0, 0, 0.05)",
                "border-color": "#DDD"
            },
            info: {
                color: "#5a5a5a",
                "background-color": "#fefefe",
                "border-color": "#dadada"
            },
            success: {
                color: "#468847",
                "background-color": "#DFF0D8",
                "border-color": "#D6E9C6"
            },
            warning: {
                color: "#C09853",
                "background-color": "#FCF8E3",
                "border-color": "#FBEED5"
            },
            error: {
                color: "#B94A48",
                "background-color": "#F2DEDE",
                "border-color": "#EED3D7"
            }
        }
    });
    $("#back2top").isGoTopButton();
    $('#birthDate').datepicker({
        showOn: "focus",
        dateFormat: "d/m/yy",
        changeYear: true,
        yearRange: "1980:2010",
        onSelect: function(dateText, datePicker) {
            $("#dateDay").val(datePicker.selectedDay);
            $("#dateMonth").val(datePicker.selectedMonth + 1);
            $("#dateYear").val(datePicker.selectedYear);
        },
        beforeShow: function(datePickerElement, datePicker) {
            var nvl = function(val1, val2) {
                val1 = $.trim(val1);
                return (val1 && val1 !== 0 ? val1 : val2);
            };
            var selectedDate = nvl($("#dateDay").val(), 1) + "/" + nvl($("#dateMonth").val(), 1) + "/" + nvl($("#dateYear").val(), 1996);
            $(this).datepicker("setDate", selectedDate);
        }
    });
    if (vDevs.user_id) {
        setTimeout(interval, 3000);
    }
    $('#container').pusher({
        handler: function() {
            var d = $("body").attr("id");
            if (d && vDevs[d + "Unload"]) {
                vDevs[d + "Unload"]();
            }
            d = null;
            this.updateText("title", 'page_title');
            this.updateHtml("#mainContent", 'html_content');
            this.updateHtml("#breadcrumb", 'breadcrumb');
            vDevs.user_id && updateBadge(this.res);
            $('body').attr({'id': this.res.page_id, 'pos-y': this.state.posY});
            if (this.state.posY) {
                $("html, body").animate({scrollTop: this.state.posY}, "normal");
            } else {
                if (!$.doScroll()) {
                    $("html, body").animate({scrollTop:0}, "normal");
                }
            }
            $('.nav-response').fixNav();
            var m = $('body').attr("id");
            if (m && vDevs[m]) {
                vDevs[m]();
            }
            m = null;
            $('.carousel').each(function(){
                var $this = $(this);
                $this.carousel().fixCarousel();
            });
        }
    });
    vDevs["_start"]();
    var a = $('body').attr('id');
    if (a && vDevs[a]) {
        vDevs[a]();
    }
    a = null;
    $(document).on('click touch', '#toggleMenu, #menuOverlay, body.has-menu #subCol a', function(e){
        e.preventDefault();
        $('body').toggleClass('has-menu');
    }).on('change', '#fLoadMode', function() {
        var $this = $(this);
        fLoadMode = $this.find(':selected').val();
        loadForum();
    }).on('click', '#fLoadIcon', function() {
        loadForum();
    }).on('click', '.confirm', function(e) {
        e.preventDefault();
        var $this = $(this).parent(),
            $parent = $this.closest('.list-group').attr('id'),
            ele;
        if (confirm('B\u1ea1n c\u00f3 ch\u1eafc ch\u1eafn mu\u1ed1n x\u00f3a tin nh\u1eafn \u0111\u00e3 ch\u1ecdn?')) {
            var mid = $this.data('id');
            Pace.ignore(function() {
                $.ajax({
                    method: 'POST',
                    url: (vDevs.BASE_URL + '/chat/ajax/delete?id=' + mid)
                }).done(function (data) {
                    if (data.status == 200 && data.success === true) {
                        $this.remove();
                        $('._chat').text(function(i, v) {
                            return parseInt(v) - 1;
                        });
                        switch ($parent) {
                            case 'chatMessages':
                                ele = $('#chatbox');
                                break;
                            case 'chatbox':
                                ele = $('#chatMessagesInner');
                                break;
                        }
                        if (ele) {
                            ele.find('[data-id="' + mid + '"]').remove();
                        }
                    }
                }).fail(function () {
                    showNotify('H\u1ec7 th\u1ed1ng b\u1eadn!', 'warning');
                });
            });
        }
    });
});

var fIsLoading = false,
    fLoadMode = 'recent',
    fTimeout = setTimeout(loadForum, 10000),
    forumTpl = tmpl('forumTemplate');

function loadForum()
{
    if (!fIsLoading) {
        var $target = $('#fLoadTarget');
        if ($target.length) {
            $('#fLoadIcon').addClass('fa-spin');
            clearTimeout(fTimeout);
            Pace.ignore(function() {
                $.ajax({
                    url: vDevs.BASE_URL + '/forum/ajax/load',
                    method: 'GET',
                    data: {
                        mode: fLoadMode
                    },
                    cache: false
                }).done(function(res) {
                    var html = '';
                    if (res.status == 200 && res.success === true && res.threads.length) {
                        res.threads.forEach(function(thread) {
                            html += tmpl('forumTemplate', thread);
                        });
                        $target.html(html);
                    }
                }).always(function() {
                    fIsLoading = false;
                    $('#fLoadIcon').removeClass('fa-spin');
                    fTimeout = setTimeout(loadForum, 10000)
                })
            })
        }
    }
}
var cLastUpdate = 0,
    chatTpl = tmpl('chatTemplate');
function interval() {
    Pace.ignore(function() {
        $.ajax({
            type: 'GET',
            url: vDevs.API_URL + '/interval',
            crossDomain: true,
            data: {
                token: vDevs.token,
                time: cLastUpdate,
                t: (new Date()).getTime()
            },
            success: function(e) {
                setTimeout(interval, 3000)
            },
            error: function() {
                setTimeout(interval, 5000)
            },
            timeout: 70000
        }).done(function(res) {
            if (res.status == 200) {
                cLastUpdate = res.time;
                updateBadge(res);
                if (res.new_unread_chat || res.new_unread_message || res.new_unread_notification) {
                    $.alert();
                    if (res.new_unread_chat) {
                        if (vDevs.browser == 'web') {
                            $('#chatMessagesInner').updateChat({
                                get: 'after',
                                time: $('#chatMessagesInner').children().last().attr('data-time'),
                                sort: 'ASC',
                                handler: function() {
                                    cLastUpdate = this.res['time'];
                                    if ($('#chatbox').length) {
                                        var html;
                                        $.each(this.res['messages'], function(i, v) {
                                            html = tmpl('chatTemplate', v);
                                            $('#chatbox').prepend(html)
                                        });
                                        if (this.isBottom) {
                                            $("#chatMessages").nanoScroller({
                                                scrollTop: ($("#chatMessagesInner").prop('scrollHeight') - $("#chatMessagesInner").outerHeight()),
                                                preventPageScrolling: true
                                            })
                                        } else {
                                            $("#chatMessages").nanoScroller({ preventPageScrolling: true })
                                        }
                                    }
                                }
                            })
                        } else if ($('#chatbox').length) {
                            $('#chatbox').updateChat({
                                get: 'after',
                                time: $('#chatbox').children().first().attr('data-time'),
                                sort: 'DESC'
                            })
                        };
                        showNotify('C\u00f3 n\u1ed9i dung chat m\u1edbi!', 'success')
                    };
                    if (res.new_unread_message) {
                        showNotify('B\u1ea1n c\u00f3 tin nh\u1eafn m\u1edbi!', 'success')
                    };
                    if (res.new_unread_notification) {
                        showNotify('B\u1ea1n c\u00f3 th\u00f4ng b\u00e1o m\u1edbi!', 'success')
                    }
                }
            }
        })
    })
};
function showNotify(b, a) {
    $.notify(b, {
        style: "bootstrap",
        className: a,
        autoHideDelay: 5000
    })
};
function fixNumber(a) {
    return a<=99 ? a : '99+';
};
function updateBadge(data) {
    $('._messages > .badge').remove();
    $('._notifications > .badge').remove();
    $('._chats > .badge').remove();
    if (data.unread_message != 0) {
        $('._messages').attr({'href': vDevs.BASE_URL + '/messages/new'}).append('<span class="badge">' + fixNumber(data.unread_message) + '</span>')
    } else {
        $('._messages').attr({'href': vDevs.BASE_URL + '/messages/'})
    };
    if (data.unread_notification != 0) {
        $('._notifications').append('<span class="badge">' + fixNumber(data.unread_notification) + '</span>');
    };
    if (data.unread_chat != 0) {
        $('._chats').append('<span class="badge">' + fixNumber(data.unread_chat) + '</span>');
    };
    $('._chat').text(data.chat_count);
    $('.userCoin').text(data.coin);
    $('.userGold').text(data.gold);
};

(function ( $, window, document ) {
    "use strict";
    if (!window.history.pushState || !window.ProgressEvent || !window.FormData) {
        $.fn.pusher = function() { return this; };
        $.fn.pusher.options = {};
        return;
    };
    if($.fn.pusher) { return; };
    var defaults = {
            initialPath: window.location.href.replace(/^https?:\/\/[^\/]+\//, '/'),
            repeatDelay: 500,
            before: function(done) {
                done();
            },
            handler: function() {
            },
            after: function() {
            },
            fail: function() {
                showNotify("H\u1ec7 th\u1ed1ng b\u1eadn!");
            },
            onStateCreation: function(state, elem) {
            }
        };

    function Plugin(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);
        this.init();
    };

    Plugin.prototype = {
        init: function() {
            var self = this;
            //create the initial state
            var initialState = createState({
                path: self.options.initialPath,
                posY: 0
            }, self.options.onStateCreation);
            history.replaceState(initialState, null, initialState.path);
            // click event
            $(self.element).on('click', 'a:not(.noPusher)', function (e) {
                if (!isRateLimited(self)){
                    var $this = $(this),
                        rootUrl = vDevs.BASE_URL;
                    var url = $this.attr('href');
                    var isInternalLink = url.substring(0, rootUrl.length) === rootUrl || url.indexOf(':') === -1;

                    if (!isInternalLink || $this.attr('target') || url.match(/^(#|javascript)/)) {
                        return;
                    }
                    setRateLimitRepeatTime(self);
                    // replace current state
                    var currentState = createState({
                        path: getRelativeUrl(window.location.href),
                        posY: $(window).scrollTop()
                    });
                    history.replaceState(currentState, null, currentState.path);
                    var state = createState({
                            path: getRelativeUrl(url),
                            elem: $this,
                            posY: 0
                        }, self.options.onStateCreation),
                        request = {
                            type: 'GET',
                            dataType: 'json',
                            url: state.path
                        };
                    run(self, request, state, true);
                }
                e.preventDefault();
            });
            // submit event
            $(self.element).on('submit', 'form:not(.noPusher)', function (e) {
                if (!isRateLimited(self)) {
                    var $this = $(this),
                        rootUrl = vDevs.BASE_URL;
                    var url = $this.attr('action');
                    var isInternalLink = url.substring(0, rootUrl.length) === rootUrl || url.indexOf(':') === -1;

                    if (!isInternalLink || $this.attr('target') || url.match(/^(#|javascript)/)) {
                        return;
                    };
                    setRateLimitRepeatTime(self);
                    // replace current state
                    var currentState = createState({
                        path: getRelativeUrl(window.location.href),
                        posY: $(window).scrollTop()
                    });
                    history.replaceState(currentState, null, currentState.path);
                    var state = createState({
                            path: getRelativeUrl(url),
                            elem: $this,
                            posY: 0
                        }, self.options.onStateCreation),
                        request = {
                            url: state.path,
                            type: $this.attr('method'),
                            dataType: 'json'
                        };

                    if (request.type.toLowerCase() === 'post') {
                        request.data = new FormData($this[0]);
                        request.cache = false;
                        request.contentType = false;
                        request.processData = false;
                        var $submit = $(document.activeElement);
                        if (!$submit.is('[type=submit]')) {
                            $submit = $submit.closest('[type=submit]');
                            if (!$submit.length) {
                                $submit = $this.find('[type=submit]').first()
                            }
                        };
                        request.data.append($submit.attr('name'), true);
                        request.beforeSend = function() {
                            $this.find('input, button, select, textarea').each(function() {
                                if ($(this).is(':disabled')) {
                                    $(this).data('disabled', true);
                                }
                                $(this).attr('disabled', true);
                            });
                        }
                        request.complete = function() {
                            $this.find('input, button, select, textarea').each(function() {
                                if ($(this).data('disabled') === true) {
                                    $(this).attr('disabled', true);
                                }
                                $(this).removeAttr('disabled');
                            })
                        }
                        if ($submit.attr('id') === 'upload') {
                            var f = $this.find('input[type="file"]')[0].files[0];
                            if (f && f.type.match(/image.*/)) {
                                request.xhr = function() {
                                    var xhr = new window.XMLHttpRequest,
                                        o = $submit.text();
                                    xhr.upload.addEventListener("progress", function(e) {
                                        if (e.lengthComputable) {
                                            if (e.loaded === e.total) {
                                                $submit.html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Đang xử lý');
                                            } else {
                                                $submit.text('Đang tải lên: ' + Math.floor(e.loaded * 100 / e.total) + "%");
                                            }
                                        }
                                    }, false);
                                    xhr.upload.addEventListener("load", function(e) {
                                        $submit.text(o)
                                    }, false);
                                    return xhr
                                }
                            } else {
                                showNotify('Chưa có tập tin nào được chọn!', 'warning');
                                e.preventDefault();
                                return;
                            }
                        }
                    } else {
                        request.data = $this.serialize();
                    };
                    run(self, request, state, true);
                };
                e.preventDefault()
            });
            //popstate event
            window.addEventListener('popstate', function(e) {
                // replace current state
                var currentState = createState({
                        path: getRelativeUrl(window.location.href),
                        posY: $(window).scrollTop()
                    }),
                    request = {
                        type: 'GET',
                        dataType: 'json',
                        url: currentState.path
                    };
                history.replaceState(currentState, null, currentState.path);
                run(self, request, e.state);
            });
        }
    };

    var createState = function(params, fn) {
            var state = {};
            params = params || {};
            state.path = params.path;
            state.posY = params.posY;
            state.time = new Date().getTime();
            if (fn) {
                fn(state, params.elem);
            }
            return state;
        },
        run = function(plugin, request, state, push) {
            if(!state) {
                return;
            }
            var context = {
                state: state,
                updateText: function(query, resource) {
                    var el = $(query).text(context.res[resource]);
                },
                updateHtml: function(query, resource) {
                    var el = $(query).html(context.res[resource]);
                }
            };

            var done = function() {
                $.ajax(request).done(function (res, status, xhr) {
                    context.res = res;
                    if (push) {
                        var redirectedLocation = xhr.getResponseHeader('X-vDevs-Location');

                        if (redirectedLocation && redirectedLocation != stripHash(state.path)) {
                            state.path = redirectedLocation;
                        }
                        history.pushState(state, null, state.path);
                    };
                    plugin.options.handler.apply(context);
                }).fail(function () {
                    plugin.options.fail.apply(context);
                }).always(function (a, b, c) {
                    plugin.options.after.apply(context);
                });
            };
            plugin.options.before.apply(context, [done]);
        },
        getRelativeUrl = function(url) {
            if (/^https?:\/\/[^\/]+$/.test(url)) {
                return '/';
            }
            url = url.replace(/^https?:\/\/[^\/]+\//, '/');
            return url;
        },
        stripHash = function (href) {
            return href.replace(/#.*/, '');
        },
        rateLimitRepeatTime = 0,
        isRateLimited = function (plugin) {
            var isDelayOver = (parseInt(Date.now()) > rateLimitRepeatTime);
            return !isDelayOver;
        },
        setRateLimitRepeatTime = function (plugin) {
            rateLimitRepeatTime = parseInt(Date.now()) + parseInt(plugin.options.repeatDelay);
        };

    $.fn.pusher = function (options) {
        if (!$.data(document, 'pusher')) {
            $.data(document, 'pusher', new Plugin( this, options ));
        }
    };
})( jQuery, window, document );

if (vDevs.browser == 'web') {
    var audio = new Audio(vDevs.BASE_URL + '/assets/odIeERVR1c5.mp3');
} else {
    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;
}
(function( $ ) {
    "use strict";
    $.fn.isGoTopButton=function(){return this.each(function(){var a=$(this).hide();$(function(){$(window).on('scroll load',function(){if($(this).scrollTop()>$(window).height()/2){a.fadeIn()}else{a.fadeOut()}});$(a).click(function(){$("html, body").animate({scrollTop:0},"normal");return false})})})};
    $.fn.fixNav = function(){
        return this.each(function () {
            var $pill = $(this), $width = 0, $dropdown, $child;
            $pill.children().each(function(){
                $width += $(this).show().outerWidth(true);
            });
            if ($pill.children().last().hasClass('dropdown')) {
                $pill.children().last().remove();
            }
            if ($pill.innerWidth() < $width && $pill.children().not(':hidden').length > 2) {
                $pill.append('<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Thêm <span class="caret"></span></a><ul class="dropdown-menu"></ul></li>');
                $dropdown = $pill.children().last().children('ul');
                $width += $pill.children().last().outerWidth(true);
            }
            while ($pill.innerWidth() < $width && $pill.children().not(':hidden').length > 2) {
                $child  = $pill.children().not(':hidden').eq(-2);
                $width -= $child.outerWidth(true);
                $child.clone().css({display:''}).prependTo($dropdown);
                $child.hide();
            }
        }
    )};
    $.fn.fixCarousel = function() {
        return this.each(function(){
            var $this = $(this), items = $this.find('.item'), height, maxheight = 0;
            items.each(function(){
                $(this).removeAttr('style');
                height = $(this).outerHeight();
                if (height > maxheight) {
                    maxheight = height;
                }
            });
            items.each(function(){
                $(this).css({'height': maxheight});
            })
        })
    };
    $.fn.updateChat = function (options) {
        if (!this.length) {
            return this
        };
        var defaults = {
            get: 'before',
            time: 0,
            sort: 'ASC',
            handler: function(){},
            fail: function() {
                showNotify("H\u1ec7 th\u1ed1ng b\u1eadn", "warning")
            }
        };
        var settings = $.extend({}, defaults, options);
        var realHeight = $("#chatMessagesInner").prop('scrollHeight'),
            displayHeight = $("#chatMessagesInner").outerHeight(),
            lastChildHeight = $('#chatMessagesInner > div').last().outerHeight(),
            elementTop = $("#chatMessagesInner").scrollTop();
        var context = {
            isBottom: (realHeight - displayHeight - elementTop < 2 * lastChildHeight)
        };
        var render = function (element, res) {
            var html;
            $.each(res['messages'], function(i, v) {
                html = tmpl('chatTemplate', v);
                if ((settings.get == 'before' && settings.sort == 'DESC') || (settings.get == 'after' && settings.sort == 'ASC')) {
                    element.append(html)
                } else {
                    element.prepend(html)
                }
            })
        }
        return this.each(function(){
            var $this = $(this);
            Pace.ignore(function() {
                $.ajax({
                    type:  'GET',
                    url:   vDevs.BASE_URL + '/chat/ajax/load',
                    data: {
                        get:   settings.get,
                        time:  settings.time
                    }
                }).done(function(res){
                    render($this, res);
                    context.res = res;
                    context.element = $this;
                    settings.handler.apply(context)
                }).fail(function () {
                    settings.fail.apply(context)
                })
            })
        });
    };
    $.alert = function() {
        if (vDevs.browser == 'web') {
            audio.play();
        } else {
            navigator.vibrate(200)
        }
    };

    $.parseQueryString = function()
    {
        var a = {};
        if (location.search) {
            var b = location.search.substr(1).split('&');
            for(var c = 0, d = b.length; c<d; c++){
                if (b[c]) {
                    var e = b[c].split('=');
                    a[e[0]]=e[1]?decodeURIComponent(e[1]):'';
                }
            }
        }
        return a
    }
    $.doScroll = function() {
        var qData = $.parseQueryString();
        if (!!qData.st) {
            var ele = $('#' + qData.st);
            if (ele) {
                $('html, body').animate({
                    scrollTop: ele.offset().top - $('#header').height()
                }, "normal");
                return true
            }
        };
        return false
    }
})( jQuery );

$.extend(vDevs, {
    token: vDevs.user_id + '-' + $('body').attr('ses'),
    _start: function() {
        $(window).on('resize load', function(){
            $('.nav-response').fixNav();
            $('.carousel').fixCarousel();
        });
        $(document).on('swiperight', '#news', function() {
            $(this).carousel('prev');
        });
        $(document).on('swipeleft', '#news',function() {
            $(this).carousel('next');
        });
        if (vDevs.user_id) {
            $('#chatboxHeader').click(function() {
                $('#chatboxFixed').toggleClass('show');
            });
            if (vDevs.browser == 'web') {
                $('#quickChatForm').submit(function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    $this.find('input[type="submit"]').attr('disabled', 'disabled');
                    $.post(vDevs.BASE_URL + '/chat/ajax/send', $this.serialize()).done(function(data) {
                        if (data.success === true) {
                            $('#chatMessagesInner').updateChat({
                                get: 'after',
                                time: $('#chatMessagesInner').children().last().attr('data-time'),
                                sort: 'ASC',
                                handler: function() {
                                    if ($('#chatbox').length) {
                                        var html;
                                        $.each(this.res['messages'], function(i, v) {
                                            html = tmpl('chatTemplate', v);
                                            $('#chatbox').prepend(html)
                                        })
                                    }
                                    $("#chatMessages").nanoScroller({
                                        scrollTop: ($("#chatMessagesInner").prop('scrollHeight') - $("#chatMessagesInner").outerHeight()),
                                        preventPageScrolling: true
                                    })
                                }
                            })
                            $this.find('input[type="text"]').val('')
                        } else {
                            $.notify(data.message, 'warning')
                        }
                        $this.find('input[type="submit"]').removeAttr('disabled')
                    })
                });
                setTimeout(function() {
                    $('#chatMessagesInner').updateChat({
                        handler: function(){
                            $("#chatMessages").nanoScroller({scroll: 'bottom', preventPageScrolling: true })
                        }
                    })
                }, 1000)
            }
        }
    },
    chatroom: function() {
        $('#mainContent').on('click touch', '#chatLoadMore', function(){
            $('#chatbox').updateChat({
                get: 'before',
                time: $('#chatbox').children().last().attr('data-time'),
                sort: 'DESC',
                handler: function() {
                    if (this.res['messages'].length == 0) {
                        $('#chatLoadMore').hide();
                        showNotify('N\u1ed9i dung \u0111\u00e3 h\u1ebft!', 'warning')
                    }
                }
            });
        }).on('submit', '#chat', function(e) {
            e.preventDefault();
            var $this = $(this);
            $this.find('input[type="submit"]').attr('disabled', 'disabled');
            $.post(vDevs.BASE_URL + '/chat/ajax/send', $this.serialize()).done(function(data) {
                if (data.success === true) {
                    $('#chatbox').updateChat({
                        get: 'after',
                        time: $('#chatbox').children().first().attr('data-time'),
                        sort: 'DESC',
                        handler: function() {
                            if (vDevs.browser == 'web') {
                                var html;
                                $.each(this.res['messages'], function(i, v) {
                                    html = tmpl('chatTemplate', v);
                                    $('#chatMessagesInner').append(html);
                                });
                                if (this.isBottom) {
                                    $("#chatMessages").nanoScroller({
                                        scrollTop: ($("#chatMessagesInner").prop('scrollHeight') - $("#chatMessagesInner").outerHeight()),
                                        preventPageScrolling: true
                                    })
                                } else {
                                    $("#chatMessages").nanoScroller({ preventPageScrolling: true })
                                }
                            }
                        }
                    });
                    $this.find('textarea').val('');
                    $this.parent().find('.alert').remove();
                } else {
                    $this.parent().find('.alert').remove();
                    $this.after($('<div/>').addClass('alert alert-warning margin-top').text(data.message));
                }
                $this.find('input[type="submit"]').removeAttr('disabled');
            })
        })
    },
    chatroomUnload: function() {
        $('#mainContent').off('click touch', '#chatLoadMore').off('submit', '#chat');
    },
    account: function() {
        $(document).on('change', 'select[name=theme]', function(){
            var $this = $(this),
                currentTheme = $('option[selected]', $this).val(),
                newTheme = $('option:selected', $this).val();
            if (newTheme != currentTheme) {
                $this.closest('form').addClass('noPusher');
            } else {
                $this.closest('form').removeClass('noPusher');
            }
        })
    },
    accountUnload: function() {
        $(document).off('change', 'select[name=theme]')
    }
})
