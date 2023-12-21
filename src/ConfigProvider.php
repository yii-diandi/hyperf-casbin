<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Diandi\HyperfCasbin;

use Hyperf\Utils\Collection;
use Hyperf\Utils\Filesystem\Filesystem;
use Diandi\HyperfCasbin\Commands\CacheReset;

class ConfigProvider
{

    public function __invoke(): array
    {
        return [
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for casbin.',
                    'source' => __DIR__ . '/../publish/casbin.php',
                    'destination' => BASE_PATH . '/config/autoload/casbin.php',
                ],
                [
                    'id' => 'model',
                    'description' => 'The model for casbin.',
                    'source' => __DIR__ . '/../publish/casbin-rbac-model.conf',
                    'destination' => BASE_PATH . '/config/autoload/casbin-rbac-model.conf',
                ],
                [
                    'id' => 'config',
                    'description' => 'The config for permission.',
                    'source' => __DIR__ . '/../publish/permission.php',
                    'destination' => BASE_PATH . '/config/autoload/permission.php',
                ],
                [
                    'id' => 'database',
                    'description' => 'The database for permission.',
                    'source' => __DIR__ . '/../database/migrations/create_permission_tables.php.stub',
                    'destination' => $this->getMigrationFileName(),
                ]
            ],
            'commands' => [
                CacheReset::class,
            ],
        ];
    }

    protected function getMigrationFileName(): string
    {
        $timestamp = date('Y_m_d_His');
        $filesystem = new Filesystem();
        return Collection::make(BASE_PATH . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_permission_tables.php');
            })->push(BASE_PATH . "/migrations/{$timestamp}_create_permission_tables.php")
            ->first();
    }

}
