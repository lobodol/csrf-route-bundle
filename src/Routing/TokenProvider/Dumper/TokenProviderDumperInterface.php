<?php

namespace Genedys\CsrfRouteBundle\Routing\TokenProvider\Dumper;

use Symfony\Component\Routing\RouteCollection;

/**
 * TokenProviderDumperInterface is the interface that all token provider dumper classes must implement.
 *
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface TokenProviderDumperInterface
{
    /**
     * Gets the routes to dump.
     *
     * @return RouteCollection
     */
    public function getRoutes();

    /**
     * Dumps a set of routes to a string representation of executable code
     * that can then be used to generate CSRF token for such a route.
     *
     * @param array $options
     *
     * @return string
     */
    public function dump(array $options = []);
}
