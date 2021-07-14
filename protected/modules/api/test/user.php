<?php
return [
    'user' => [

        'POST:login' => [
            'params' => [
                'LoginForm[username]' => 'Trand59est@209String.com',
                'LoginForm[password]' => 'Test String',
                'LoginForm[device_token]' => '12131313',
                'LoginForm[device_type]' => '1',
                'LoginForm[device_name]' => '1'
            ]
        ],

        'GET:check' => [
            'params' => [
                'DeviceDetail[device_token]' => 'harman',
                'DeviceDetail[device_type]' => 'admin',
                'DeviceDetail[device_name]' => '12131313'
            ]
        ],
        'POST:signup' => [
            'params' => [
                'User[full_name]' => 'Test String',
                'User[email]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com',
                'User[password]' => 'Test String',
                'User[role_id]' => '1',
                'User[contact_no]' => '8989898'
            ]
        ],
        'POST:change-password' => [
            'params' => [
                'User[oldPassword]' => 'Test String',
                'User[newPassword]' => 'Test String',
                'User[confirm_password]' => 'Test String',
            ]
        ],
        'POST:forget-password' => [
            'params' => [
                'User[email]' => '',
            ]
        ],
        'POST:update-profile'=>[
            'params' => [
                'User[first_name]' => 'Test string',
                'User[last_name]' => 'Test string',
                'User[email]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com',
                'User[city]' => 'Mohali',
                'User[country]' => 'india',
                'User[profile_file]' => 'abc.jpg',
                'User[contact_no]' => '8989898',
                
            ]
        ],
        'POST:contact-us' => [
            'params' => [
                'Information[full_name]' => '',
                'Information[email]' => '',
                'Information[message]' => ''
            ]
        ],
        'POST:add-address' => [
            'params' => [
                'Address[first_name]' => 'Test string',
                'Address[last_name]' => 'Test string',
                'Address[primary_address]' => 'Test string',
                'Address[secondary_address]' => 'Test string',
                'Address[city]' => 'Mohali',
                'Address[zipcode]' => 'abc.jpg',
                'Address[contact_no]' => '8989898',
            ]
        ],
        'POST:update-address' => [
            'params' => [
                'Address[first_name]' => 'Test string',
                'Address[last_name]' => 'Test string',
                'Address[primary_address]' => 'Test string',
                'Address[secondary_address]' => 'Test string',
                'Address[city]' => 'Mohali',
                'Address[zipcode]' => 'abc.jpg',
                'Address[contact_no]' => '8989898',
            ]
        ],
        'POST:notification' => [
            'params' => [
                'User[notification_status]' => '0',
            ]
        ],
        'Get:default-address' => [
            'params' => [
              
            ]
        ],
        'Get:delete-Address' => [
            'params' => [
                
            ]
        ],

    ]
];