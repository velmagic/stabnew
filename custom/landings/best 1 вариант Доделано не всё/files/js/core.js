var popTop = "<div id='pop-up'><div class='pop-up-body'><i class='close' onclick='closePop();'></i>",
popBot = "</div><div class='pop-up-over' onclick='closePop();'></div></div>";

(function(){
	$('.fancybox').fancybox();
})();

$(document).ready(function(){
	googleMap();
});

function googleMap() {
	var latlng = new google.maps.LatLng(50.494265,30.458733);
	var settings = {
		zoom: 14,
		scrollwheel: false,
		center: latlng,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
		navigationControl: true,
		navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
		mapTypeId: google.maps.MapTypeId.ROADMAP};
	var map = new google.maps.Map(document.getElementById("google-map"), settings);

	var companyPos = new google.maps.LatLng(50.494265,30.458733);

	var companyMarker = new google.maps.Marker({
		position: companyPos,
		map: map,
		zIndex: 3});
};

function closePop(){
	$("#pop-up").remove();
}

function callMe(theme){
  var referer = $('.request-form').find("input[name='referer']").val();
  var page = $('.request-form').find("input[name='page']").val();
	if(theme==="order"){
		$("body").append(popTop+"<h3>Оставьте заявку</h3><form action='mail.php' data-theme='Заказ монтажа кондиционера'><input type='hidden' name='referer' value='"+referer+"' /><input type='hidden' name='page' value='"+page+"' /><div class='form-line'><input type='text' name='name' placeholder='Введите имя' /></div><div class='form-line'><input type='text' name='phone' placeholder='Введите телефон' /></div><div class='form-line'><a href='#' class='btn orange' onclick='formSubmit(this, \"pinp\"); return false;'>Оставить заявку</a></div></form>"+popBot);
	} else {
		$("body").append(popTop+"<h3>Оставьте заявку</h3><form action='mail.php' data-theme='Заказ обратного звонка'><input type='hidden' name='referer' value='"+referer+"' /><input type='hidden' name='page' value='"+page+"' /><div class='form-line'><input type='text' name='name' placeholder='Введите имя' /></div><div class='form-line'><input type='text' name='phone' placeholder='Введите телефон' /></div><div class='form-line'><a href='#' class='btn orange' onclick='formSubmit(this, \"pinp\"); return false;'>Заказать звонок</a></div></form>"+popBot);
	}
}

function orderMe(element){
	var themeEl = $(element).parents().get(0);
	var theme = $(themeEl).find("h4").text();
  var referer = $('.request-form').find("input[name='referer']").val();
  var page = $('.request-form').find("input[name='page']").val();
	$("body").append(popTop+"<h3>Оставьте заявку</h3><form action='mail.php' data-theme='"+theme+"'><div class='form-line'><input type='hidden' name='referer' value='"+referer+"' /><input type='hidden' name='referer' value='"+page+"' /><input type='text' name='name' placeholder='Введите имя' /></div><div class='form-line'><input type='text' name='phone' placeholder='Введите телефон' /></div><div class='form-line'><a href='#' class='btn orange' onclick='formSubmit(this, \"pinp\"); return false;'>Оставить заявку</a></div></form>"+popBot);
}

function formSubmit(element, pop){
	var form = $(element).parent().parent();
	var theme = form.attr("data-theme");
	var myname = form.find("input[name='name']").val();
	var myphone = form.find("input[name='phone']").val();
  var mypage = $('.request-form').find("input[name='page']").val();
  var myreferer = $('.request-form').find("input[name='referer']").val();
	if(myname!="" && myphone!=""){
		var url = form.attr("action");
		var posting = $.post( url, {
		    name: myname,
		    phone: myphone,
        page: mypage,
        referer: myreferer,
		    subject: myname+", "+myphone+", "+theme
		});
		posting.done(function(success) {
			if(typeof pop !== "undefined"){closePop();}
	       	$("body").append(popTop+"<h4>Ваша заявка отправленна</h4>"+popBot);
			setTimeout(closePop, 1500);
	    });
	    posting.fail(function(error) {
	    	if(typeof pop !== "undefined"){closePop();}
	        $("body").append(popTop+"<h4>Ошибка отправки. Попробуйте ещё раз</h4>"+popBot);
			setTimeout(closePop, 1500);
	    });
	}else{
		if(typeof pop !== "undefined"){closePop();}
		$("body").append(popTop+"<h4>Заполните все поля!</h4>"+popBot);
		setTimeout(closePop, 1500);
	}
}