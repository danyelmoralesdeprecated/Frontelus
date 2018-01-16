$(function () {
    NavScrollDown();
    function cover() {
        var $height = $(window).height();
        $('section.cover').css({
            'height': $height
        });
    }
    cover();
    $(window).resize(function () {
        cover();
    });
});
$(function () {
    $('html,body').click(function () {
        $('*.open').removeClass('open');
    });
    $('#topbar div.barspace form.topsearch').click(function (e) {
        $(this).toggleClass('open');
        e.stopPropagation();
    });
    $('#topbar div.barspace form.topsearch input').click(function (e) {
        e.stopPropagation();
    });
    $('#topbar div.barspace div.notifications').click(function (e) {
        $(this).toggleClass('open');
        e.stopPropagation();
    });
    $('#topbar div.barspace div.notifications div.dropdown').click(function (e) {
        e.stopPropagation();
    });
});

// SmoothScroll v0.9.9
// Licensed under the terms of the MIT license.

// People involved
// - Balazs Galambosi: maintainer (CHANGELOG.txt)
// - Patrick Brunner (patrickb1991@gmail.com)
// - Michael Herf: ssc_pulse Algorithm
function ssc_init() {
    if (!document.body)
        return;
    var e = document.body;
    var t = document.documentElement;
    var n = window.innerHeight;
    var r = e.scrollHeight;
    ssc_root = document.compatMode.indexOf("CSS") >= 0 ? t : e;
    ssc_activeElement = e;
    ssc_initdone = true;
    if (top != self) {
        ssc_frame = true
    } else if (r > n && (e.offsetHeight <= n || t.offsetHeight <= n)) {
        ssc_root.style.height = "auto";
        if (ssc_root.offsetHeight <= n) {
            var i = document.createElement("div");
            i.style.clear = "both";
            e.appendChild(i)
        }
    }
    if (!ssc_fixedback) {
        e.style.backgroundAttachment = "scroll";
        t.style.backgroundAttachment = "scroll"
    }
    if (ssc_keyboardsupport) {
        ssc_addEvent("keydown", ssc_keydown)
    }
}
function ssc_scrollArray(e, t, n, r) {
    r || (r = 1e3);
    ssc_directionCheck(t, n);
    ssc_que.push({x: t, y: n, lastX: t < 0 ? .99 : -.99, lastY: n < 0 ? .99 : -.99, start: +(new Date)});
    if (ssc_pending) {
        return
    }
    var i = function () {
        var s = +(new Date);
        var o = 0;
        var u = 0;
        for (var a = 0; a < ssc_que.length; a++) {
            var f = ssc_que[a];
            var l = s - f.start;
            var c = l >= ssc_animtime;
            var h = c ? 1 : l / ssc_animtime;
            if (ssc_pulseAlgorithm) {
                h = ssc_pulse(h)
            }
            var p = f.x * h - f.lastX >> 0;
            var d = f.y * h - f.lastY >> 0;
            o += p;
            u += d;
            f.lastX += p;
            f.lastY += d;
            if (c) {
                ssc_que.splice(a, 1);
                a--
            }
        }
        if (t) {
            var v = e.scrollLeft;
            e.scrollLeft += o;
            if (o && e.scrollLeft === v) {
                t = 0
            }
        }
        if (n) {
            var m = e.scrollTop;
            e.scrollTop += u;
            if (u && e.scrollTop === m) {
                n = 0
            }
        }
        if (!t && !n) {
            ssc_que = []
        }
        if (ssc_que.length) {
            setTimeout(i, r / ssc_framerate + 1)
        } else {
            ssc_pending = false
        }
    };
    setTimeout(i, 0);
    ssc_pending = true
}
function ssc_wheel(e) {
    if (!ssc_initdone) {
        ssc_init()
    }
    var t = e.target;
    var n = ssc_overflowingAncestor(t);
    if (!n || e.defaultPrevented || ssc_isNodeName(ssc_activeElement, "embed") || ssc_isNodeName(t, "embed") && /\.pdf/i.test(t.src)) {
        return true
    }
    var r = e.wheelDeltaX || 0;
    var i = e.wheelDeltaY || 0;
    if (!r && !i) {
        i = e.wheelDelta || 0
    }
    if (Math.abs(r) > 1.2) {
        r *= ssc_stepsize / 120
    }
    if (Math.abs(i) > 1.2) {
        i *= ssc_stepsize / 120
    }
    ssc_scrollArray(n, -r, -i);
    e.preventDefault()
}
function ssc_keydown(e) {
    var t = e.target;
    var n = e.ctrlKey || e.altKey || e.metaKey;
    if (/input|textarea|embed/i.test(t.nodeName) || t.isContentEditable || e.defaultPrevented || n) {
        return true
    }
    if (ssc_isNodeName(t, "button") && e.keyCode === ssc_key.spacebar) {
        return true
    }
    var r, i = 0, s = 0;
    var o = ssc_overflowingAncestor(ssc_activeElement);
    var u = o.clientHeight;
    if (o == document.body) {
        u = window.innerHeight
    }
    switch (e.keyCode) {
        case ssc_key.up:
            s = -ssc_arrowscroll;
            break;
        case ssc_key.down:
            s = ssc_arrowscroll;
            break;
        case ssc_key.spacebar:
            r = e.shiftKey ? 1 : -1;
            s = -r * u * .9;
            break;
        case ssc_key.pageup:
            s = -u * .9;
            break;
        case ssc_key.pagedown:
            s = u * .9;
            break;
        case ssc_key.home:
            s = -o.scrollTop;
            break;
        case ssc_key.end:
            var a = o.scrollHeight - o.scrollTop - u;
            s = a > 0 ? a + 10 : 0;
            break;
        case ssc_key.left:
            i = -ssc_arrowscroll;
            break;
        case ssc_key.right:
            i = ssc_arrowscroll;
            break;
        default:
            return true
    }
    ssc_scrollArray(o, i, s);
    e.preventDefault()
}
function ssc_mousedown(e) {
    ssc_activeElement = e.target
}
function ssc_setCache(e, t) {
    for (var n = e.length; n--; )
        ssc_cache[ssc_uniqueID(e[n])] = t;
    return t
}
function ssc_overflowingAncestor(e) {
    var t = [];
    var n = ssc_root.scrollHeight;
    do {
        var r = ssc_cache[ssc_uniqueID(e)];
        if (r) {
            return ssc_setCache(t, r)
        }
        t.push(e);
        if (n === e.scrollHeight) {
            if (!ssc_frame || ssc_root.clientHeight + 10 < n) {
                return ssc_setCache(t, document.body)
            }
        } else if (e.clientHeight + 10 < e.scrollHeight) {
            overflow = getComputedStyle(e, "").getPropertyValue("overflow");
            if (overflow === "scroll" || overflow === "auto") {
                return ssc_setCache(t, e)
            }
        }
    } while (e = e.parentNode)
}
function ssc_addEvent(e, t, n) {
    window.addEventListener(e, t, n || false)
}
function ssc_removeEvent(e, t, n) {
    window.removeEventListener(e, t, n || false)
}
function ssc_isNodeName(e, t) {
    return e.nodeName.toLowerCase() === t.toLowerCase()
}
function ssc_directionCheck(e, t) {
    e = e > 0 ? 1 : -1;
    t = t > 0 ? 1 : -1;
    if (ssc_direction.x !== e || ssc_direction.y !== t) {
        ssc_direction.x = e;
        ssc_direction.y = t;
        ssc_que = []
    }
}
function ssc_pulse_(e) {
    var t, n, r;
    e = e * ssc_pulseScale;
    if (e < 1) {
        t = e - (1 - Math.exp(-e))
    } else {
        n = Math.exp(-1);
        e -= 1;
        r = 1 - Math.exp(-e);
        t = n + r * (1 - n)
    }
    return t * ssc_pulseNormalize
}
function ssc_pulse(e) {
    if (e >= 1)
        return 1;
    if (e <= 0)
        return 0;
    if (ssc_pulseNormalize == 1) {
        ssc_pulseNormalize /= ssc_pulse_(1)
    }
    return ssc_pulse_(e)
}
var ssc_framerate = 250;
var ssc_animtime = 900;
var ssc_stepsize = 150;
var ssc_pulseAlgorithm = true;
var ssc_pulseScale = 6;
var ssc_pulseNormalize = 1;
var ssc_keyboardsupport = true;
var ssc_arrowscroll = 50;
var ssc_frame = false;
var ssc_direction = {x: 0, y: 0};
var ssc_initdone = false;
var ssc_fixedback = true;
var ssc_root = document.documentElement;
var ssc_activeElement;
var ssc_key = {left: 37, up: 38, right: 39, down: 40, spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36};
var ssc_que = [];
var ssc_pending = false;
var ssc_cache = {};
setInterval(function () {
    ssc_cache = {}
}, 10 * 1e3);
var ssc_uniqueID = function () {
    var e = 0;
    return function (t) {
        return t.ssc_uniqueID || (t.ssc_uniqueID = e++)
    }
}();
var ischrome = /chrome/.test(navigator.userAgent.toLowerCase());
if (ischrome) {
    ssc_addEvent("mousedown", ssc_mousedown);
    ssc_addEvent("mousewheel", ssc_wheel);
    ssc_addEvent("load", ssc_init)
}

