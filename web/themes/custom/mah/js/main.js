(function () {
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

})();
