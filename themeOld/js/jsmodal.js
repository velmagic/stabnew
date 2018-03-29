/*!
 * jsModal - A pure JavaScript modal dialog engine v1.0d
 * http://jsmodal.com/
 *
 * Author: Henry Rune Tang Kai <henry@henrys.se>
 *
 * (c) Copyright 2013 Henry Tang Kai.
 *
 * License: http://www.opensource.org/licenses/mit-license.php
 *
 * Date: 2013-7-11
 */
var Modal = (function () {
        "use strict";
        /*global document: false */
        /*global window: false */

         // create object method
        var method = {},
            settings = {},

            modalOverlay = document.createElement('div'),
            modalContainer = document.createElement('div'),
            modalHeader = document.createElement('div'),
            modalContent = document.createElement('div'),
            modalClose = document.createElement('div'),

            centerModal,

            closeModalEvent,

            defaultSettings = {
                width: 'auto',
                height: 'auto',
                lock: false,
                hideClose: false,
                draggable: false,
                closeAfter: 0,
                openCallback: false,
                closeCallback: false,
                hideOverlay: false
            };

        // Open the modal
        method.open = function (parameters) {
            settings.width = parameters.width || defaultSettings.width;
            settings.height = parameters.height || defaultSettings.height;
            settings.lock = parameters.lock || defaultSettings.lock;
            settings.hideClose = parameters.hideClose || defaultSettings.hideClose;
            settings.draggable = parameters.draggable || defaultSettings.draggable;
            settings.closeAfter = parameters.closeAfter || defaultSettings.closeAfter;
            settings.closeCallback = parameters.closeCallback || defaultSettings.closeCallback;
            settings.openCallback = parameters.openCallback || defaultSettings.openCallback;
            settings.hideOverlay = parameters.hideOverlay || defaultSettings.hideOverlay;

            centerModal = function () {
                method.center({});
            };

            //if (parameters.content && !parameters.ajaxContent) {
            //    modalContent.innerHTML = parameters.content;
            //} else if (parameters.ajaxContent && !parameters.content) {
            //    modalContainer.className = 'modal-loading';
            //    method.ajax(parameters.ajaxContent, function insertAjaxResult(ajaxResult) {
            //        modalContent.innerHTML = ajaxResult;
            //    });
            //} else {
            //    modalContent.innerHTML = '';
            //}

            if (parameters.content && parameters.header && !parameters.ajaxContent) {
                modalContent.innerHTML = parameters.content;
                modalHeader.innerHTML = parameters.header;
                modalHeader.appendChild(modalClose);
            } else if (parameters.ajaxContent && !parameters.content && !parameters.header) {
                modalContainer.className = 'modal-loading';
                method.ajax(parameters.ajaxContent, function insertAjaxResult(ajaxResult) {
                    modalContent.innerHTML = ajaxResult;
                });
            } else {
                modalContent.innerHTML = '';
            }

            modalContainer.style.width = settings.width;
            modalContainer.style.height = settings.height;

            method.center({});

            if (settings.lock || settings.hideClose) {
                modalClose.style.visibility = 'hidden';
            }
            if (!settings.hideOverlay) {
                modalOverlay.style.visibility = 'visible';
            }
            modalContainer.style.visibility = 'visible';

            document.onkeypress = function (e) {
                if (e.keyCode === 27 && settings.lock !== true) {
                    method.close();
                }
            };

            modalClose.onclick = function () {
                if (!settings.hideClose) {
                    method.close();
                } else {
                    return false;
                }
            };
            modalOverlay.onclick = function () {
                if (!settings.lock) {
                    method.close();
                } else {
                    return false;
                }
            };

            if (window.addEventListener) {
                window.addEventListener('resize', centerModal, false);
            } else if (window.attachEvent) {
                window.attachEvent('onresize', centerModal);
            }

            if (settings.draggable) {
                modalHeader.style.cursor = 'move';
                modalHeader.onmousedown = function (e) {
                    method.drag(e);
                    return false;
                };
            } else {
                modalHeader.onmousedown = function () {
                    return false;
                };
            }
            if (settings.closeAfter > 0) {
                closeModalEvent = window.setTimeout(function () {
                    method.close();
                }, settings.closeAfter * 1000);
            }
            if (settings.openCallback) {
                settings.openCallback();
            }
        };

        // Drag the modal
        method.drag = function (e) {
            var xPosition = (window.event !== undefined) ? window.event.clientX : e.clientX,
                yPosition = (window.event !== undefined) ? window.event.clientY : e.clientY,
                differenceX = xPosition - modalContainer.offsetLeft,
                differenceY = yPosition - modalContainer.offsetTop;

            document.onmousemove = function (e) {
                xPosition = (window.event !== undefined) ? window.event.clientX : e.clientX;
                yPosition = (window.event !== undefined) ? window.event.clientY : e.clientY;

                modalContainer.style.left = ((xPosition - differenceX) > 0) ? (xPosition - differenceX) + 'px' : 0;
                modalContainer.style.top = ((yPosition - differenceY) > 0) ? (yPosition - differenceY) + 'px' : 0;

                document.onmouseup = function () {
                    window.document.onmousemove = null;
                };
            };
        };

        // Perform XMLHTTPRequest
        method.ajax = function (url, successCallback) {
            var i,
                XMLHttpRequestObject = false,
                XMLHttpRequestObjects = [
                    function () {
                        return new window.XMLHttpRequest();  // IE7+, Firefox, Chrome, Opera, Safari
                    },
                    function () {
                        return new window.ActiveXObject('Msxml2.XMLHTTP.6.0');
                    },
                    function () {
                        return new window.ActiveXObject('Msxml2.XMLHTTP.3.0');
                    },
                    function () {
                        return new window.ActiveXObject('Msxml2.XMLHTTP');
                    }
                ];

            for (i = 0; i < XMLHttpRequestObjects.length; i += 1) {
                try {
                    XMLHttpRequestObject = XMLHttpRequestObjects[i]();
                } catch (ignore) {
                }

                if (XMLHttpRequestObject !== false) {
                    break;
                }
            }

            XMLHttpRequestObject.open('GET', url, true);

            XMLHttpRequestObject.onreadystatechange = function () {
                if (XMLHttpRequestObject.readyState === 4) {
                    if (XMLHttpRequestObject.status === 200) {
                        successCallback(XMLHttpRequestObject.responseText);
                        modalContainer.removeAttribute('class');
                    } else {
                        successCallback(XMLHttpRequestObject.responseText);
                        modalContainer.removeAttribute('class');
                    }
                }
            };

            XMLHttpRequestObject.send(null);
        };


        // Close the modal
        method.close = function () {
            modalContent.innerHTML = '';
            modalOverlay.setAttribute('style', '');
            modalOverlay.style.cssText = '';
            modalOverlay.style.visibility = 'hidden';
            modalContainer.setAttribute('style', '');
            modalContainer.style.cssText = '';
            modalContainer.style.visibility = 'hidden';
            modalHeader.style.cursor = 'default';
            modalClose.setAttribute('style', '');
            modalClose.style.cssText = '';

            if (closeModalEvent) {
                window.clearTimeout(closeModalEvent);
            }

            if (settings.closeCallback) {
                settings.closeCallback();
            }

            if (window.removeEventListener) {
                window.removeEventListener('resize', centerModal, false);
            } else if (window.detachEvent) {
                window.detachEvent('onresize', centerModal);
            }
        };

        // Center the modal in the viewport
        method.center = function (parameters) {
            var $document = $(document),
                $window = $(window),
                $body = $document.find('body'),
                $container = $(modalContainer),
                $modalOverlay = $(modalOverlay),
                documentHeight = Math.max($body.scrollTop(), $document.height()),

                modalWidth = Math.max($container.width(), $container.outerWidth()),
                modalHeight = Math.max($container.height(), $container.outerWidth()),

                browserWidth = 0,
                browserHeight = 0,

                amountScrolledX = 0,
                amountScrolledY = 0;

            if (typeof ($window.width()) === 'number' && $window.width() !== 0) {
                browserWidth = $window.width();
                browserHeight = $window.height();
            } else if ($document && $document.width()) {
                browserWidth = $document.width();
                browserHeight = $body.height();
            }

            if (typeof ($window.scrollTop()) === 'number') {
                amountScrolledY = $window.scrollTop();
                amountScrolledX = $window.scrollLeft();
            } else if ($body && $body.scrollLeft()) {
                amountScrolledY = $body.scrollTop();
                amountScrolledX = $body.scrollLeft();
            } else if ($document && $document.scrollLeft()) {
                amountScrolledY = $document.scrollTop();
                amountScrolledX = $document.scrollLeft();
            }

            if (!parameters.horizontalOnly) {
                if (browserHeight > 600) {
                    $container.css('top', amountScrolledY + (browserHeight / 2) - (modalHeight / 2) + 'px');
                } else {
                    $container.css('top', amountScrolledY + (browserHeight / 1.4) - (modalHeight / 2) + 'px');
                }
            }

            $container.css('left', amountScrolledX + (browserWidth / 2) - (modalWidth / 2) + 'px');

            $modalOverlay.css('height', documentHeight + 'px');
            $modalOverlay.css('width', '100%');


        };

        // Set the id's, append the nested elements, and append the complete modal to the document body
        modalOverlay.setAttribute('id', 'modal-overlay');
        modalContainer.setAttribute('id', 'modal-container');
        modalHeader.setAttribute('id', 'modal-header');
        modalContent.setAttribute('id', 'modal-content');
        modalClose.setAttribute('id', 'modal-close');

        modalContainer.appendChild(modalHeader);
        modalContainer.appendChild(modalContent);

        modalOverlay.style.visibility = 'hidden';
        modalContainer.style.visibility = 'hidden';

        if (window.addEventListener) {
            window.addEventListener('load', function () {
                document.getElementById('modal-wrapper').appendChild(modalOverlay);
                document.getElementById('modal-wrapper').appendChild(modalContainer);
            }, false);
        } else if (window.attachEvent) {
            window.attachEvent('onload', function () {
                document.getElementById('modal-wrapper').appendChild(modalOverlay);
                document.getElementById('modal-wrapper').appendChild(modalContainer);
            });
        }

        return method;
    }());

