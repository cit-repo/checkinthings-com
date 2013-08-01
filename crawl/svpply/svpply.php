<?php

    require_once("../inc/db.inc.php");
    require_once("../inc/functions.inc.php");

    $json = '{"meta":{"status":200,"msg":"OK","time":1370016083},"response":{"product":{"type":"Product","id":1893840,"id_str":"1893840","page_title":"Los Pollos Hermanos - Breaking Bad White T-Shirt: Amazon.co.uk: Clothing","page_url":"https:\/\/api.svpply.com\/v1\/products\/1893840\/purchase","price":10,"formatted_price":"$1-20","currency_code":"USD","discount":false,"discount_code":null,"category":"apparel","categories":[{"name":"All Products","url":"https:\/\/api.svpply.com\/v1\/shop.json","text_color":"#FF5B54","border_color":"#FFDEDD","peak_rank":null},{"name":"Men\u2019s","url":"https:\/\/api.svpply.com\/v1\/shop\/mens.json","text_color":"#FF5B54","border_color":"#FFDEDD","peak_rank":null},{"name":"Clothing","url":"https:\/\/api.svpply.com\/v1\/shop\/mens\/clothing.json","text_color":"#782825","border_color":"#782825","peak_rank":null}],"gender":"neutral","store":{"type":"Store","id":14,"id_str":"14","name":"Amazon: UK","slug":"amazon.co.uk","url":"http:\/\/amazon.co.uk","avatar":"https:\/\/s3.amazonaws.com\/assets.svpply.com\/store\/avatar\/default.jpg","description":null,"products_count":5118,"collections_count":2299,"users_followers_count":107,"locations_count":15,"masthead":null,"masthead_height":null,"masthead_width":null,"date_created":"2009-09-25 02:08:13 UTC","date_updated":"2013-05-31 13:40:08 UTC"},"width":385,"height":417,"image":"https:\/\/s3.amazonaws.com\/assets.svpply.com\/medium\/1893840.jpg?1370015830","saves":608,"notes":643,"status_id":1,"date_created":"2012-09-18 14:20:53 UTC","date_updated":"2013-05-31 15:57:10 UTC","user":{"type":"User","id":20356,"id_str":"20356","name":"","username":"wrrn","url":null,"description":null,"location":null,"display_name":"wrrn","avatar":"https:\/\/s3.amazonaws.com\/assets.svpply.com\/avatars\/20356.png?u=1369958516","gender_preference":"none","products_count":315,"owns_count":7,"users_following_count":21,"stores_following_count":12,"searches_following_count":0,"users_followers_count":42,"date_created":"2011-01-10 15:18:59 UTC","date_updated":"2013-05-31 00:01:56 UTC","collections_count":0}}}}';

    $obj = json_decode($json);

    $feed = "svpply";

/*
    type => Product
    id => 1893840
    id_str => 1893840
    page_title => Los Pollos Hermanos - Breaking Bad White T-Shirt: Amazon.co.uk: Clothing
    page_url => https://api.svpply.com/v1/products/1893840/purchase
    price => 10
    formatted_price => $1-20
    currency_code => USD
    discount =>
    discount_code =>
    category => apparel
    categories (array)
    Array
    (
        [0] => stdClass Object
            (
                [name] => All Products
                [url] => https://api.svpply.com/v1/shop.json
                [text_color] => #FF5B54
                [border_color] => #FFDEDD
                [peak_rank] =>
            )

        [1] => stdClass Object
            (
                [name] => Men’s
                [url] => https://api.svpply.com/v1/shop/mens.json
                [text_color] => #FF5B54
                [border_color] => #FFDEDD
                [peak_rank] =>
            )

        [2] => stdClass Object
            (
                [name] => Clothing
                [url] => https://api.svpply.com/v1/shop/mens/clothing.json
                [text_color] => #782825
                [border_color] => #782825
                [peak_rank] =>
            )

    )
    gender => neutral
    store (object)
    stdClass Object
    (
        [type] => Store
        [id] => 14
        [id_str] => 14
        [name] => Amazon: UK
        [slug] => amazon.co.uk
        [url] => http://amazon.co.uk
        [avatar] => https://s3.amazonaws.com/assets.svpply.com/store/avatar/default.jpg
        [description] =>
        [products_count] => 5118
        [collections_count] => 2299
        [users_followers_count] => 107
        [locations_count] => 15
        [masthead] =>
        [masthead_height] =>
        [masthead_width] =>
        [date_created] => 2009-09-25 02:08:13 UTC
        [date_updated] => 2013-05-31 13:40:08 UTC
    )
    width => 385
    height => 417
    image => https://s3.amazonaws.com/assets.svpply.com/medium/1893840.jpg?1370015830
    saves => 608
    notes => 643
    status_id => 1
    date_created => 2012-09-18 14:20:53 UTC
    date_updated => 2013-05-31 15:57:10 UTC
    user (object)
    stdClass Object
    (
        [type] => User
        [id] => 20356
        [id_str] => 20356
        [name] =>
        [username] => wrrn
        [url] =>
        [description] =>
        [location] =>
        [display_name] => wrrn
        [avatar] => https://s3.amazonaws.com/assets.svpply.com/avatars/20356.png?u=1369958516
        [gender_preference] => none
        [products_count] => 315
        [owns_count] => 7
        [users_following_count] => 21
        [stores_following_count] => 12
        [searches_following_count] => 0
        [users_followers_count] => 42
        [date_created] => 2011-01-10 15:18:59 UTC
        [date_updated] => 2013-05-31 00:01:56 UTC
        [collections_count] => 0
    )
*/

    foreach ($obj->response as $key => $value) {
        if ($key == 'product') {
            foreach ($value as $k => $v) {
                if (is_object($v)) {
                    echo $k." (object)\n";
//                    print_r($v);
                } else if (is_array($v)) {
                    echo $k." (array)\n";
//                    print_r($v);
                } else {
                    echo $k."\n";
                    // echo $k." => $v\n";
                }
            }
        }
    }

?>
