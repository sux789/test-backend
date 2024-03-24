<?php

namespace app\common;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;

class ServiceAspect implements Aspect
{
    /**
     * @Around("execution(public app\service\*->*())")
     */
    public function aroundPublicMethods(MethodInvocation $invocation)
    {
        echo "Before " . $invocation->getMethod()->getName() . "\n";
        $result = $invocation->proceed();
        echo "After " . $invocation->getMethod()->getName() . "\n";
        return $result;
    }
}