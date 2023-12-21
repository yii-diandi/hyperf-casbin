<?php

declare (strict_types=1);
namespace Diandi\HyperfCasbin\Models;

use Hyperf\DbConnection\Model\Model;
use Diandi\HyperfCasbin\Guard;
use Diandi\HyperfCasbin\Contracts\Role as RoleContract;


class Log extends Model
{

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('permission.guard');

        parent::__construct($attributes);

        $this->setTable(config('permission.table_names.logs'));
    }

    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        return static::query()->create($attributes);
    }

    protected $casts =['params'=>'array'];



}