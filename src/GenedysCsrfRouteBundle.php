<?php

namespace Genedys\CsrfRouteBundle;

use Genedys\CsrfRouteBundle\DependencyInjection\Compiler\SetRouterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 */
class GenedysCsrfRouteBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetRouterPass());
    }
}
