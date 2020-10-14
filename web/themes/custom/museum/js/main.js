(function () {

  jQuery('[data-toggle="tooltip"]').tooltip();

  jQuery(".fancybox").fancybox({
    caption: function (instance, item) {
      return jQuery(this).find('figcaption').html();
    }
  });

  jQuery('.thumb_diapo').on('click', function () {
    const type = jQuery(this).attr('data-type');
    const url_full = jQuery(this).attr('data-url');

    if (type == 'image') {
      var html_string = '<img class="w-100" title="" src="' + url_full + '">'
    } else if (type == 'video') {
      var html_string =
        '<div class="embed-responsive embed-responsive-16by9">' +
        '<iframe class="embed-responsive-item" src="' + url_full + '" allowfullscreen></iframe>' +
        '</div>';
    }
    jQuery('.media-full').html(html_string);
  });

  jQuery(window).on("load", function () {
    jQuery('.masonry').masonry({
      itemSelector: '.grid-item',
      columnWidth: 260,
      gutter: 20,
      fitWidth: true
    });
  });

  jQuery('.diapo_slick:nth-child(1)').remove();

  jQuery('.carousel-slick').slick({
    prevArrow: "<img class='a-left control-c prev slick-prev' src='/themes/custom/museum/images/prev.png'>",
    nextArrow: "<img class='a-right control-c next slick-next' src='/themes/custom/museum/images/next.png'>",
    infinite: false,
    slidesToScroll: 1,
    centerMode: false,
    responsive: [
      {
        breakpoint: 2500,
        settings: {
          slidesToShow: 4,
        }
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 550,
        settings: {
          slidesToShow: 2
        }
      }
    ]
  });

  //bloc accordeon
  jQuery('.bloc_close').next().css({'display': 'none'});

  jQuery('.bloc_close, .bloc_open').on('click', function () {
    if (jQuery(this).next().is(':hidden')) {
      jQuery(this).removeClass('bloc_close');
      jQuery(this).addClass('bloc_open');
      jQuery(this).next().css({'display': 'block'});
    } else {
      jQuery(this).next().css({'display': 'none'});
      jQuery(this).removeClass('bloc_open');
      jQuery(this).addClass('bloc_close');
    }
  });

  jQuery('.object-filter').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    jQuery('.object-filter').removeClass('actif');
    jQuery('.object-filter-all').removeClass('actif');
    jQuery(this).addClass('actif');

    jQuery('.grid-object').css({'display': 'none'});
    // jQuery('.grid-object').hide(500);

    var filter = jQuery(this).attr('data-tid');

    jQuery('.' + filter).css({'display': 'block'});
    // jQuery('.' + filter).show(500);

    jQuery('.masonry').masonry({
      itemSelector: '.grid-item',
      columnWidth: 260,
      gutter: 20,
      fitWidth: true
    });
  });

  jQuery('.object-filter-all').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    jQuery('.object-filter').removeClass('actif');
    jQuery(this).addClass('actif');

    jQuery('.grid-object').css({'display': 'block'});

    jQuery('.masonry').masonry({
      itemSelector: '.grid-item',
      columnWidth: 260,
      gutter: 20,
      fitWidth: true
    });
  });

  //Menu XL
  jQuery('html').click(function () {
    if (jQuery('.sub-menu').is(":visible")) {
      jQuery('.sub-menu').css({'display': 'none'});
      jQuery('.item-menu').removeClass('active');
    }
  });

  jQuery('.sub-menu').on('click', function (e) {
    e.stopPropagation();
  });

  jQuery('.nav_lvl_1').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var target = '';

    if (window.matchMedia("(min-width: 992px)").matches) {
      target = jQuery(this).attr('data-menu');

    } else {
      target = jQuery(this).attr('data-menu-resp');
    }

    jQuery('.item-menu').removeClass('active');

    if (jQuery('#' + target).is(':hidden')) {
      jQuery('.sub-menu').css({'display': 'none'});
      jQuery('#' + target).css({'display': 'block'});
      jQuery(this).parent().addClass('active');
    } else {
      jQuery('#' + target).css({'display': 'none'});
    }
  });

  jQuery('.burger').on('click', function (e) {
    jQuery(this).next().toggle();
  });

  //secret aventure
  jQuery('.secret').keyup(function () {
    jQuery(this).next().focus();
  });

  jQuery('#secret_submit').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let reponse = '';

    jQuery('.secret').each(function (index) {
      reponse += jQuery(this).val();
    });

    if (reponse === 'apiculture') {
      jQuery('#secret_modal').modal();
      jQuery('#secret_modal_body').html('<p>Bravo ! Tu peux acceder à l\'espace secret :<br/><br/>' +
        '<a class="btn btn-primary" href="/espace-secret">Espace secret</a> </p>')
    } else {
      jQuery('#secret_modal').modal();
      jQuery('#secret_modal_body').html('<p>Code incorrect !</p>')
      jQuery('.secret').val('');
    }
  })

})();
