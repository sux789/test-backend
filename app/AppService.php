<?php
declare (strict_types=1);

namespace app;

use app\common\ServiceAspect;
use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册

        $this->app->bind('aspect', ServiceAspect::class);
    }

    public function boot()
    {
        // 服务启动


    }
}
