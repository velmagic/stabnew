<?php

require_once 'config.php';

$search_str = iconv("UTF-8", "windows-1251", $_REQUEST['search_str']);

$prod = searchForProducts($search_str);

if (count($prod['filtered_prod']) > 0) {

    if (version_compare(PHP_VERSION, '5.0.0', '>=')) {
        $prod = arrayCharEncConvert($prod);
    }

    $json_resp = json_encode(array('result' => 1, 'products' => $prod['filtered_prod'], 'search_words' => $prod['search_words']));

    print $json_resp;
} else {
    print json_encode(array('result' => 0));
}

###########################################################
function arrayCharEncConvert($array) {
    function my_callBck (&$value) {
        $value = iconv("windows-1251", "UTF-8", $value);
    }

    array_walk_recursive($array, 'my_callBck');

    return $array;
}

###########################################################
function searchForProducts($search_str, $search_fields = array('category', 'trademark', 'model')) {
    $products = getProductsArray();

    $search_str = trim(mb_strtolower($search_str, "windows-1251"));

    $search_words = explode(' ', $search_str);
    $search_words_cnt = count($search_words);

    $search_scores = array();

    $filtered_prod = array();

    foreach ($products as $product) {
        $score = 0;
        $term_find_cnt = 0;
        foreach($search_words as $term){
            if ($search_words_cnt == 1 ? strlen($term) > 2 : true) {
                $find_cnt = 0;
                foreach ($search_fields as $field) {
                    if (isset($product[$field])) {
                        $search_field = mb_strtolower($product[$field], "windows-1251");

                        $pos_score = strpos($search_field, $term);
                        if ($pos_score !== false) {
                            $find_cnt++;
                            $score += $pos_score;
                        }
                    }
                }
                $find_cnt > 0 ? $term_find_cnt++ : null;
            }
        }
        if ($term_find_cnt > 0 && $term_find_cnt == $search_words_cnt) {
            $search_scores[] = $score;
            $filtered_prod[] = $product;
        }
    }

    array_multisort($search_scores, SORT_ASC, $filtered_prod);

    $search_words_filtered = array();
    foreach($search_words as $term){
        if (strlen($term) >= 2) {
            $search_words_filtered[] = $term;
        }
    }

    return array('filtered_prod' => $filtered_prod, 'search_words' => $search_words_filtered);
}