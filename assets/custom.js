/**
 * Created by iy2 on 5/3/2016.
 */

/** Affix */
/**
 $(document).ready(function () {
    $('#navbar').affix({
        offset: {
            top: $('header').height()
        },
    });
});
 */

/**
 $(document).ready(function () {
    $('#breadcrumb').affix({
        offset: {
          top: $('header').height()
        },

    });
});
 */
/** Scrool spy */

$(document).ready(function () {
    // Add scrollspy to <body>
    //$('body').scrollspy({target: ".navbar", offset: 50});
    $("#myNavbar a").on('click', function (event) {
        event.preventDefault();
        var hash = this.hash;
        $('html, body').animate({
            scrollTop: $(hash).offset().top
        }, 800, function () {
            window.location.hash = hash;
        });
    });
});

/** slide corousel **/
$(document).ready(function () {
    $('.carousel').carousel({
        interval: 5000
    })
});


/**Alert POP UP **/

$(document).ready(function () {
    $('.tanggal').datepicker({
        format: "dd-mm-yyyy",
        startView: 1
    });
});
$(document).ready(function () {

    $(".tahun").datepicker({
        autoclose: true,
        format: ' yyyy',
        viewMode: 'years',
        minViewMode: 'years',
        startDate: ' 2014',
        endDate: new Date(),
    });
});

/**
$(document).ready(function () {
    $("html").mouseover(function () {
        $("html").getNiceScroll().resize();
    });
});
 */