$(document).ready(function($){
    $('#modal-wrapper').on('submit', '#one_cl_order-form', function(e) {
        var form = this;

        if (checkForm(form, e)) {
            $.ajax({
                url: "ajax-oneclick-order.php",
                type: "POST",
                dataType: "json",
                cache: false,
                data: {
                    'prod_code': $("input[name='one_cl_order-prod_code']").val(),
                    'prod_quantity': $('#one_cl_order-quantity').val(),
                    'fname': form.fname.value,
                    'phone': form.phone.value
                },
                beforeSend : function(req) {
                    disableForm($('form#one_cl_order-form'));
                },
                success: function(data) {
                    if (data.result == 1) {
                        $('#modal-wrapper .modal-description').hide();
                        $('#modal-wrapper .modal-title').text('Заказ #' + data.data + ' принят').css('text-align', 'center');
                        $('#modal-wrapper #modal-content').html(
                            '<img src="/theme/img/ok_64.jpg" width="64" height="64">' +
                            '<p>' +
                            'Ваш заказ успешно принят и поставлен в очередь на обработку.<br />В ближайшее время с вами свяжется наш менеджер для согласования времени доставки.' +
                            '</p>'
                        );
                        Modal.center({});
                    }
                },
				/*allways:funtion(data)
				{
					alert(data.result);
				}*/
				error:function( XHR, textStatus, errorThrown )
				{
					//alert('XHR ' + XHR.stausText);
					$('#modal-wrapper .modal-description').hide();
                        $('#modal-wrapper .modal-title').text('Ошибка отправки заказа ').css('text-align', 'center');
                        $('#modal-wrapper #modal-content').html(
                            '<img src="/theme/img/error64.png" width="64" height="64">' +
                            '<p>' +
                            'Возникла ошибка при отправке заказа.<br>' +
							'Сделайте, пожалуйста, заказ по телефону +7(499)322-8089.<br>Заказы принимаются <span style="font-weight:bold;">круглосуточно.</span>' +
                            '</p>'
                        );
                        Modal.center({});
				}/*,
				complete:function (XMLHttpRequest, textStatus) {
  this; // the options for this ajax request
					alert('complete '+textStatus);  
}*/
            });

            e.preventDefault();
        }

    });
});


function checkForm(form, e)
{
    if(form.fname.value == "") {
        alert("Необходимо указать имя.");
        form.fname.focus();
        e.preventDefault();
        return false;
    }

    if(form.phone.value == "") {
        alert("Необходимо указать контактный телефон.");
        form.phone.focus();
        e.preventDefault();
        return false;
    }

    return true;
}

// Дизеблим форму
function disableForm(form) {
    form.find("input").attr('disabled','disabled');
    form.find("button[type='submit']").hide();
    form.find("#one_cl_order-loading").show();
}