<?php

    require_once("../inc/db.inc.php");
    require_once("../inc/functions.inc.php");

    $json = getJsonFromUrl("https://api.svpply.com/v1/shop/categories.json", "categories.json");
    $arCategories = json_decode($json, true);

    foreach ($arCategories['response']['categories'][0]['children'] as $categories) {
        foreach ($categories as $key => $value) {
            // if ($key == 'url') echo "$value\n";
        }
    }

    $arUrls = array();

    $arUrls[] = "https://api.svpply.com/v1/shop/mens.json";
    $arUrls[] = "https://api.svpply.com/v1/shop/womens.json";
    $arUrls[] = "https://api.svpply.com/v1/shop/tech.json";
    $arUrls[] = "https://api.svpply.com/v1/shop/media.json";
    $arUrls[] = "https://api.svpply.com/v1/shop/home.json";
    $arUrls[] = "https://api.svpply.com/v1/shop/art.json";
    $arUrls[] = "https://api.svpply.com/v1/shop/other.json";

    /*
    Array
    (
        [type] => Product
        [id] => 3116456
        [id_str] => 3116456
        [page_title] => Aliexpress.com : Buy Free Shipping lower Lace fit short T shirt tops 181 from Reliable TSHIRT suppliers on Not-u Fashion dress (Justinlin)
        [page_url] => https://api.svpply.com/v1/products/3116456/purchase
        [price] => 14
        [formatted_price] => $14.00
        [currency_code] => USD
        [discount] =>
        [discount_code] =>
        [category] => apparel
        [categories] => Array
            (
                [0] => Array
                    (
                        [name] => All Products
                        [url] => https://api.svpply.com/v1/shop.json
                        [text_color] => #FF5B54
                        [border_color] => #FFDEDD
                        [peak_rank] =>
                    )

                [1] => Array
                    (
                        [name] => Men’s
                        [url] => https://api.svpply.com/v1/shop/mens.json
                        [text_color] => #FF5B54
                        [border_color] => #FFDEDD
                        [peak_rank] =>
                    )

                [2] => Array
                    (
                        [name] => Clothing
                        [url] => https://api.svpply.com/v1/shop/mens/clothing.json
                        [text_color] => #FF5B54
                        [border_color] => #FFDEDD
                        [peak_rank] =>
                    )

                [3] => Array
                    (
                        [name] => Tops
                        [url] => https://api.svpply.com/v1/shop/mens/clothing/tops.json
                        [text_color] => #FF5B54
                        [border_color] => #FFDEDD
                        [peak_rank] =>
                    )

                [4] => Array
                    (
                        [name] => T-Shirts
                        [url] => https://api.svpply.com/v1/shop/mens/clothing/tops/t-shirts.json
                        [text_color] => #782825
                        [border_color] => #782825
                        [peak_rank] =>
                    )

            )

        [gender] => neutral
        [store] => Array
            (
                [type] => Store
                [id] => 23134
                [id_str] => 23134
                [name] => Aliexpress
                [slug] => aliexpress.com
                [url] => http://aliexpress.com
                [avatar] => https://s3.amazonaws.com/assets.svpply.com/store/avatar/default.jpg
                [description] =>
                [products_count] => 1975
                [collections_count] => 2722
                [users_followers_count] => 1577
                [locations_count] => 0
                [masthead] =>
                [masthead_height] =>
                [masthead_width] =>
                [date_created] => 2011-01-13 03:29:33 UTC
                [date_updated] => 2013-07-24 16:22:39 UTC
            )

        [width] => 625
        [height] => 545
        [image] => https://s3.amazonaws.com/assets.svpply.com/medium/3116456.jpg?1374683990
        [saves] => 3
        [notes] => 3
        [status_id] => 1
        [date_created] => 2013-07-24 16:02:46 UTC
        [date_updated] => 2013-07-24 16:39:50 UTC
        [user] => Array
            (
                [type] => User
                [id] => 70268
                [id_str] => 70268
                [name] => teeth rich
                [username] => casket
                [url] => http://shinjukugewalt.tumblr.com
                [description] =>
                [location] =>
                [display_name] => teeth rich
                [avatar] => https://s3.amazonaws.com/assets.svpply.com/avatars/70268.png?u=1374681766
                [gender_preference] => none
                [products_count] => 5464
                [owns_count] => 109
                [users_following_count] => 55
                [stores_following_count] => 0
                [searches_following_count] => 0
                [users_followers_count] => 867
                [date_created] => 2011-09-06 02:24:48 UTC
                [date_updated] => 2013-07-24 16:02:46 UTC
                [collections_count] => 1
            )

    )

    */

    $feed = "svpply";

    // mysqli_query($link, "TRUNCATE $database.product_eav;");
    // mysqli_query($link, "TRUNCATE $database.product;");

    $n = 0;

    foreach ($arUrls as $url) {
        $folders = explode("/", $url);

        echo $folders[5]."\n";

        $mainCategory = str_replace(".json", "", $folders[5]);

        $json = getJsonFromUrl($url, $folders[5]);
        $arProducts = json_decode($json, true);

        foreach ($arProducts['response']['products'] as $product) {

            $json = file_get_contents('http://127.0.0.1:5984/_uuids');
            $decode = json_decode($json);
            $uuid = $decode->uuids[0];

            $ins = "INSERT INTO product (`product_id`, `feed`, `name`, `image_url`, `uuid`) VALUES (NULL, '$feed', '".addslashes($product['page_title'])."', '".addslashes($product['image'])."', '$uuid');";
            // echo $ins."\n";

            $res = mysqli_query($link, $ins);
            $id = mysqli_insert_id($link);

            foreach ($product as $key => $value) {
                if (!is_array($value)) {
                    switch ($key) {
                        case "page_title":
                        case "image":
                            break;
                        case "gender":
                            $upd = "UPDATE product SET `".camelCaseToUnderscores($key)."` = '".addslashes($value)."' WHERE product_id = $id;";
                            // echo $upd."\n";
                            $res = mysqli_query($link, $upd);
                            break;
                        case "price":
                            $upd = "UPDATE product SET `price` = '".addslashes($value)."' WHERE product_id = $id;";
                            // echo $upd."\n";
                            $res = mysqli_query($link, $upd);
                            break;
                        case "currency_code":
                            $upd = "UPDATE product SET `currency` = '".addslashes($value)."' WHERE product_id = $id;";
                            // echo $upd."\n";
                            $res = mysqli_query($link, $upd);
                            break;
                        case "id":
                            $ins3 = "INSERT INTO product_eav (`product_eav_id`, `product_id`, `attribute`, `value`) VALUES (NULL, $id, '".$feed."_id"."', '".addslashes($value)."');";
                            // echo $ins3."\n";
                            $res = mysqli_query($link, $ins3);
                            break;
                        case "id_str":
                            $ins4 = "INSERT INTO product_eav (`product_eav_id`, `product_id`, `attribute`, `value`) VALUES (NULL, $id, '".$feed."_id_str"."', '".addslashes($value)."');";
                            // echo $ins4."\n";
                            $res = mysqli_query($link, $ins4);
                            break;
                        default:
                            $ins2 = "INSERT INTO product_eav (`product_eav_id`, `product_id`, `attribute`, `value`) VALUES (NULL, $id, '".camelCaseToUnderscores($key)."', '".addslashes($value)."');";
                            // echo $ins2."\n";
                            $res = mysqli_query($link, $ins2);
                            break;
                    }
                } else {
                    switch ($key) {
                        case "store":
                            $upd2 = "UPDATE product SET `channel` = '".addslashes($value['name'])."' WHERE product_id = $id;";
                            // echo $upd2."\n";
                            $res = mysqli_query($link, $upd2);
                            break;
                        case "categories":
                            $arCats = array();

                            foreach ($value as $categories) {
                                foreach ($categories as $key3 => $value3) {
                                    if ($key3 == "name") {
                                        $arCats[] = strtolower($value3);
                                    }
                                }
                            }

                            // $upd2 = "UPDATE product SET `main_category` = '".addslashes(camelCaseToUnderscores($arCats[rand(0, sizeof($arCats))]))."' WHERE product_id = $id;";
                            $upd2 = "UPDATE product SET `main_category` = '".addslashes(camelCaseToUnderscores($mainCategory))."' WHERE product_id = $id;";
                            // echo $upd2."\n";
                            $res = mysqli_query($link, $upd2);
                        default:
                            break;
                    }
                }
            }

            echo $n++."\r";
        }
    }

    echo $n++."\n";
