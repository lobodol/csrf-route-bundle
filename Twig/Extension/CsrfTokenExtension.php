<?php

namespace Genedys\CsrfRouteBundle\Twig\Extension;

use Genedys\CsrfRouteBundle\Manager\CsrfTokenManager;
use Genedys\CsrfRouteBundle\Routing\Router\CsrfRouter;

/**
 * Class CsrfTokenExtension
 */
class CsrfTokenExtension extends \Twig_Extension
{
    /**
     * @var CsrfRouter
     */
    protected $csrfRouter;

    /**
     * @var CsrfTokenManager
     */
    protected $csrfTokenManager;

    /**
     * CsrfTokenExtension constructor.
     *
     * @param CsrfRouter       $csrfRouter
     * @param CsrfTokenManager $csrfTokenManager
     */
    public function __construct(CsrfRouter $csrfRouter, CsrfTokenManager $csrfTokenManager)
    {
        $this->csrfRouter       = $csrfRouter;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('csrf_token', [$this, 'getToken']),
        ];
    }

    /**
     * @param string $routeName
     * @param array   $parameters
     *
     * @return string
     */
    public function getToken($routeName, array $parameters = [])
    {
        $route = $this->csrfRouter->getRouteCollection()->get($routeName);
        if (null !== $route) {
            $this->csrfTokenManager->updateRoute($route, $routeName, $parameters);

            return (isset($parameters['_token']) && !empty($parameters['_token'])) ? $parameters['_token'] : '';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'genedys_csrf.csrf_token';
    }
}
