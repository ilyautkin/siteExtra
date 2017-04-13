$(document).ready(function() {
    // Fix image margins in content
    $("#content img, .galleryouter img").each(function() {
        var marginLable = '';
        if ($(this).css("float") != 'none') {
            marginLable = "margin-" + $(this).css("float");
        }
        var imgStyle = {
            "display": "inline-block",
            "vertical-align": "top",
            "margin-right": "10px",
            "margin-left": "10px",
            "margin-bottom": "10px",
            "background": "#fff",
            "padding": "5px",
            "border": "1px solid #d0dae3"
        };
        if (marginLable) {
            imgStyle[marginLable] = 0;
        } else {
            imgStyle["margin-left"] = 0;
        }
        $(this).css(imgStyle);

    });
});

// FancyBox initialization
$(".fancybox2").fancybox({
    padding: 0,

    minWidth: 100,
    minHeight: 100,

    maxWidth: 800,
    maxHeight: 600,

    autoPlay: false,
    playSpeed: 3000,

    openEffect: "elastic",
    openSpeed: 150,

    closeEffect: "elastic",
    closeSpeed: 150,

    closeClick: true,
    titleShow: false,
    loop: false,
    title: "",

    helpers: {
        overlay: {
            closeClick: true,
            speedOut: 200,
            showEarly: true,
            css: {},
            locked: true
        }
    }
});
