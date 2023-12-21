<?php
declare(strict_types=1);

namespace Diandi\HyperfCasbin\Util;

use App\Model\Admin;
use Diandi\HyperfCasbin\Models\AdminLog;
use Diandi\HyperfCasbin\Exceptions\UnauthorizedException;
use Diandi\HyperfCasbin\Models\Log;
use Diandi\HyperfCasbin\Models\Permission;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Di\Annotation\Inject;

/**
 * Class ActionLogService
 * @package Diandi\HyperfCasbin\Service
 */
class ActionLogUtil
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public  function  __construct(RequestInterface $req, ServerRequestInterface $request,LoggerFactory $loggerFactory)
    {
        $this->req =  $req;
        $this->request = $request;
        $this->logger = $loggerFactory->get('admin_action_log', 'default');
    }



    /**
     * @var RequestInterface
     */
    protected $req;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    public function procEss($route='')
    {
        //去掉路由参数
        if(empty($route)){
            $dispatcher = $this->request->getAttribute('Hyperf\HttpServer\Router\Dispatched');
            $route = $dispatcher->handler->route;
        }
        $permission = Permission::getPermissions(['name' => $route])->first();
        $user_name = $this->request->getAttribute('user_name');
        $user = Admin::where('user_name',$user_name)->first();


            try {
                $param = $this->req->all();
                $admin_log = new Log();
                $admin_log->admin_id = $user->admin_id;
                $admin_log->user_name = $user->user_name;
                $admin_log->method = $route;
                $server_param = $this->request->getServerParams('remote_addr');
                $ip = $server_param['remote_addr'];

                $name = Permission::where("url", "=", $route)->get();

                if ($name->isNotEmpty()) {
                    $name = $name->toArray();
                    $description = $name[0]['display_name'];

                    if ($name[0]['parent_id'] != 0) {
                        $parent_name = Permission::where("id", "=", $name[0]['parent_id'])->get();
                        foreach ($parent_name as $item) {
                            $display_name = $item->display_name;
                            $description = $display_name . ":" . $description;
                        }
                    }
                    $admin_log->description = $description;
                }else{
                    $admin_log->description = "公共操作";
                }

                $admin_log->params = $param;
                $admin_log->ip = $ip;
                $rs=$admin_log->save();
            } catch (\Throwable $throwable) {
                $this->recordErrorLog($throwable);
            }

    }


    /**
     * @Inject
     * @var LoggerFactory
     */
    protected $loggerFactory;

    private function recordErrorLog(\Throwable $throwable)
    {
        $this->logger->error("error:", ['detail' => sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile())]);
    }

}