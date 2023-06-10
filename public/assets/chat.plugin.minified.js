! function() {
    var e = document.createElement("link");
    e.rel = "stylesheet", e.href = "https://support.softhostit.com/public/assets/floating-btn.css";
    var t = document.createElement("div");
    t.className = "floatingButtonWrap shadow", t.style.bottom = "90px", t.style.right = "-5px";
    var n = document.createElement("div");
    n.className = "floatingButtonInner";
    var a = document.createElement("a");
    a.href = "javascript:;", a.className = "floatingButton";
    var o = document.createElement("img");
    o.src = "https://support.softhostit.com/public/assets/chat-head.png", o.alt = "", a.appendChild(o);
    var c = document.createElement("div");
    c.className = "floatingChatBox";
    var s = document.createElement("div");
    s.className = "main-chatbox-container";
    var i = document.createElement("div");
    i.className = "chatbox-header";
    var l = document.createElement("span");
    l.textContent = "Live Chat Now", i.appendChild(l);
    var r = document.createElement("hr");
    var d = document.createElement("div");
    d.className = "chatbox-body";
    var p = document.createElement("p");
    p.className = "chatbox-greetings", p.innerHTML = '<span>Hello,</span> Thanks for visiting our website.';
    var h = document.createElement("p");
    h.className = "chatbox-introduction", h.innerHTML = 'Have you any question about <a href="/">' + document.location.host + "</a>?";
    var m = document.createElement("div");
    m.className = "chatbox-button-box";
    var g = document.createElement("a");
    g.className = "chatbox-chat-button", g.href = "https://support.softhostit.com/web?" + document.location.host, g.target = "_blank", g.textContent = "Chat With Us", m.appendChild(g), d.appendChild(p), d.appendChild(h), d.appendChild(m);
    var u = document.createElement("hr");
    var b = document.createElement("div");
    b.className = "chatbox-footer";
    var f = document.createElement("p");
    f.className = "footer-text", f.textContent = "We provide service from 10 am to 10 pm.", b.appendChild(f), s.appendChild(i), s.appendChild(r), s.appendChild(d), s.appendChild(u), s.appendChild(b), c.appendChild(s), n.appendChild(a), n.appendChild(c), document.head.appendChild(e), document.body.appendChild(t), t.appendChild(n)
}();

$(document).ready(function() {
    $('.floatingButton').on('click',
        function(e) {
            e.preventDefault();
            $(this).toggleClass('open');
            if ($(this).children('.fa').hasClass('fa-comments')) {
                $(this).children('.fa').removeClass('fa-comments');
                $(this).children('.fa').addClass('fa-close');
            } else if ($(this).children('.fa').hasClass('fa-close')) {
                $(this).children('.fa').removeClass('fa-close');
                $(this).children('.fa').addClass('fa-comments');
            }
            $('.floatingChatBox').stop().slideToggle();
        }
    );
    $(this).on('click', function(e) {

        var container = $(".floatingButton");
        // if the target of the click isn't the container nor a descendant of the container
        if (container.is(e.target)) {
            if (container.hasClass('open')) {
                container.removeClass('open');
            }
            if (container.children('.fa').hasClass('fa-close')) {
                container.children('.fa').removeClass('fa-close');
                container.children('.fa').addClass('fa-comments');
            }
            $('.floatingChatBox').hide();
        }
    });
});
