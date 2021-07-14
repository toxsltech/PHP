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
	"comment" => [
		"POST:create" => [
            'params' => [
		

			"Comment[model_id]" => 1,
			"Comment[model_type]" => \Faker\Factory::create()->text(10),
			"Comment[comment]" => \Faker\Factory::create()->text,
			"Comment[state_id]" => 0,
			"Comment[type_id]" => 0,
			"Comment[created_on]" => "2021-07-13 17:51:28",
			"Comment[created_by_id]" => 1,
			]
],
		"POST:update/{id}"=>  [
            'params' => [		
		
			"Comment[model_id]" => 1,
			"Comment[model_type]" => \Faker\Factory::create()->text(10),
			"Comment[comment]" => \Faker\Factory::create()->text,
			"Comment[state_id]" => 0,
			"Comment[type_id]" => 0,
			"Comment[created_on]" => "2021-07-13 17:51:28",
			"Comment[created_by_id]" => 1,
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
