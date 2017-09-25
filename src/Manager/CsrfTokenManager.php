<?php

namespace Genedys\CsrfRouteBundle\Manager;

use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Symfony\Component\Routing\Route;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 */
class CsrfTokenManager
{
    /**
     * CsrfToken route option name
     */
    const OPTION_NAME = 'csrf_token';

    /**
     * @var string
     */
    protected $fieldName;

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
    public function getDefaultToken()
    {
        return (new CsrfToken())
            ->setToken($this->fieldName)
            ->setIntention(null)
            ->setMethods('GET')
        ;
    }

    /**
     * @param true|array $option
     * @return CsrfToken|null
     */
    public function getTokenFromOption($option)
    {
        if (true === $option) {
            return $this->getDefaultToken();
        }

        if (!is_array($option)) {
            return null;
        }

        return (new CsrfToken())
            ->setToken(array_key_exists('token', $option) ? $option['token'] : $this->fieldName)
            ->setIntention(array_key_exists('intention', $option) ? $option['intention'] : null)
            ->setMethods(array_key_exists('methods', $option) ? $option['methods'] : 'GET')
        ;
    }

    /**
     * @param Route $route
     * @return CsrfToken|null
     */
    public function getTokenFromRoute(Route $route)
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
