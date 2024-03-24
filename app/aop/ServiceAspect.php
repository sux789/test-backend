<?php


namespace app\aop;

use Go\Aop\Aspect;
use Go\Aop\Before;
use Go\Aop\Intercept\MethodInvocation;
use Go\Aop\JoinPoint;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Aspect as AspectAnnotation;

/**
 * @Aspect
 */
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
