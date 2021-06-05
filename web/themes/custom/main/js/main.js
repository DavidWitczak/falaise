(function () {

  jQuery('[data-toggle="tooltip"]').tooltip({});

  document.getElementById('social-reduce').style.display = 'none';
window.onscroll = function (){
  if(document.documentElement.scrollTop > 80){
    document.getElementById('social-bar').style.display = 'none';
    document.getElementById('logo').style.width = '70%';
    document.getElementById('col-menu').classList.remove('offset-lg-1');
    document.getElementById('row-menu').classList.remove('my-3');

    if (window.matchMedia("(min-width: 992px)").matches) {
      document.getElementById('social-reduce').style.display = 'flex';

    } else {
      document.getElementById('social-reduce').style.display = 'none';
    }
  }else{
    document.getElementById('social-bar').style.display = 'flex';
    document.getElementById('logo').style.width = '100%';
    document.getElementById('col-menu').classList.add('offset-lg-1');
    document.getElementById('row-menu').classList.add('my-3');
    document.getElementById('social-reduce').style.display = 'none';
  }

}

//bloc accordeon
jQuery('.bloc_close').next().css({'display': 'none'});

jQuery('.bloc_close, .bloc_open').on('click', function () {
  if (jQuery(this).next().is(':hidden')) {
    jQuery(this).removeClass('bloc_close');
    jQuery(this).addClass('bloc_open');
    jQuery(this).next().slideDown(300);
  } else {
    jQuery(this).next().slideUp(300);
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

    var filter = jQuery(this).attr('data-tid');

    jQuery('.' + filter).css({'display': 'block'});
  });

  jQuery('.object-filter-all').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    jQuery('.object-filter').removeClass('actif');
    jQuery(this).addClass('actif');

    jQuery('.grid-object').css({'display': 'block'});

  });

  let only_once = 'no';
  jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() >= 160 && only_once == 'no') {        // If page is scrolled more than 50px
      jQuery('#return-to-top').fadeIn(100);
      only_once = 'yes';// Fade in the arrow
    }
    only_once = 'no';
    if (jQuery(this).scrollTop() < 160 && only_once == 'no') {
      jQuery('#return-to-top').fadeOut(100);   // Else fade out the arrow
    }
  });
  jQuery('#return-to-top').click(function () {    // When arrow is clicked
    jQuery('body,html').animate({
      scrollTop: 0                       // Scroll to top of body
    }, 500);
  });

})();
