<?php

namespace Genedys\CsrfRouteBundle\Routing\TokenProvider;

use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Genedys\CsrfRouteBundle\Routing\TokenProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
abstract class TokenProvider implements TokenProviderInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @param string $fieldName
     */
    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
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
}
