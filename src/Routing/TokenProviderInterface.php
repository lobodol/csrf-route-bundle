<?php

namespace Genedys\CsrfRouteBundle\Routing;

use Genedys\CsrfRouteBundle\Model\CsrfToken;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface TokenProviderInterface
{
    /**
     * Returns CSRF configuration for the given route.
     *
     * @param string $name
     * @return CsrfToken|null
     */
    public function getCsrfToken($name);
}
