<?php

namespace Genedys\CsrfRouteBundle\Routing\TokenProvider;

use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Genedys\CsrfRouteBundle\Routing\TokenProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class TokenProvider implements TokenProviderInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @param string $fieldName
     * @param RouteCollection $routeCollection
     */
    public function __construct($fieldName, RouteCollection $routeCollection)
    {
        $this->fieldName = $fieldName;
        $this->routeCollection = $routeCollection;
    }

    /**
     * @return CsrfToken
     */
    protected function getDefaultToken()
    {
        $token = new CsrfToken();
        $token->setToken($this->fieldName);
        $token->setIntention(null);
        $token->setMethods('GET');

        return $token;
    }

    /**
     * @param true|array $option
     * @return CsrfToken|null
     */
    protected function getTokenFromOption($option)
    {
        if (true === $option) {
            return $this->getDefaultToken();
        }

        if (!is_array($option)) {
            return null;
        }

        $token = new CsrfToken();
        $token->setToken(array_key_exists('token', $option) ? $option['token'] : $this->fieldName);
        $token->setIntention(array_key_exists('intention', $option) ? $option['intention'] : null);
        $token->setMethods(array_key_exists('methods', $option) ? $option['methods'] : 'GET');

        return $token;
    }

    /**
     * @param Route $route
     * @return CsrfToken|null
     */
    protected function getTokenFromRoute(Route $route)
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
        $this->getTokenFromRoute($this->routeCollection->get($name));
    }
}
