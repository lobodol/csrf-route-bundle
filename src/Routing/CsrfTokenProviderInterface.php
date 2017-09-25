<?php

namespace Genedys\CsrfRouteBundle\Routing;

use Genedys\CsrfRouteBundle\Model\CsrfToken;

interface CsrfTokenProviderInterface
{
    /**
     * Returns CSRF configuration for the given route.
     *
     * @param string $name
     * @return CsrfToken|null
     */
    public function getCsrfToken($name);
}
