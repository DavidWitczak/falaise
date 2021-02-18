(function () {
  jQuery('#quiz-action').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let nb_questions = 0;
    let nb_reponse_ok = 0;

    jQuery('.quiz_question').each(function () {
      nb_questions++;
    });

    jQuery('.quiz_reponse').each(function (index) {
      let id_question = jQuery(this).attr('data-question');
      if (jQuery(this).is(':checked')) {
        if (jQuery(this).prop('checked')) {
          if (jQuery(this).val() === '1') {
            jQuery('.quiz-yes.' + id_question).removeClass('d-none');
            nb_reponse_ok++;
          } else if (jQuery(this).val() === '0') {
            jQuery('.quiz-nop.' + id_question).removeClass('d-none');
          }
        }
      }
    });

    jQuery('.quiz-label').each(function () {
      if(jQuery(this).attr('data-value') === '1'){
        jQuery(this).addClass('label-ok');
      }
    });

    let aTag;

    if (nb_questions === nb_reponse_ok){
      jQuery('.quiz-explication').removeClass('d-none');
      jQuery('#quiz-gagne').removeClass('d-none');
      aTag = jQuery("#quiz-gagne");
    }else {
      jQuery('#quiz-perdu').removeClass('d-none');
      aTag = jQuery("#quiz-perdu");
    }


    jQuery('html,body').animate({scrollTop: aTag.offset().top},'slow');
    jQuery(this).addClass('d-none');
    jQuery('#quiz-refresh').removeClass('d-none');

  });

  jQuery('#quiz-refresh').on('click', function () {
    window.scrollTo(0, 0);
    location.reload();
  });

})();
