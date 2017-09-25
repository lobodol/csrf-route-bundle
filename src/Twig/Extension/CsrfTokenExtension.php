<?php

namespace Genedys\CsrfRouteBundle\Twig\Extension;

use Genedys\CsrfRouteBundle\Manager\CsrfTokenManager;
use Genedys\CsrfRouteBundle\Routing\CsrfRouterInterface;
use Genedys\CsrfRouteBundle\Routing\Router\CsrfRouter;

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
     * @param CsrfRouterInterface $csrfRouter
     * @param CsrfTokenManager    $csrfTokenManager
     */
    public function __construct(CsrfRouterInterface $csrfRouter, CsrfTokenManager $csrfTokenManager)
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
     *
     * @return string
     */
    public function getToken($routeName)
    {
        $token = $this->csrfRouter->getCsrfToken($routeName);

        return $token ? $this->csrfTokenManager->getTokenValue($routeName, $token) : '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'genedys_csrf.csrf_token';
    }
}