/*
 Plugin: jQuery Parallax
 Version 1.1.3
 Author: Ian Lunn
 Twitter: @IanLunn
 Author URL: http://www.ianlunn.co.uk/
 Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/
 
 Dual licensed under the MIT and GPL licenses:
 http://www.opensource.org/licenses/mit-license.php
 http://www.gnu.org/licenses/gpl.html
 */
(function ($) {
    var $window = $(window);
    var windowHeight = $window.height();
    $window.resize(function () {
        windowHeight = $window.height()
    });
    $.fn.parallax = function (xpos, speedFactor, outerHeight) {
        var $this = $(this);
        var getHeight;
        var firstTop;
        var paddingTop = 0;
        $this.each(function () {
            firstTop = $this.offset().top
        });
        if (outerHeight) {
            getHeight = function (jqo) {
                return jqo.outerHeight(true)
            }
        } else {
            getHeight = function (jqo) {
                return jqo.height()
            }
        }
        if (arguments.length < 1 || xpos === null)
            xpos = "50%";
        if (arguments.length < 2 || speedFactor === null)
            speedFactor = 0.1;
        if (arguments.length < 3 || outerHeight === null)
            outerHeight = true;
        function update() {
            var pos = $window.scrollTop();
            $this.each(function () {
                var $element = $(this);
                var top = $element.offset().top;
                var height = getHeight($element);
                if (top + height < pos || top > pos + windowHeight) {
                    return
                }
                $this.css('backgroundPosition', xpos + " " + Math.round((firstTop - pos) * speedFactor) + "px")
            })
        }
        $window.bind('scroll', update).resize(update);
        update()
    }
})(jQuery);
$('.bg-parallax').parallax("50%", 0.4);
$('.scroll-btn').on('click', function () {
    var numpos = 0;
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
            || location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            $('html,body').animate({scrollTop: target.offset().top - numpos}, 1000);
            return false;
        }
    }
});