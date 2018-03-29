<?php

session_start();

// Подключаем пользовательские функции перехвата
if (file_exists(dirname(__FILE__) . '/custom/hooks.php'))
    include(dirname(__FILE__) . '/custom/hooks.php');

if ((!file_exists($VERSION_INFO_FILE)) || (!is_writable($VERSION_INFO_FILE)) || (!is_writable(dirname(__FILE__) . '/products/shop.xml')))
{
  require_once 'upgrade.php';
} else {

  $ft = file_exists(dirname(__FILE__) . '/products/shop.xml') ? filemtime(dirname(__FILE__) . '/products/shop.xml') : 0;
  $td = getdate();
  $nt = mktime(0, 0, 0, $td['mon'], $td['mday'], $td['year']);
  if ( $ft < $nt ) {
    require_once 'upgrade.php';
  }

}

if ( !function_exists('sys_get_temp_dir') )
{
    function sys_get_temp_dir()
    {
        if ( !empty($_ENV['TMP']) ) { return realpath( $_ENV['TMP'] ); }
        else if ( !empty($_ENV['TMPDIR']) ) { return realpath( $_ENV['TMPDIR'] ); }
        else if ( !empty($_ENV['TEMP']) ) { return realpath( $_ENV['TEMP'] ); }
        else
        {
            $temp_file = tempnam( md5(uniqid(rand(), TRUE)), '' );
            if ( $temp_file )
            {
                $temp_dir = realpath( dirname($temp_file) );
                unlink( $temp_file );
                return $temp_dir;
            }
            else
            {
                return FALSE;
            }
        }
    }
}

###########################################################

function processExtendedTags($text)
{

  while (preg_match("/<TMPL_USE\s*PRODUCT=[\"'](.*?)[\"']>(.*?)<\/TMPL_USE>/is", $text, $matches))
  {
    $product = getProductsArray("//product[@code='" . $matches[1] . "']");
    if (count($product) == 1) 
    {
      $tmpfname = tempnam(sys_get_temp_dir(), 'pp');
      $handle = fopen($tmpfname, "w");
      fwrite($handle, $matches[2]);
      fclose($handle);
      $tmp_tmpl = new vlibTemplate($tmpfname, array('UNKNOWNS' => 'REMOVE', 'GLOBAL_VARS' => 1));
      foreach ($product[0] as $key => $value) {
        if (is_scalar($value)) {
	  $tmp_tmpl->setVar($key, $value);
        }
      }
      $use_prod_text = $tmp_tmpl->grab();
      unlink($tmpfname);
    } else {
      $use_prod_text = 'Товар с кодом ' . $matches[1] . ' не найден';
    }
    $text = preg_replace("/<TMPL_USE(.*?)>(.*?)<\/TMPL_USE>/is", $use_prod_text, $text, 1);
  }

  while (preg_match("/<TMPL_INVOLVE\s*FILE=[\"'](.*?)[\"']>/i", $text, $matches))
  {
    if (file_exists(dirname(__FILE__) . $matches[1]))
    {
      $incl_content = file_get_contents( dirname(__FILE__) . '/' . $matches[1] );
    } else {
      $incl_content = 'Файл ' . dirname(__FILE__) . '/' . $matches[1] . ' не найден';
    }
    $text = preg_replace("/<TMPL_INVOLVE(.*?)>/i", $incl_content, $text, 1);
  }

  return($text);
}

######################################################################################################################

