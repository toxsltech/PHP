<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
return [
    "product" => [
        "POST:create" => [
            'params' => [

                "Product[title]" => \Faker\Factory::create()->text(10),
                "Product[amount]" => \Faker\Factory::create()->text(10),
                "Product[image_file]" => \Faker\Factory::create()->text(10),
                "Product[description]" => \Faker\Factory::create()->text,
                "Product[benifits]" => \Faker\Factory::create()->text(10),
                "Product[specification]" => \Faker\Factory::create()->text(10),
                "Product[medical_specification]" => \Faker\Factory::create()->text(10),
                "Product[state_id]" => 0,
                "Product[type_id]" => 0,
                "Product[created_on]" => "2020-11-23 15:37:19",
                "Product[created_by_id]" => 1
            ]
        ],
        "POST:update/{id}" => [
            'params' => [

                "Product[title]" => \Faker\Factory::create()->text(10),
                "Product[amount]" => \Faker\Factory::create()->text(10),
                "Product[image_file]" => \Faker\Factory::create()->text(10),
                "Product[description]" => \Faker\Factory::create()->text,
                "Product[benifits]" => \Faker\Factory::create()->text(10),
                "Product[specification]" => \Faker\Factory::create()->text(10),
                "Product[medical_specification]" => \Faker\Factory::create()->text(10),
                "Product[state_id]" => 0,
                "Product[type_id]" => 0,
                "Product[created_on]" => "2020-11-23 15:37:19",
                "Product[created_by_id]" => 1
            ]
        ],

        "POST:place-order" => [

            'params' => [

                "Order[payment_type]" => 1,
                "Order[total_amount]" => 1,
                "Order[address_id]" => 1,
                "itemJson" => '[{"item_id":1,"amount":"100","quantity":8,"type_id":1}]'
            ]
        ],
        "GET:package-list" => [],
        "GET:{id}" => [],
        "DELETE:{id}" => []
    ]
];
?>
