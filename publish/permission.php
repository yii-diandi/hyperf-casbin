<?php

return [
    //model设置
    'models' => [
        'permission' =>\Diandi\HyperfCasbin\Models\Permission::class,
        'role' => \Diandi\HyperfCasbin\Models\Role::class,
    ],
    //表名设置
    'table_names' => [
        'roles' => 'admin_roles',
        'permissions' => 'admin_permissions',
        'logs' => 'admin_logs',

    ],
    'guard' => 'web',
    'cache' => [
        'expiration_time' => 86400,
        'key' => 'diandi.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],
];
