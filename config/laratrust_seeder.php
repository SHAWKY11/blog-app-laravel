<?php

return [
    'roles_structure' => [
        'owner' => [
            'users' => 'c,r,u,d',
        ],
        
        'super_admin' =>[
            'users' => 'c,r,u',
        ],
        'admin' =>[
            'users' => 'r',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
