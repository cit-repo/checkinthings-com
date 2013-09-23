<?php

    require_once("../inc/db.inc.php");
    require_once("../inc/functions.inc.php");

    /*
        [0] => name
        [1] => productUrl
        [2] => imageUrl
        [3] => description
        [4] => price
        [5] => currency
        [6] => TDProductId
        [7] => TDCategoryID
        [8] => TDCategoryName
        [9] => merchantCategoryName
        [10] => sku
        [11] => shortDescription
        [12] => promoText
        [13] => previousPrice
        [14] => warranty
        [15] => availability
        [16] => inStock
        [17] => shippingCost
        [18] => deliveryTime
        [19] => weight
        [20] => size
        [21] => brand
        [22] => model
        [23] => ean
        [24] => upc
        [25] => isbn
        [26] => condition
        [27] => mpn
        [28] => techSpecs
        [29] => manufacturer
        [30] => programName
        [31] => programLogoPath
        [32] => programId
        [33] => advertiserProductUrl
        [34] => fields
    */

    $products = new SimpleXMLElement("tradedoubler.xml", 0, 1);

    $arKeys = array("product_id");
    $arKeys = array("feed");

    foreach ($products as $product) {
        foreach ($product as $key => $value) {
            if (!in_array($key, $arKeys) && !is_array($value) && $key != 'fields') {
                $arKeys[] = $key;
            }
        }
    }

    $feed = "tradedoubler";

    $n = 0;

    foreach ($products as $product) {

	$json = file_get_contents('http://127.0.0.1:5984/_uuids');
	$decode = json_decode($json);
	$uuid = $decode->uuids[0];

        $ins = "INSERT INTO product (`product_id`, `feed`, `name`, `uuid`) VALUES (NULL, '$feed', '".$product->name."', '$uuid');";
        $res = mysqli_query($link, $ins);
        $id = mysqli_insert_id($link);
        // echo $ins."\n";

        foreach ($product as $key => $value) {

            $value = str_replace("'","''",rtrim($value));

            switch ($key) {
                case "feed":
                case "name":
                case "uuid":
                    break;

                case "fields":
                    foreach ($product->fields as $fields) {
                        foreach ($fields as $fAttribute => $fValue) {
                            $fValue = str_replace("'","''",rtrim($fValue));

                            if (isset($fValue) && $fValue != '') {
                                 $ins2 = "INSERT INTO product_eav (`product_eav_id`, `product_id`, `attribute`, `value`, `ts`) VALUES (NULL, $id, '".camelCaseToUnderscores($fAttribute)."', '".$fValue."', '".microtime(true)."');";
                                 $res2 = mysqli_query($link, $ins2);
                                 // echo $ins2."\n";
                            }
                        }
                    }
                    break;

                case "channel":
                case "description":
                case "short_description":
                case "brand":
                case "model":
                case "manufacturer":
                case "price":
                case "currency":
                case "sku":
                case "warranty":
                case "availability":
                case "in_stock":
                case "shipping_cost":
                case "delivery_time":
                case "weight":
                case "size":
                case "ean":
                case "upc":
                case "isbn":
                case "mpn":
                case "condition":
                    if (isset($value) && $value != '') {
                        $upd = "UPDATE product SET `".camelCaseToUnderscores($key)."` = '".$value."' WHERE product_id = $id;";
                        $res = mysqli_query($link, $upd);
                        // echo $upd."\n";
                    }
                    break;

                default:
                    if (isset($value) && $value != '') {
                        if (camelCaseToUnderscores($key) == 'image_url') {
                            $upd = "UPDATE product SET `".camelCaseToUnderscores($key)."` = '".$value."' WHERE product_id = $id;";
                            $res = mysqli_query($link, $upd);
                            // echo $upd."\n";
                        } else if (camelCaseToUnderscores($key) == 'product_url') {
                            $upd = "UPDATE product SET `".camelCaseToUnderscores($key)."` = '".$value."' WHERE product_id = $id;";
                            $res = mysqli_query($link, $upd);
                            // echo $upd."\n";
                        } else if (camelCaseToUnderscores($key) == 'merchant_category_name') {
                            $upd = "UPDATE product SET `main_category` = '".camelCaseToUnderscores(strtolower($value))."' WHERE product_id = $id;";
                            $res = mysqli_query($link, $upd);
                            // echo $upd."\n";
                        } else if (camelCaseToUnderscores($key) == 'program_name') {
                            $upd = "UPDATE product SET `channel` = '".$value."' WHERE product_id = $id;";
                            $res = mysqli_query($link, $upd);
                            // echo $upd."\n";
                        } else {
                            if (isset($value) && $value != '') {
                                $ins3 = "INSERT INTO product_eav (`product_eav_id`, `product_id`, `attribute`, `value`) VALUES (NULL, $id, '".camelCaseToUnderscores($key)."', '".$value."');";
                                $res3 = mysqli_query($link, $ins3);
                                // echo $ins3."\n";
                            }
                        }
                    }

                    break;

            }
        }

        echo $n++."\r";
    }

    echo $n++."\n";
