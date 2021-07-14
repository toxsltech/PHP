<?php /**
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
	"charity" => [
		"POST:create" => [
            'params' => [
		

			"Charity[title]" => \Faker\Factory::create()->text(10),
			"Charity[image_file]" => \Faker\Factory::create()->text(10),
			"Charity[goal_amount]" => \Faker\Factory::create()->text(10),
			"Charity[description]" => \Faker\Factory::create()->text(10),
			"Charity[raised_amount]" => \Faker\Factory::create()->text(10),
			"Charity[min_amount]" => \Faker\Factory::create()->text(10),
			"Charity[max_amount]" => \Faker\Factory::create()->text(10),
			"Charity[state_id]" => 0,
			"Charity[type_id]" => 0,
			"Charity[created_on]" => "2021-01-07 19:11:16",
			"Charity[created_by_id]" => 1,
			]
],
		"POST:update/{id}"=>  [
            'params' => [		
		
			"Charity[title]" => \Faker\Factory::create()->text(10),
			"Charity[image_file]" => \Faker\Factory::create()->text(10),
			"Charity[goal_amount]" => \Faker\Factory::create()->text(10),
			"Charity[description]" => \Faker\Factory::create()->text(10),
			"Charity[raised_amount]" => \Faker\Factory::create()->text(10),
			"Charity[min_amount]" => \Faker\Factory::create()->text(10),
			"Charity[max_amount]" => \Faker\Factory::create()->text(10),
			"Charity[state_id]" => 0,
			"Charity[type_id]" => 0,
			"Charity[created_on]" => "2021-01-07 19:11:16",
			"Charity[created_by_id]" => 1,
			]
],
	    "POST:charity-payment"=>  [
	        'params' => [
	            
	            "CharityDetail[amount]" => \Faker\Factory::create()->text(10),
	            "CharityDetail[charity_id]" => \Faker\Factory::create()->text(10),
	            
	        ]
	    ],
		"GET:index" => [
            ],
		"GET:{id}" =>  [
            ],
		"DELETE:{id}" =>  [
            ],
	]
];
?>
