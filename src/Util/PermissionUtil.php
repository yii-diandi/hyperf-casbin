<?php
declare(strict_types=1);

namespace Diandi\HyperfCasbin\Util;


use App\Constants\ErrorCode;
use App\Exception\ApiException;
use App\Model\Admin;
use Hyperf\Utils\ApplicationContext;
use Diandi\HyperfCasbin\Enforcer;
use Diandi\HyperfCasbin\Models\Permission;
use Diandi\HyperfCasbin\PermissionRegistrar;

class PermissionUtil
{
    /**
     * @param $data
     * @param int $is_update
     * @param int $permission_id
     * @return $this|bool|\Hyperf\Database\Model\Model
     * @author brady
     * Time: 2021-08-21 15:47:36
     */
    public function create($data,$is_update = 0,$permission_id = 0)
    {
        $param = [
            'name' => $data['role_name'],
            'display_name' => $data['display_name'],
            'role_name' => $data['role_name'],
            'parent_id' => $data['parent_id']??0,
            'sort' => $data['sort']??0,
            'type' => $data['type']??1,
        ];
        if(isset($data['url']) && !empty($data['url'])){
            $param['url']=strtolower($data['url']);
        }else{
            $param['url']='';
        }


        if($is_update){
            $permission= Permission::find($permission_id);
            $permission->name = $param['name'];
            $permission->display_name = $param['display_name'];
            $permission->role_name = $param['role_name'];
            $permission->parent_id = $param['parent_id'];
            $permission->sort = $param['sort'];
            $permission->type = $param['type'];
            $permission->url = $param['url'];
            $return = $permission->save();
        }else{
            $return = Permission::create($param);
        }

        ApplicationContext::getContainer()->get(PermissionRegistrar::class)->forgetCachedPermissions();
        return $return;
    }


    /**
     * @param int $parentId
     * @param bool $isUrl
     * @param null $permission
     * @return \Hyperf\Utils\Collection
     * @author brady
     * Time: 2021-08-21 15:47:47
     */
    public function getMenuList($parentId = 0, $isUrl = true, $permission = null)
    {
        return Permission::getMenuList($parentId, $isUrl, $permission);

    }

    /**
     * @param $user_id
     * @return mixed
     * @author brady
     * Time: 2021-08-21 15:47:58
     */
    public function getMenu($user_id)
    {
        $user = Admin::query()->where(['admin_id' => $user_id])->first();
        return $user->getMenu();
    }

    /**
     * @param $user_id
     * @return mixed
     * @author brady
     * Time: 2021-08-21 15:48:03
     */
    public function getAllPermissions($user_id)
    {
        $user = Admin::query()->where(['admin_id' => $user_id])->first();
        return $user->getAllPermissions();

    }

    public  function delPermission($per_id){
        $permisssion=Permission::find($per_id);
        if(empty($permisssion)){
            throw new \InvalidArgumentException('PARAM_ERROR');
        }
        $res=$permisssion->delete();
        if(empty($permisssion->url)){
            Enforcer::deletePermission($permisssion->url,'any');
        }
        return true;
    }
}