<?php

namespace Genedys\CsrfRouteBundle\Routing;

use Symfony\Component\Routing\RouterInterface as BaseRouterInterface;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface CsrfRouterInterface extends BaseRouterInterface, TokenProviderInterface
{
}
