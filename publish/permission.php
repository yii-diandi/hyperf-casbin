<?php

return [
    //model设置
    'models' => [
        'permission' =>\Voopoo\Casbin\Models\Permission::class,
        'role' => \Voopoo\Casbin\Models\Role::class,
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
        'key' => 'voopoo.permission.cache',
        'model_key' => 'name',
        'store' => 'default',
    ],
];
