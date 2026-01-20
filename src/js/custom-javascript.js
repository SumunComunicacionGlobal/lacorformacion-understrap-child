// Add your custom JS here.

jQuery(document).ready(function($) {
	var body = $('body');
  var scrolled = false;
  var navbarClasses = $('#main-nav').attr('class');

  jQuery(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if (scroll >= 25) {
			body.addClass("scrolled");
      scrolled = true;
      // $('#main-nav').removeClass('navbar-dark');
      // $('#main-nav').addClass('navbar-light');
    } else {
			body.removeClass("scrolled");
      scrolled = false;
      // $('#main-nav').removeClass('navbar-light navbar-dark').addClass(navbarClasses);
    }

	   if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
	       body.addClass("near-bottom");
	   } else {
			body.removeClass("near-bottom");
	   }

	});

  $('#navbarNavDropdown').on('show.bs.collapse', function () {
    body.addClass('menu-open');
  });

  $('#navbarNavDropdown').on('hide.bs.collapse', function () {
    body.removeClass('menu-open');
  });

  $('#search-collapse').on('show.bs.collapse', function () {
    body.addClass('search-open');
  });

  $('#search-collapse').on('hide.bs.collapse', function () {
    body.removeClass('search-open');
  });

  $('.sub-menu-toggler').click(function(e) {
    e.preventDefault();
    $(this).parent().next().toggleClass('show');
    $(this).parent().parent().toggleClass('show');
  });

  $('.solonumeros').on('textInput', e => {
    var keyCode = e.originalEvent.data.charCodeAt(0);
    if (String.fromCharCode(keyCode).match(/[^0-9]/g))
        return false;  
  });

});


/* Carruseles */

// jQuery( '.slick-slider, .commentlist' ).slick({
jQuery( '.slick-slider' ).slick({
  dots: false,
  arrows: true,
  infinite: true,
  speed: 300,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true
});