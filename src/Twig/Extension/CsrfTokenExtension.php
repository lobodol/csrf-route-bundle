<?php

namespace Genedys\CsrfRouteBundle\Twig\Extension;

use Genedys\CsrfRouteBundle\Handler\TokenHandlerInterface;
use Genedys\CsrfRouteBundle\Routing\CsrfRouterInterface;
use Genedys\CsrfRouteBundle\Routing\Router\CsrfRouter;

class CsrfTokenExtension extends \Twig_Extension
{
    /**
     * @var CsrfRouter
     */
    protected $csrfRouter;

    /**
     * @var TokenHandlerInterface
     */
    protected $tokenHandler;

    /**
     * @param CsrfRouterInterface   $csrfRouter
     * @param TokenHandlerInterface $tokenHandler
     */
    public function __construct(CsrfRouterInterface $csrfRouter, TokenHandlerInterface $tokenHandler)
    {
        $this->csrfRouter       = $csrfRouter;
        $this->tokenHandler = $tokenHandler;
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

        return $token ? $this->tokenHandler->getToken($token->getIntention() ?: $routeName) : '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'genedys_csrf.csrf_token';
    }
}
