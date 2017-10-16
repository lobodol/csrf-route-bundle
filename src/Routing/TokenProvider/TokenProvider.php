<?php

namespace Genedys\CsrfRouteBundle\Routing\TokenProvider;

use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
final class TokenProvider extends AbstractTokenProvider
{
    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName, RouteCollection $routeCollection)
    {
        parent::__construct($fieldName);

        $this->routeCollection = $routeCollection;
    }

    /**
     * @param Route $route
     * @return CsrfToken|null
     */
    private function getTokenFromRoute(Route $route)
    {
        // Check if route has the option
        if (!$route->hasOption(self::OPTION_NAME)) {
            return null;
        }

        // Get option
        $option = $route->getOption(self::OPTION_NAME);
        if (!$option) {
            return null;
        }

        // Get token
        return $this->getTokenFromOption($option);
    }

    /**
     * Returns CSRF configuration for the given route.
     *
     * @param string $name
     * @return CsrfToken|null
     */
    public function getCsrfToken($name)
    {
        return $this->getTokenFromRoute($this->routeCollection->get($name));
    }
}
