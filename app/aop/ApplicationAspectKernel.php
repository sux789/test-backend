<?php

namespace app\aop;


use Go\Core\AspectKernel;
use Go\Core\AspectContainer;
use app\aop\ServiceAspect;
/**
 * Application Aspect Kernel
 */
class ApplicationAspectKernel extends AspectKernel
{

    /**
     * Configure an AspectContainer with advisors, aspects and pointcuts
     *
     * @param AspectContainer $container
     *
     * @return void
     */
    protected function configureAop(AspectContainer $container)
    {
        echo __FILE__,__LINE__;
        $container->registerAspect(new ServiceAspect());
    }
}
