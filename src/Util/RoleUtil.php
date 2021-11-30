<?php
declare(strict_types=1);

namespace Voopoo\Casbin\Util;


use App\Constants\ErrorCode;
use App\Exception\ApiException;
use App\Model\Admin;
use Hyperf\DbConnection\Db;
use Voopoo\Casbin\Enforcer;
use Voopoo\Casbin\Models\Permission;
use Voopoo\Casbin\Models\Role;

class RoleUtil
{
    public function list($param)
    {
        $where = self::sqlMap($param);
        $limit = (int) $param['limit']?:10;
        $res = Role::where($where)
            ->orderByRaw('created_at asc')
            ->paginate($limit);

        foreach ($res as $k => $v) {
            var_dump($v->name);
            $v->user_name = Enforcer::getUsersForRole($v->id);
        }

        return $res;

    }

    /**
     * @return \Hyperf\Database\Model\Collection|static[]
     * @author brady
     * Time: 2021-08-18 14:07:44
     */
    public function  getAll(){
        return Role::get(['id','name','is_disable']);
    }

    /**
     * @return mixed
     * @author brady
     * Time: 2021-08-18 14:07:56
     */
    public  function getAvailable(){
        return Role::where(['is_disable'=>2])->get(['id','name']);
    }

    public function sqlMap($data)
    {
        $where = [];
        if (!empty($data['name']) && isset($data['name'])) {
            $sqlmap = ['name', 'like', "%" . $data['name'] . "%"];
            $where[] = $sqlmap;
        }

        if (!empty($data['is_disable']) && isset($data['is_disable'])) {
            $sqlmap = ['is_disable', '=', $data['is_disable']];
            $where[] = $sqlmap;
        }
        return $where;
    }

    public function create($data)
    {
         return Role::create($data);
    }

    /**
     * 编辑
     * @param $data
     * @return int
     * @author brady
     * Time: 2021-08-18 14:42:12
     */
    public function edit($data)
    {

        $update = [];
        if (!empty($data['name']) && isset($data['name'])) {
            $update['name'] = $data['name'];
        }
        if (!empty($data['description']) && isset($data['description'])) {
            $update['description'] = $data['description'];
        }
        if (!empty($data['is_disable']) && isset($data['is_disable'])) {
            $update['is_disable'] = $data['is_disable'];
        }

        return Role::where(['id' => $data['role_id']])->update($update);

    }

    public function getPermissionsList($role_id){
        $pre= array_column(Enforcer::getPermissionsForUser($role_id),1);
        return Permission::whereIn('url',$pre)->get();
    }

    /**
     * 分配权限
     * @param $data
     * @return bool
     * @author brady
     * Time: 2021-08-18 16:57:05
     */
    public function syncPermissions(int $role_id , array $permission_ids){
        if (!$role_id || empty($permission_ids)) {
            throw new \InvalidArgumentException('param errror');
        }

        $list = Permission::findMany($permission_ids);
        Enforcer::deletePermissionsForUser($role_id);
        foreach ($list as $v){
            if(!empty($v->url)){
                Enforcer::addPermissionForUser($role_id, $v->url, 'any');
            }
        }
        return true;
    }

    public function assignRole($role_id, $user_id)
    {
        $user = Admin::query()->where(['admin_id' => $user_id])->first();
        return $user->assignRole((int)$role_id);
    }

    public function removeRole($role_id, $user_id)
    {
        $user = Admin::query()->where(['admin_id' => $user_id])->first();
        return $user->removeRole((int)$role_id);
    }

    public function getRoleNames($user_id)
    {
        $user = Admin::query()->where(['admin_id' => $user_id])->first();
        return Role::findMany($user->getRoleIds(),['name']);
    }

    /**
     * @param $role_id
     * @param $user_name
     * @return bool
     * @author brady
     * Time: 2021-08-21 17:01:44
     */
    public function updateRole($role_id, $user_name)
    {
        Enforcer::deleteRolesForUser( $user_name);
        Enforcer::addRoleForUser($user_name, $role_id);

        return true;
    }
}