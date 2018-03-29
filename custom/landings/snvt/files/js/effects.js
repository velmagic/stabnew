jQuery(function(){
//фиксирование верхней навигации
var scrollPosDetect = function() {
  if (jQuery(window).scrollTop() >= 100) {
   jQuery(".top").addClass('fixed');
  } else {
   jQuery(".top").removeClass('fixed');
  }
 };
 scrollPosDetect();
 jQuery(window).scroll(scrollPosDetect);

 //Активация слайдеров
  jQuery('.slider01, .slider02, .slider03, .slider04').slick({
    slidesToShow: 2,
    slidesToScroll: 2,
    autoplay: false,
    autoplaySpeed: 2000,
    arrows: true,
    dots:false,
      responsive: [
    {
      breakpoint: 1080,
      settings: {
        slidesToScroll: 2,
        slidesToShow: 2

      }
    },
    {
	      breakpoint: 810,
	      settings: {
          slidesToScroll: 1,
	        slidesToShow: 1
	      }
	    }
	  ]
  });

  jQuery('.slider05').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 2000,
    arrows: true,
    dots:false,
    fade: true,
      responsive: [
	    {
	      breakpoint: 810,
	      settings: {
	        centerMode: true
	      }
	    }
	  ]
  });

//таймер
  jQuery('#no-reflection01').county({ endDateTime: new Date('2016/10/30 1:00:00'), reflection: false, animation: 'scroll', theme: 'black' });
  jQuery('#no-reflection02').county({ endDateTime: new Date('2016/10/30 1:00:00'), reflection: false, animation: 'scroll', theme: 'black' });

//WOW-анимация
wow = new WOW(
    {
    mobile: false
  }
  )
  wow.init();

  jQuery('button.close').click(function(){
    $(".modal").removeClass('show');
  });
  
//Модаль при клике на товар

  jQuery('.stabil').click(function(){
    var stab = jQuery(this).attr('data-stab');
    $(".modal#"+stab).addClass("in").attr("aria-hidden", "false").addClass('show');
  });

//Модаль при наведении на товар
/*$('.stabil').hover(function(){
  var stab = jQuery(this).attr('data-stab');
  $(".modal#"+stab).addClass("in").attr("aria-hidden", "false").addClass('show');
  },function(){

  });*/
//Подсказка при наведении на товар
$('img.stabil').tooltip("hide");
});
	
