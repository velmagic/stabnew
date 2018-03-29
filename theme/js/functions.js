(function ($) {
  'use strict';

  $(document).ready(function () {
     function menuPage()
     {
        var $trigger = $(".trigger-nav"),
            $menu=$('.trigger-victim');
        $trigger.click(function(){
            $(this).next($menu).slideToggle(10);
         });
        $(window).resize(function() {
            if($(window).width() > 992)
                $menu.removeAttr('style');
        });
     }
     menuPage();
  }); //end ready


    //фиксирование верхней навигации
    var scrollPosDetect = function() {
		//alert($(window).width());
      if (jQuery(window).scrollTop() >= 100) {
			jQuery(".top").addClass('fixed');
            if($(window).width() >= 992)
            { 
                $(".top").css({"position":"fixed"})// Убрать в продакшине
            }
            else
            {
                $(".top").css({"position":"relative"})// Убрать в продакшине
            }
      } else {
         jQuery(".top").removeClass('fixed');
          $(".top").css({"position":"fixed"})// Убрать в продакшине
      }
    };
   scrollPosDetect();
   jQuery(window).scroll(scrollPosDetect);

}(jQuery));

// Выравнивание по высоте блоков со стабилизаторами
function alignmentByHeight(classname) 
{
    var divs = $("div ."+classname);
    var max = 0;
    for(var i=0; i<divs.length; i++) {
        max = Math.max(max, $(divs[i]).height());
    }
    $(divs).css('min-height', 3+max+'px');
}