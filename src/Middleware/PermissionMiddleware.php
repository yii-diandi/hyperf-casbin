<?php
declare(strict_types=1);

namespace Diandi\HyperfCasbin\Middleware;


use Diandi\HyperfCasbin\Enforcer;
use Diandi\HyperfCasbin\Exceptions\UnauthorizedException;
use Diandi\HyperfCasbin\Models\Permission;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Diandi\HyperfCasbin\Util\ActionLogUtil;

class PermissionMiddleware implements MiddlewareInterface
{

    /**
     * @var ActionLogUtil
     */
    protected $actionLogUtil;

    public function __construct(ActionLogUtil $actionLogUtil)
    {
        $this->actionLogUtil =  $actionLogUtil;
    }



    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //去掉路由参数
        $uri=$request->getUri()->getPath();
        $route=substr($uri,7);
        $permission = Permission::where(['url' => $route])->first();
        $user_name = $request->getAttribute('user_name');
        
        if ($user_name && (empty($permission) || (!empty($permission) && Enforcer::enforce($user_name,strtolower($route),'any')))) {
            //记录日志
            $this->actionLogUtil->procEss($route);
            return $handler->handle($request);
        }
        throw new UnauthorizedException('无权进行该操作',4004);
    }
}