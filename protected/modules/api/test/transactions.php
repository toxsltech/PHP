<?php


return [
    "transactions" => [
        "POST:card-delete" => [
            
        ],
        "POST:set-default"=>  [
            
        ],
        "GET:card-list" => [
        ],
        "POST:add-card" => [
            'params' => [
                "customer_name" => 'test',
                'card_number' => '',
                'expiry_date' => '',
                'cvc' => '',
                'is_default' => '1'
                
                
            ]
        ],
        "POST:booking-payment" => [
            'params' => [
                "booking_id" => 'test',
                'price' => ''
                
                
                
            ]
        ],
        "POST:order-payment" => [
            'params' => [
                'PaymentTransaction[transaction_id]' => 'test',
                'PaymentTransaction[payment_status]' => '1',
                'PaymentTransaction[order_id]'=>'1',
//                 'PaymentTransaction[type_id]'=>1
                
                
                
            ]
        ],
    ]
];
?>
