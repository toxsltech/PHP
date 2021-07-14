<?php
return [
    'user' => [

        'GET:check' => [
            'params' => [
                'DeviceDetail[device_token]' => 'harman',
                'DeviceDetail[device_type]' => 'admin',
                'DeviceDetail[device_name]' => '12131313'
            ]
        ],
        'POST:signup' => [
            'params' => [
                'User[first_name]' => 'Test String',
                'User[last_name]' => 'Test String',
                'User[email]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com',
                'User[password]' => 'Test String',
                'User[role_id]' => '4',
                'User[social_link]' => 'abc.com',
                'User[profession]' => 'Artist',
                'User[agent_representation]' => 'Test String',
                'User[establishment_id]' => 1
            ]
        ],
        'POST:change-password' => [
            'params' => [
                'User[newPassword]' => 'Test String',
                'User[oldPassword]' => 'Test String',
                'User[confirm_password]' => 'Test String'
            ]
        ],

        'POST:login' => [
            'params' => [
                'LoginForm[username]' => 'Trand59est@209String.com',
                'LoginForm[password]' => 'Test String',
                'LoginForm[role_id]' => '4',
                'LoginForm[date_of_birth]' => '1997-06-26',
                'LoginForm[device_token]' => '12131313',
                'LoginForm[device_type]' => '1',
                'LoginForm[device_name]' => '1'
            ]
        ],

        'POST:forgot-password' => [
            'params' => [
                'User[email]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com'
            ]
        ],

        "POST:mark-favorite?access-token=" => [
            'params' => [
                "Item[model_id]" => 1
            ]
        ],

        'POST:contact-us?access-token=' => [
            'params' => [
                'Information[full_name]' => 'test',
                'Information[email]' => 'test@gmail.com',
                'Information[description]' => 'testing'
            ]
        ],

        'POST:add-news' => [
            'params' => [
                'News[title]' => 'Test String',
                'News[image_file]' => 'Test String',
                'News[summary]' => 'Test String',
                'News[description]' => 'Test String',
                'News[start_date]' => 'Test String',
                'News[end_date]' => 'Test String',
                'News[start_time]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com',
                'News[end_time]' => 'Test String',
                'News[duration]' => '4',
                'News[budget]' => 'abc.com',
                'News[domain_id]' => 'Artist',
                'News[location]' => 'Test String',
                'News[type_id]' => 1
            ]
        ],

        'POST:profile-update' => [
            'params' => [
                'User[first_name]' => 'Test String',
                'User[last_name]' => 'Test String',
                'User[profile_file]' => 'Test String',
                'User[address]' => 'Test String',
                'User[latitude]' => '123456789',
                'User[longitude]' => '123456789',
                'User[email]' => 'Trand' . rand(0, 499) . 'est@' . rand(0, 499) . 'String.com',
                'User[role_id]' => '4',
                'User[contact_no]' => '123456789',
                'User[emoji_id]' => '1',
                'User[activity_id]' => '1',
                'User[target_area_id]' => '1',
                'User[language_id]' => '1',
                'User[domain_id]' => '1'
            ]
        ]
    ]
];

