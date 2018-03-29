function verify_form(selector) {
    $(selector).validate({
        showErrors: function() {},
        ignore: ":hidden",
        invalidHandler: function(form, validator) {
            if (validator.numberOfInvalids() > 0) {
                alert(validator.errorList[0].message);
                $(validator.invalidElements()[0]).focus();
            }
        },
        rules: {
            lname: {
                required: true,
                minlength: 2
            },
            fname: {
                required: true,
                minlength: 2
            },
            mname: {
                required: true,
                minlength: 2
            },
            fiz_lname: {
                required: true,
                minlength: 2
            },
            fiz_fname: {
                required: true,
                minlength: 2
            },
            fiz_mname: {
                required: true,
                minlength: 2
            },
            fiz_address: {
                required: true,
                minlength: 2
            },
            email: {
                required: true,
                email: true
            },
            wmid: {
                required: true,
                number: true
            },
            yur_payer: {
                required: true,
                minlength: 2
            },
            yur_address: {
                required: true,
                minlength: 2
            },
            yur_inn: {
                required: true,
                number: true
            },
            yur_kpp: {
                required: true,
                number: true
            },
            yur_rs: {
                required: true,
                number: true
            },
            yur_bank: {
                required: true,
                minlength: 2
            },
            yur_ks: {
                required: true,
                number: true
            },
            yur_bik: {
                required: true,
                number: true
            },
            phone: {
                required: true,
                minlength: 2
            },
            address: {
                required: true,
                minlength: 2
            },
            delivery_city: {
                required: true,
                incorrect: true
            }
        },
        messages: {
            lname: {
                required: 'Необходимо указать фамилию получателя.',
                minlength: 'Фамилия не должна быть короче 2-х символов'
            },
            fname: {
                required: 'Необходимо указать имя получателя.',
                minlength: 'Имя не должно быть короче 2-х символов'
            },
            mname: {
                required:'Необходимо указать отчество получателя.',
                minlength: 'Отчество не должна быть короче 2-х символов'
            },
            fiz_lname: {
                required: 'Необходимо указать фамилию плательщика.',
                minlength: 'Фамилия не должна быть короче 2-х символов'
            },
            fiz_fname: {
                required: 'Необходимо указать имя плательщика.',
                minlength: 'Имя не должно быть короче 2-х символов'
            },
            fiz_mname: {
                required: 'Необходимо указать отчество плательщика.',
                minlength: 'Отчество не должна быть короче 2-х символов'
            },
            fiz_address: {
                required: 'Необходимо указать адрес прописки плательщика.',
                minlength: 'Адрес не должен быть короче 2-х символов'
            },
            email: {
                required: 'Необходимо указать корректный email-адрес.  На него будет выслан счет.',
                email: 'Адрес электронной почты задан не корректно.'
            },
            wmid: {
                required: 'Необходимо указать WMID Вашего WebMoney кошелька.',
                number: 'WMID WebMoney кошелька задан не корректно.'
            },
            yur_payer: {
                required: 'Необходимо указать наименование организации заказчика.',
                minlength: 'Отчество не должно быть короче 2-х символов'
            },
            yur_address: {
                required: 'Необходимо указать юридический адрес организации заказчика.',
                minlength: 'Адрес не должен быть короче 2-х символов'
            },
            yur_inn: {
                required: 'Необходимо указать ИНН.',
                number: 'ИНН задан не корректно.'
            },
            yur_kpp: {
                required: 'Необходимо указать КПП.',
                number: 'КПП задан не корректно.'
            },
            yur_rs: {
                required: 'Необходимо указать расчётный счёт.',
                number: 'Расчётный счёт задан не корректно.'
            },
            yur_bank: {
                required: 'Необходимо указать наименование отделения банка заказчика.',
                minlength: 'Наименовние банка не должен быть короче 2-х символов'
            },
            yur_ks: {
                required: 'Необходимо указать корреспондентский счёт.',
                number: 'Корреспондентский счёт задан не корректно.'
            },
            yur_bik: {
                required: 'Необходимо БИК банка.',
                number: 'БИК банка задан не корректно.'
            },
            phone: {
                required: 'Необходимо указать контактный телефон.',
                minlength: 'Телефон не должен быть короче 2-х символов'
            },
            address: {
                required: 'Необходимо указать адрес доставки.',
                minlength: 'Адрес не должен быть короче 2-х символов'
            },
            delivery_city: {
                required: 'Необходимо указать город доставки.'
            }
        }
    });
}

var loaded = true;

