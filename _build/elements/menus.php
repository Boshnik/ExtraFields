<?php

return [
    'extrafields' => [
        'description' => 'extrafields_menu_desc',
        'action' => 'resource',
    ],
    'extrafields_resource' => [
        'parent' => 'extrafields',
        'description' => 'extrafields_resource_menu_desc',
        'action' => 'resource',
        'menuindex' => 1,
    ],
    'extrafields_user' => [
        'parent' => 'extrafields',
        'description' => 'extrafields_user_menu_desc',
        'action' => 'user',
        'menuindex' => 2,
    ],
];