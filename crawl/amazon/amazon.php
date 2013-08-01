<?php
    require_once("../inc/db.inc.php");
    require_once("../inc/functions.inc.php");
    require_once('amazon/lib/AmazonECS.class.php');

    define('AWS_API_KEY', 'AKIAJ5BQFD5IU6V5KTZA');
    define('AWS_API_SECRET_KEY', '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');
    define('AWS_ASSOCIATE_TAG', substr(md5(microtime()),0,8));

    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'com', AWS_ASSOCIATE_TAG);

    $amazonEcs->associateTag(AWS_ASSOCIATE_TAG);

    $feed = "amazon";

    $categories = array(
                        'Amazon Instant Video',
                        'Appliances',
                        'Apps for Android',
                        'Arts, Crafts & Sewing',
                        'Automotive',
                        'Baby',
                        'Beauty',
                        'Books',
                        'Cell Phones & Accessories',
                        'Clothing & Accessories',
                        'Collectibles',
                        'Computers',
                        'Credit Cards',
                        'Electronics',
                        'Gift Cards Store',
                        'Grocery & Gourmet Food',
                        'Health & Personal Care',
                        'Home & Kitchen',
                        'Industrial & Scientific',
                        'Jewelry',
                        'Kindle Store',
                        'Magazine Subscriptions',
                        'Movies & TV',
                        'MP3 Music',
                        'Music',
                        'Musical Instruments',
                        'Office Products',
                        'Patio, Lawn & Garden',
                        'Pet Supplies',
                        'Shoes',
                        'Software',
                        'Sports & Outdoors',
                        'Tools & Home Improvement',
                        'Toys & Games',
                        'Video Games',
                        'Watches'
                        );

    $categories = array('Shoes');

    foreach ($categories as $category) {
        $res = $amazonEcs->category($category)->responseGroup('Large')->search("Pirelli");

        $operation = $res->OperationRequest;

        $items = $res->Items;

//      print_r($items); die;

        foreach ($items as $key => $item) {
            echo $key."\n";


//            Request
//            TotalResults
//            TotalPages
//            MoreSearchResultsUrl
//            Item
            if ($key == 'Item') {
                foreach ($item as $hit) {
                    recursiveDumpPro($hit);
                    die;
                }
            }
        }
    }

?>