$(document).ready(function(){
	$(':submit[name=cart_submit]').hide();
	$('input.cart_product_qty').numeric(false, null);
	$('select[name=delivery]').bind('change', onDeliveryChange);
	$('input.cart_product_qty').bind('keyup', onKeyup);
	$('input.cart_product_qty').bind('paste', function() { setTimeout(updateCart, 100) } );
	$('.remove_product').bind('click', removeProduct);
	
	$('input[name=customer]').bind('click', onCustomerClick);
	$('input[name=payment]').bind('click', onPaymentClick);

    $( "select[name=delivery]" ).change();

    $('.trans_comp').on('change', 'input[name="transport_company"]', function() {
        updateCart();
    });

    // сохраненяем выбор ТК при обновлении.
    $('.trans_comp').on('change', 'input[name="transport_company"]', function() {
        $('input[name="transport_company_val"]').val($(this).val());
    });

    $('input[name=customer][value=1]').click();

    jQuery.validator.addMethod("incorrect", function(value, element) {
        return this.optional(element) || !($('#transCompResult').is(':visible'));
    }, "Укажите другой город доставки.");

    $('form[name=order]').on('submit', function(e) {

        if ($('select[name="delivery"] option:selected').val() == 2 && !$('input[name="transport_company"]').is(':checked') && $('#transCompResult').is(':hidden') && $('#transComp').hasClass('calculated')) {
            alert('Необходимо указать транспортную компанию.');
            $('input[name="transport_company"]:first').focus();
            e.preventDefault();
            return false;
        } else {
            verify_form('form[name=order]');
            var valid = $('form[name=order]').valid();
            if (valid == true) {

                if ($('input[name=delivery]').length) {
                    $('input[name=delivery]').val($('select[name=delivery]').val());
                } else {
                    $('form[name=order]').append("<input type='hidden' name='delivery' id='delivery' value='" + $('select[name=delivery]').val() + "'/>");
                }

                disableForm($('.order_table'));
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        }

    });

    $('#transCompResult').text('');

});

/////////////////////////////////////////////////////////////////////////////////////////////
// Дизеблим форму
function disableForm(form) {
    $(form).find("input").attr('readonly', 'readonly');
    $(form).find("select").attr('disabled', 'disabled');
    $(form).find("textarea").attr('readonly', 'readonly');
    $(form).find("button[type='submit']").hide();
    $('#submit-loader').show();
    loaded = false;
}

/////////////////////////////////////////////////////////////////////////////////////////////
//$(document).ready(function(){
//(function($) {
//	$.fn.myEnable = function(value) {
//		return this.each(function(){
//			if ( $(this).is('input:radio') ) {
//				if ( $(this).next().is('label') ) {
//					if ((value) && ($(this).next().data('LastColor') != 0)) {
//						$(this).next().css("color", $(this).next().data('LastColor'));
//					} else {
//						$(this).next().data('LastColor',  $(this).next().css("color"));
//						$(this).next().css("color", "gray");
//					}
//				}
//                if (!value) {
//                    return $(this).attr("disabled", "disabled");
//                } else {
//                    return $(this).removeAttr("disabled");
//                }
////				return $(this).attr("disabled", value ? '' : true);
//			}
//		});
//	}
//})(jQuery);
//})

(function($) {
    $.fn.myEnable = function(value) {
        return this.each(function(){
            if ( $(this).is('input:radio') ) {
                if ( $(this).next().is('label') ) {
                    if (value) {
                        $(this).next().css("color", "#717171");
                    } else {
                        $(this).next().css("color", "#BABABA");
                    }
                }
                if (!value) {
                    return $(this).attr("disabled", "disabled");
                } else {
                    return $(this).removeAttr("disabled");
                }
//				return $(this).attr("disabled", value ? '' : true);
            }
        });
    }
})(jQuery);

/////////////////////////////////////////////////////////////////////////////////////////////
function onKeyup() 
{
	if ($(this).data('old_value') != $(this).val()) 
	{
		updateCart();
		$(this).data('old_value', $(this).val());
	}

    if ($('#delivery_city').is(":visible") && $('#transCompResult').is(':hidden')) {
        getDeliveryCost($('#delivery_city').val());
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////
function updateCart()
{
    var total_cost = 0;
    var total_qty = 0;
    var total_weight = 0;
    var total_products = 0;
    $('.cart_product').each( function(){
        var qty = $(this).find('input.cart_product_qty').val();
        qty = qty.replace(/[\D]/g, '');
        $(this).find('input.cart_product_qty').val(qty);
        var price = $(this).find('.cart_product_price').text();
        var weight = $(this).find('.cart_product_weight').attr('weight');
        $(this).find('.cart_product_cost').text( price*qty );
        $(this).find('.cart_product_weight').text( (weight*qty).toFixed(1) )
        total_cost += price*qty;
        total_qty += (qty.length > 0) ? parseInt(qty) : 0;
        total_weight +=  parseFloat(weight*qty);
        total_products++;
    } );
    if (total_products == 0) {
        window.location='cart.php';
        exit;
    }

    var transp_delivery_cost = $(this).closest('.order-amount').find('strong').text();

    var delivery_free_limit = $('select[name=delivery] option:selected').attr('free_limit');
    var delivery_cost = (delivery_free_limit > total_cost) ? $('select[name=delivery] option:selected').attr('price') : 0;

    if (transp_delivery_cost) {
        total_cost += parseInt(delivery_cost);
        $('.transp_delivery').show();
        $('#delivery_cost').text(delivery_cost);
        $('#transp_delivery_cost').text(transp_delivery_cost);
        $('.cart_total_cost').text(total_cost + '+' + transp_delivery_cost);
    } else if ($('#transComp').is(':visible') && $('input[name=transport_company]').is(':checked') && !$('#transCompResult').is(':visible')) {
        var transp_delivery_cost = $('input[name=transport_company]:checked').closest('.order-amount').find('strong').text();
        total_cost += parseInt(delivery_cost);
        $('.transp_delivery').show();
        $('#delivery_cost').text(delivery_cost);
        $('#transp_delivery_cost').text(transp_delivery_cost);
        $('.cart_total_cost').text(total_cost + ' + ' + transp_delivery_cost);
    } else {
        total_cost += parseInt(delivery_cost);
        if ($('.transp_delivery').is(':visible')) {
            $('#transp_delivery_cost').text('');
            $('#delivery_cost').text(delivery_cost);
        } else {
            $('#delivery_cost').text(delivery_cost);
        }
        $('.cart_total_cost').text(total_cost);
    }

//    $('.cart_total_cost').text(total_cost);
    $('.cart_total_qty').text(total_qty);
    $('.cart_total_weight').text(total_weight.toFixed(1));

    $.ajax({
        type: 'POST',
        url: $('form[name=cart]').attr('action'),
        dataType: 'html',
        data: $('form[name=cart]').serializeArray()
    });
}

/////////////////////////////////////////////////////////////////////////////////////////////
function removeProduct(eventObj)
{
    if (loaded == false)
        return false;

	var product_name = $(this).parentsUntil('.cart_product').siblings('.cart_product_name').text();
	if ( confirm("Удалить из вашего заказа \n" + product_name + "?") )
	{
        return true;
	}
    return false;
}

/////////////////////////////////////////////////////////////////////////////////////////////

function onCustomerClick()
{
    if ($(this).val() == 1) {
        $('input[name=payment][value=1]').myEnable(true);
        $('input[name=payment][value=3]').myEnable(true);
        $('input[name=payment][value=4]').myEnable(true);
        $('input[name=payment]:checked').click();
        if ($('#delivery_city').is(':visible')) {
            $('input[name=payment][value=1]').myEnable(false);
            $('input[name=payment][value=3]').myEnable(false);
            $('input[name=payment][value=2]').click();
        }
    }
    if ($(this).val() == 2) {
        $('input[name=payment][value=1]').myEnable(false);
        $('input[name=payment][value=3]').myEnable(false);
        $('input[name=payment][value=4]').myEnable(false);
        $('input[name=payment][value=2]').click();
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////
function onPaymentClick()
{
	$('.person').show().find('input').show();
	if ($(this).val() == 1) {
		$('.noncash').hide().find('input').hide();
	}
	if ($(this).val() == 2) {
		$('.noncash').hide().find('input').hide();
		if ($('input[name=customer]:checked').val() == 2) {
			$('.noncash_title, .yur').show().find('input').show();
		} else {
			$('.noncash_title, .fiz').show().find('input').show();
			$('.person').hide().find('input').hide();
		}
	}
    if ($(this).val() == 3) {
        $('.noncash').hide().find('input').hide();
    }
	if ($(this).val() == 4) {
		$('.yur, .fiz').hide().find('input').hide();
		$('.noncash_title, .wm').show().find('input').show();
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////
function onDeliveryChange()
{
    if ($(this).val() == 1) {
        $('#transComp').hide().find('input').hide();
        $('.delivery_address').hide().find('input').hide();
        $('.delivery_city').hide().find('input').hide();
        $('.transp_delivery').hide();
        $('input[name=customer]:checked').click();

    } else if ($(this).val() == 2) {
        $('.delivery_address').hide().find('input').hide();
        $('.delivery_city').show().find('input').show();
        $('.transp_delivery').show();
        if ($('#transComp').hasClass('calculated')) {
            $('#transComp').show().find('input').show();
        }
        if ($('#transCompResult').text() !== '') {
            $('#transComp').show();
        }

        if ($.trim($("#delivery_city").val()) == '') {
            $('#transComp').show();
            $('#transCompResult').show();
            $('#transCompResult').text('Не указан город доставки');
        }

        $('input[name=customer]:checked').click();
    } else {
        $('.delivery_city').hide().find('input').hide();
        $('#transComp').hide().find('input').hide();
        $('.delivery_address').show().find('input').show();
        $('.transp_delivery').hide();
        $('input[name=customer]:checked').click();
    }
    updateCart();
}

/////////////////////////////////////////////////////////////////////////////////////////////
// Функция вычисления стоимости доставки
function getDeliveryCost(city, callback) {
    if (city!== '') {
        $('#transComp').show();
        $('.trans_comp').html('');
        $('#transp_delivery_cost').text('');
        $('#transCompResult').hide();
        $('#city-loader').show();
        $.ajax({
            type: 'POST',
            url: 'ajax-calc-delivery.php',
            dataType: 'json',
            cache: false,
            timeout: 4000,
            data: {
                'products_weight': $('#total_weight').text(),
                'city': city
            },
            success: function(data) {
                if (data.result == 1) {
                    $('#transCompResult').hide();
                    $('#transCompResult').text('');
                    $('.trans_comp').html('');
                    $.each(data.data, function(i,val) {
                        if ($(this) !== undefined) {
//                          -----------------------

                            if(val.id !== undefined) {
                                $('.trans_comp').append(
                                    '<tr class="order-amount" id="company' + i + '">' +
                                        '<td>' +
                                            '<input type="radio" name="transport_company" form="order" class="radio" value="' + val.id + '" />' +
                                            '<span class="company_name">' + val.company + ':' + '</span>' +
                                        '</td>' +
                                        '<td>' +
                                            '<strong>' + val.price + '</strong> р.' +
                                        '</td>' +
                                        '<td  class="delivery_time" style="display: none">' +
                                            '<span>(' + val.day + ')</span>' +
                                        '</td>' +
                                    '</tr>');

                                var transport_company_val = $("input[name='transport_company_val']").val();
                                if (val.id == transport_company_val) {
                                    $(".trans_comp input[name='transport_company']").attr('checked', true);
                                }

                                if (val.day !== "&nbsp;") {
                                    $('#company' + i + ' .delivery_time').show();
                                } else {
                                    $('#company' + i + ' .delivery_time').hide();
                                }
                            } else {
                                $('.trans_comp').append(
                                    '<tr class="order-amount" id="company' + i + '">' +
                                        '<td>' +
                                            '<input type="radio" name="transport_company" form="order" class="radio" value="' + val.entrance.id + '" />' +
                                            '<span class="company_name">' + val.entrance.company + ':' + '</span>' +
                                        '</td>' +
                                        '<td>' +
                                            '<strong>' + val.entrance.price + '</strong> р.' +
                                        '</td>' +
                                        '<td>' +
                                            '<span class="entrance">до подъезда</span>' +
                                        '</td>' +
                                        '<td  class="delivery_time" style="display: none">' +
                                            '<span>(' + val.entrance.day + ')</span>' +
                                        '</td>' +
                                    '</tr>');

                                var transport_company_val = $("input[name='transport_company_val']").val();
                                if (val.entrance.id == transport_company_val) {
                                    $(".trans_comp input[name='transport_company']").attr('checked', true);
                                }

                                if (val.entrance.day !== "&nbsp;") {
                                    $('#company' + i + ' .delivery_time').show();
                                } else {
                                    $('#company' + i + ' .delivery_time').hide();
                                }
                            }

//                          -----------------------
                        }
                    });
                    updateCart();
                } else if (data.result == 0) {
                    $('.trans_comp').html('');
                    $('#transCompResult').show();
                    $('#transCompResult').text('Нет доставки в г.' + city);
                    if ($('.transp_delivery').is(':visible')) {
                        updateCart();
                    }
                } else {
                    $('.trans_comp').html('');
                    $('#transCompResult').show();
                    $('#transCompResult').text('Транспортных компаний не найдено');
                    if ($('.transp_delivery').is(':visible')) {
                        updateCart();
                    }
                }
                $('#city-loader').hide();
                $('#transComp').addClass('calculated');

                callback ? callback(data.result) : null;
            },
            error: function (xhr, ajaxOptions, thrownError) {
                if(ajaxOptions ==="timeout") {
                    $('#city-loader').hide();
                    $('#transComp').show();
                    $('.trans_comp').html('');
                    $('#transCompResult').show();
                    $('#transCompResult').text('Сетевая ошибка');
                }
            }
        });
    } else {
        $('#transComp').show();
        $('.trans_comp').html('');
        $('#transCompResult').show();
        $('#transCompResult').text('Не указан город доставки');
    }

}