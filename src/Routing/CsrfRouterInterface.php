<?php

namespace Genedys\CsrfRouteBundle\Routing;

use Symfony\Component\Routing\RouterInterface as BaseRouterInterface;

interface CsrfRouterInterface extends BaseRouterInterface, CsrfTokenProviderInterface
{
}