function ServerRequest($strQuery, $BILLING_SERVER, $url)
{
    if (!function_exists('curl_version')) {
        $fp = fsockopen($BILLING_SERVER, 80, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br>\n";
        } else {
            fwrite($fp, "POST http://$BILLING_SERVER/$url HTTP/1.0\r\nHost: $BILLING_SERVER\r\n");

            fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
            fputs($fp, "Content-length: " . strlen($strQuery) . "\r\n");
            fputs ($fp,"\r\n");

            fwrite($fp, $strQuery);

            while(fgets($fp,2048)!="\r\n" && !feof($fp));

            $strReturn = "";
            while(!feof($fp)) {
                $strReturn .= fgets ($fp,128);
            }
            fclose ($fp);
            return  $strReturn;
        }
    } else {
        $ch = curl_init("http://$BILLING_SERVER/$url");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strQuery);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

###########################################################
function getDeliveryArray( $xpath_expression = '//delivery' )
{
  if (version_compare(PHP_VERSION, '5.0.0', '>=')) 
  {
    return(getDeliveryArrayPHP5($xpath_expression));
  } else {
    return(getDeliveryArrayPHP4($xpath_expression));
  }
}

###########################################################
function getDeliveryArrayPHP5( $xpath_expression = '//delivery' )
{
  $deliveries = array();

  $xml = new DOMDocument();
  $xml->load(dirname(__FILE__) . '/products/shop.xml');

  $xpath = new DOMXPath($xml);

  $nodes = $xpath->query($xpath_expression);

  foreach($nodes as $node)
  {
    $delivery = array();
    $delivery['code'] = $node->getAttribute('code');

    foreach($node->childNodes as $subnode) {
      $delivery[$subnode->nodeName] = iconv("UTF-8", "windows-1251",$subnode->nodeValue);
    }

    if (isset($_SESSION['delivery']) && ($_SESSION['delivery'] == $delivery['code']))
    {
      $delivery['selected_delivery'] = 1;
    }

  
    $deliveries[] = $delivery;
  }
  return($deliveries);
}

###########################################################
function getDeliveryArrayPHP4( $xpath_expression = '//delivery' )
{
  $deliveries = array();

  $xml = domxml_open_file(dirname(__FILE__) . '/products/shop.xml');

  $xpath = $xml->xpath_new_context();	// PHP4
  $obj = $xpath->xpath_eval($xpath_expression);

  foreach($obj->nodeset as $node)
  {
    $delivery = array();
    $delivery['code'] = $node->get_attribute('code');
    foreach($node->child_nodes() as $subnode) {
      $delivery[isset($subnode->tagname) ? $subnode->tagname : null] = iconv("UTF-8", "windows-1251",$subnode->get_content());
    }
    if (isset($_SESSION['delivery']) && ($_SESSION['delivery'] == $delivery['code']))
    {
      $delivery['selected_delivery'] = 1;
    }  
    $deliveries[] = $delivery;
  }
  return($deliveries);
}

###########################################################
function getProductsArray( $xpath_expression = '//product' )
{
  if (version_compare(PHP_VERSION, '5.0.0', '>=')) 
  {
    return(getProductsArrayPHP5($xpath_expression));
  } else {
    return(getProductsArrayPHP4($xpath_expression));
  }
}

###########################################################
function getProductsArrayPHP5( $xpath_expression = '//product' )
{
    global $ALLOW_THIRD_PARTY_PRODUCTS;

    $products = array();

    $xml = new DOMDocument('1.0', 'windows-1251');
    $xml->load(dirname(__FILE__) . '/products/shop.xml');



    $CUSTOM_SHOP_XML = dirname(__FILE__) . '/custom/shop.xml';
    if (file_exists($CUSTOM_SHOP_XML))
    {
        $custom_xml = new DOMDocument();
        $custom_xml->load($CUSTOM_SHOP_XML);
        $custom_products = $custom_xml->getElementsByTagName('product');
        foreach($custom_products as $custom_product) {
            $custom_code = $custom_product->getAttribute('code');
            $FOUND = false;
            foreach ($xml->getElementsByTagName('product') as $product)
            {
                if ($product->getAttribute('code') == $custom_code)
                {
                    foreach($custom_product->childNodes as $custom_subnode) {
                        if (($custom_subnode->nodeType == XML_ELEMENT_NODE) && (!in_array($custom_subnode->nodeName, array('price', 'available'), true))) {
                            $nodes = $product->getElementsByTagName($custom_subnode->nodeName);
                            if ($nodes->length > 0) {
                                $product->removeChild($nodes->item(0));
                            }
                            $product->appendChild($xml->importNode($custom_subnode, true));
                        }
                    }
                    $FOUND = true;
                    break;
                }
            }
            if ($ALLOW_THIRD_PARTY_PRODUCTS) {
                if (!$FOUND) {
                    $products_node = $xml->getElementsByTagName('products');
                    $products_node = $products_node->item(0);
                    $products_node->appendChild($xml->importNode($custom_product, true));
                }
            }
        }
    }
  
  $xpath = new DOMXPath($xml);
  $xpath_expression = iconv("windows-1251", "UTF-8", $xpath_expression);
  $nodes = $xpath->query($xpath_expression);

  foreach($nodes as $node)
  {
    $product = array();
    $product['code'] = $node->getAttribute('code');

    foreach($node->childNodes as $subnode) {
      if ($subnode->nodeName == 'characteristics') 
      {
        $sub2nodes = array();
        foreach($subnode->childNodes as $sub2node)
        {
	      if ($sub2node->hasAttributes())
	      {
            $sub2nodes[] = array(
                                'name' => iconv("UTF-8", "windows-1251", $sub2node->getAttribute('name')), 
                                'value' => iconv("UTF-8", "windows-1251",$sub2node->getAttribute('value'))
                               );
	      }
        }
        $product[$subnode->nodeName] = $sub2nodes;
      } else {
        $product[$subnode->nodeName] = iconv("UTF-8", "windows-1251",$subnode->nodeValue);
      }
    }

    if (isset($_SESSION[ $product['code'] ]))
    {
      $product['quantity'] = $_SESSION[ $product['code'] ];
      $product['cost'] = $product['quantity'] * $product['price'];
    }

    $product['fprice'] = number_format($product['price'], 0, '.', ' ');

    $products[] = $product;
  }
  return($products);
}

###########################################################
function getProductsArrayPHP4( $xpath_expression = '//product' )
{
    global $ALLOW_THIRD_PARTY_PRODUCTS;

    $xpath_expression = iconv("windows-1251", "UTF-8", $xpath_expression);

    $products = array();

    $xml = domxml_open_file(dirname(__FILE__) . '/products/shop.xml');
    $xpath = $xml->xpath_new_context();
    $obj = $xpath->xpath_eval($xpath_expression);
    $nodes = $obj->nodeset;

    $CUSTOM_SHOP_XML = dirname(__FILE__) . '/custom/shop.xml';
    $CUSTOM_SHOP = FALSE;
    if (file_exists($CUSTOM_SHOP_XML))
    {
        $custom_xml = domxml_open_file($CUSTOM_SHOP_XML);
        $custom_xpath = $custom_xml->xpath_new_context();
        $CUSTOM_SHOP = TRUE;
    }

  foreach($obj->nodeset as $node)
  {
    $product = array();

    $product['code'] = $node->get_attribute('code');

    foreach($node->child_nodes() as $subnode) {
      if (isset($subnode->tagname) ? $subnode->tagname == 'characteristics' : null)
      {
        $sub2nodes = array();
        foreach($subnode->child_nodes() as $sub2node)
        {
	  if ($sub2node->has_attributes())
	  {
           $sub2nodes[] = array(
                                'name' => iconv("UTF-8", "windows-1251", $sub2node->get_attribute('name')), 
                                'value' => iconv("UTF-8", "windows-1251",$sub2node->get_attribute('value'))
                               );
	  }
        }
        $product[isset($subnode->tagname) ? $subnode->tagname : null] = $sub2nodes;
      } else {
        $product[isset($subnode->tagname) ? $subnode->tagname : null] = iconv("UTF-8", "windows-1251",$subnode->get_content());
      }
    }

    if (isset($_SESSION[ $product['code'] ]))
    {
      $product['quantity'] = $_SESSION[ $product['code'] ];
      $product['cost'] = $product['quantity'] * $product['price'];
    }

      if ($CUSTOM_SHOP)
      {
          $obj = $custom_xpath->xpath_eval("//product[@code=\"" . $product['code'] . "\"]");
          foreach($obj->nodeset as $custom_node) {
              if ($custom_node->get_attribute('code') == $product['code']) {
                  foreach($custom_node->child_nodes() as $custom_subnode) {

                      if (isset($custom_subnode->tagname) ? $custom_subnode->tagname == 'characteristics' : null)
                      {
                          $sub2nodes = array();
                          foreach($custom_subnode->child_nodes() as $sub2node)
                          {
                              if ($sub2node->has_attributes())
                              {
                                  $sub2nodes[] = array(
                                      'name' => iconv("UTF-8", "windows-1251", $sub2node->get_attribute('name')),
                                      'value' => iconv("UTF-8", "windows-1251",$sub2node->get_attribute('value'))
                                  );
                              }
                          }
                          $product[isset($custom_subnode->tagname) ? $custom_subnode->tagname : null] = $sub2nodes;
                      } else {
                          if (!in_array(isset($custom_subnode->tagname) ? $custom_subnode->tagname : null, array('price', 'available')))
                              $product[isset($custom_subnode->tagname) ? $custom_subnode->tagname : null] = iconv("UTF-8", "windows-1251",$custom_subnode->get_content());
                      }

                  }
              }
          }
      }

    $product['fprice'] = number_format($product['price'], 0, '.', ' ');
  
    $products[] = $product;

  }

  if ($ALLOW_THIRD_PARTY_PRODUCTS) {
      if ($CUSTOM_SHOP)
      {
          $obj = $custom_xpath->xpath_eval($xpath_expression);
          foreach($obj->nodeset as $custom_node) {
              $obj = $xpath->xpath_eval("//product[@code=\"" . $custom_node->get_attribute('code') . "\"]");
              if (count($obj->nodeset) == 0) {
                  $product = array();

                  $product['code'] = $custom_node->get_attribute('code');
                  foreach($custom_node->child_nodes() as $custom_subnode) {

                      if (isset($custom_subnode->tagname) ? $custom_subnode->tagname == 'characteristics' : null)
                      {
                          $sub2nodes = array();
                          foreach($custom_subnode->child_nodes() as $sub2node)
                          {
                              if ($sub2node->has_attributes())
                              {
                                  $sub2nodes[] = array(
                                      'name' => iconv("UTF-8", "windows-1251", $sub2node->get_attribute('name')),
                                      'value' => iconv("UTF-8", "windows-1251",$sub2node->get_attribute('value'))
                                  );
                              }
                          }
                          $product[isset($custom_subnode->tagname) ? $custom_subnode->tagname : null] = $sub2nodes;
                      } else {
                          $product[isset($custom_subnode->tagname) ? $custom_subnode->tagname : null] = iconv("UTF-8", "windows-1251",$custom_subnode->get_content());
                      }

                  }

                  $product['fprice'] = number_format($product['price'], 0, '.', ' ');

                  $products[] = $product;
              }
          }
      }
  }

  return($products);
}

###########################################################
function getContactsArray( $xpath_expression = '//contacts' )
{
    if (version_compare(PHP_VERSION, '5.0.0', '>='))
    {
        return(getContactsArrayPHP5($xpath_expression));
    } else {
        return(getContactsArrayPHP4($xpath_expression));
    }
}

###########################################################
function getContactsArrayPHP5( $xpath_expression = '//contacts' )
{
    $contacts = array();

    $xml = new DOMDocument();
    $xml->load(dirname(__FILE__) . '/products/shop.xml');

    $CUSTOM_SHOP_XML = dirname(__FILE__) . '/custom/shop.xml';
    if (file_exists($CUSTOM_SHOP_XML))
    {
        $custom_xml = new DOMDocument();
        $custom_xml->load($CUSTOM_SHOP_XML);
        $custom_contacts = $custom_xml->getElementsByTagName('contacts');

        foreach($custom_contacts as $custom_contact) {

            foreach ($xml->getElementsByTagName('contacts') as $contact)
            {
                foreach($custom_contact->childNodes as $custom_subnode) {
                    $FOUND = array();
                    foreach($contact->childNodes as $subnode) {
                        if ($custom_subnode->nodeName == $subnode->nodeName) {
                            $nodes = $contact->getElementsByTagName($custom_subnode->nodeName);
                            if ($nodes->length > 0) {
                                $contact->removeChild($nodes->item(0));
                                $contact->appendChild($xml->importNode($custom_subnode, true));
                                $FOUND[] = $custom_subnode->nodeName;
                            }
                        }
                    }
                    if (count($FOUND) == 0) {
                        $contact->appendChild($xml->importNode($custom_subnode, true));
                    }
                }
                break;
            }
        }
    }

    $xpath = new DOMXPath($xml);

    $nodes = $xpath->query($xpath_expression);
    foreach($nodes as $node) {
        foreach($node->childNodes as $subnode) {
            $contacts[$subnode->nodeName] = iconv("UTF-8", "windows-1251",$subnode->nodeValue);
        }
    }

    if (isset($contacts['phone'])) {
        $matches = preg_split("/[\(\)]+/", $contacts['phone']);

        $contacts['phone_particles'] = $matches;

        $patterns = array("/\-/", "/[\(\)]/");
        $contacts['phone_particles'][] = preg_replace($patterns, "", $contacts['phone']);
    }

    return($contacts);
}

###########################################################
function getContactsArrayPHP4( $xpath_expression = '//contacts' )
{
    $contacts = array();

    $xml = domxml_open_file(dirname(__FILE__) . '/products/shop.xml');

    $xpath = $xml->xpath_new_context();	// PHP4

    $obj = $xpath->xpath_eval($xpath_expression);

    foreach($obj->nodeset as $node)
    {
        foreach($node->child_nodes() as $subnode) {
            $contacts[isset($subnode->tagname) ? $subnode->tagname : null] = iconv("UTF-8", "windows-1251",$subnode->get_content());
        }
    }

    $CUSTOM_SHOP_XML = dirname(__FILE__) . '/custom/shop.xml';
    if (file_exists($CUSTOM_SHOP_XML))
    {
        $custom_xml = domxml_open_file($CUSTOM_SHOP_XML);
        $custom_xpath = $custom_xml->xpath_new_context();	// PHP4
        $custom_contacts = $custom_xpath->xpath_eval($xpath_expression);

        foreach($custom_contacts->nodeset as $custom_contact) {
            foreach($custom_contact->child_nodes() as $custom_subnode) {
                $contacts[isset($custom_subnode->tagname) ? $custom_subnode->tagname : null] = iconv("UTF-8", "windows-1251", $custom_subnode->get_content());
            }
        }
    }

    if (isset($contacts['phone'])) {
        $matches = preg_split("/[\(\)]+/", $contacts['phone']);

        $contacts['phone_particles'] = $matches;

        $patterns = array("/\-/", "/[\(\)]/");
        $contacts['phone_particles'][] = preg_replace($patterns, "", $contacts['phone']);
    }

    return($contacts);
}

###########################################################
function getDeliveryDate()
{
	$today = time();
	$tomorrow = $today + 24*60*60;
	$deliveryDate = date('d.m.Y', $tomorrow); 
	return($deliveryDate);
}

###########################################################
function startTemplate( $tmpl_name )
{
  global $SITE_NAME, $TMPL_DIR, $AFF_ID;
  $tmpl = new vlibTemplate("$TMPL_DIR/$tmpl_name", array('UNKNOWNS' => 'REMOVE', 'GLOBAL_VARS' => 1,'ENABLE_PHPINCLUDE'=>1));
  $tmpl->setvar('SITE_NAME', $SITE_NAME);
  $tmpl->setvar('HTTP_HOST', preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']));
  if (isset($AFF_ID)) $tmpl->setvar('AFF_ID', $AFF_ID);
  if (isset($_SESSION['cart_value'])) { $tmpl->setVar('CART_COUNT', $_SESSION['cart_count']); }
  if (isset($_SESSION['cart_value'])) { $tmpl->setVar('CART_VALUE', $_SESSION['cart_value']); }
  $tmpl->setVar('PRICE_DATE', date ("d.m.Y", filemtime(dirname(__FILE__) . '/products/shop.xml')));

  $contacts = getContactsArray();
  $tmpl->setVar('PHONE', $contacts['phone']);
  $tmpl->setVar('PHONE_CODE', $contacts['phone_particles'][1]);
  $tmpl->setVar('PHONE_NUM', $contacts['phone_particles'][2]);
  $tmpl->setVar('PHONE_FULL', $contacts['phone_particles'][3]);
  $tmpl->setVar('EMAIL', $contacts['email']);
  $tmpl->setVar('ICQ', $contacts['icq']);
  $tmpl->setVar('SKYPE', $contacts['skype']);

  $deliveries = getDeliveryArray();
  $tmpl->setVar('DELIVERY_PRICE', $deliveries[0]['price']);
  $tmpl->setVar('DELIVERY_FREE_LIMIT', $deliveries[0]['free_limit']);
  $tmpl->setVar('DELIVERY_EXTRA_PAY', $deliveries[0]['extra_pay']);
  $tmpl->setVar('DELIVERY_DATE', getDeliveryDate());

  if (function_exists('onTemplateStart'))
    call_user_func('onTemplateStart', $tmpl);

  return($tmpl);
}

###########################################################
function redirect( $URL )
{
  $tmpl = startTemplate('redirect.tmpl');
  $tmpl->setvar('URL', $URL);
  $tmpl->pparse();
  exit;
}

###########################################################
if (!function_exists('json_encode'))
{
    function json_encode($a=false)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a))
            {
                static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = json_encode($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
            return '{' . join(',', $result) . '}';
        }
    }
}

?>