<?php
declare(strict_types = 1);

namespace Voopoo\Casbin\Commands;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Voopoo\Casbin\PermissionRegistrar;

/**
 * Class CacheReset
 * @package Voopoo\Casbin\Commands
 * @Command
 */
class CacheReset extends HyperfCommand
{

    protected $name = 'permission:cache-reset';

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container= $container;
        parent::__construct('permission:cache-reset');
        $this->setDescription('Reset the permission cache');
    }

    public function handle()
    {
        if ($this->container->get(PermissionRegistrar::class)->forgetCachedPermissions()) {
            $this->line('Permission cache flushed.');
        } else {
            $this->line('Unable to flush cache.');
        }
    }

}