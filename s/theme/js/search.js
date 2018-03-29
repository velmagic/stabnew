$(function() {

    $('body').on('click', function(e) {
        if ((($('#search_input').length && $(e.target).closest('#search_input').length) || ($('#search_result').length && $(e.target).closest('#search_result').length)) && $('#search_result_list li').length) {
            $('#search_result').show();
        } else if ($('#search_result').length && $('#search_result').is(':visible')) {
            $('#search_result').hide();
            e.preventDefault();
            e.stopPropagation();
        }
    });

    $('#search_input').on('keyup', function(event) {
        var search_term = $(this).val();

        if (search_term.length > 2) {
            $.ajax({
                type: 'POST',
                url: 'ajax-get-products.php',
                data: {
                    search_str: search_term
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (data.result == 1) {

                        var result_list = '',
                            result_items = '';

                        $.each(data.products, function() {
                            var $product = $(this);

                            result_items += '<li><a href="product.php?product=' + $product[0].code + '">' + $product[0].category + ' ' + $product[0].trademark + ' ' + $product[0].model + '<small class="search-prod-price">' + $product[0].fprice + ' руб.</small></a></li>';
                        });

                        result_list = '<ul id="search_result_list">' + result_items + '</ul>';

                        $('#search_result').html(result_list);
                        $('#search_result').show();

                        $("#search_result_list li a").highlight(data.search_words, { element: 'span', className: 'search-highlight' });
                    } else {
                        $('#search_result').hide();
                        $('#search_result').html('');
                    }
                }
            });
        } else {
            $('#search_result').hide();
            $('#search_result').html('');
        }

    });

    $('#search_form').on('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
    });


});

jQuery.extend({
    highlight: function (node, re, nodeName, className) {
        if (node.nodeType === 3) {
            var match = node.data.match(re);
            if (match) {
                var highlight = document.createElement(nodeName || 'span');
                highlight.className = className || 'highlight';
                var wordNode = node.splitText(match.index);
                wordNode.splitText(match[0].length);
                var wordClone = wordNode.cloneNode(true);
                highlight.appendChild(wordClone);
                wordNode.parentNode.replaceChild(highlight, wordNode);
                return 1; //skip added node in parent
            }
        } else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
            !/(script|style)/i.test(node.tagName) && // ignore script and style nodes
            !(node.tagName === nodeName.toUpperCase() && node.className === className)) { // skip if already highlighted
            for (var i = 0; i < node.childNodes.length; i++) {
                i += jQuery.highlight(node.childNodes[i], re, nodeName, className);
            }
        }
        return 0;
    }
});

jQuery.fn.unhighlight = function (options) {
    var settings = { className: 'highlight', element: 'span' };
    jQuery.extend(settings, options);

    return this.find(settings.element + "." + settings.className).each(function () {
        var parent = this.parentNode;
        parent.replaceChild(this.firstChild, this);
        parent.normalize();
    }).end();
};

jQuery.fn.highlight = function (words, options) {
    var settings = { className: 'highlight', element: 'span', caseSensitive: false, wordsOnly: false };
    jQuery.extend(settings, options);

    if (words.constructor === String) {
        words = [words];
    }
    words = jQuery.grep(words, function(word, i){
        return word != '';
    });
    words = jQuery.map(words, function(word, i) {
        return word.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
    });
    if (words.length == 0) { return this; };

    var flag = settings.caseSensitive ? "" : "i";
    var pattern = "(" + words.join("|") + ")";
    if (settings.wordsOnly) {
        pattern = "\\b" + pattern + "\\b";
    }
    var re = new RegExp(pattern, flag);

    return this.each(function () {
        jQuery.highlight(this, re, settings.element, settings.className);
    });
};
