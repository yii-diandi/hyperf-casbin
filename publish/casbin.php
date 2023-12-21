<?php

use Diandi\HyperfCasbin\Adapters\Mysql\DatabaseAdapter;

return [
    /*
     * Casbin model setting.
     */
    'model' => [
        // Available Settings: "file", "text"
        'config_type' => 'file',
        'config_file_path' => BASE_PATH . '/config/autoload/casbin-rbac-model.conf',
        'config_text' => '',
    ],
    /*
     * Casbin adapter .
     */
    'adapter' => [
        'class' => DatabaseAdapter::class,
        'constructor' => [
            'tableName' => 'casbin_rule'
        ],
    ],
    'log' => [
        'enabled' => false,
    ]
];
