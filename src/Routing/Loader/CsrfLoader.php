<?php

namespace Genedys\CsrfRouteBundle\Routing\Loader;

use Genedys\CsrfRouteBundle\Routing\TokenProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Routing\Route;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 */
class CsrfLoader extends AnnotatedRouteControllerLoader
{
    /**
     * Configures the CSRF token options
     *
     * @param Route             $route  A route instance
     * @param \ReflectionClass  $class  A ReflectionClass instance
     * @param \ReflectionMethod $method A ReflectionClass method
     * @param mixed             $annot  The annotation class instance
     *
     * @throws \LogicException When the service option is specified on a method
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        parent::configureRoute($route, $class, $method, $annot);

        /** @var \Genedys\CsrfRouteBundle\Annotation\CsrfToken */
        $annotation = $this->reader->getMethodAnnotation($method, '\\Genedys\\CsrfRouteBundle\\Annotation\\CsrfToken');
        if (null !== $annotation) {
            // Store the CsrfToken options on Route options
            $route->setOption(TokenProviderInterface::OPTION_NAME, $annotation->toOption());
        }
    }
}
