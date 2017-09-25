<?php

namespace Genedys\CsrfRouteBundle\Routing\Router;

use Genedys\CsrfRouteBundle\Handler\TokenHandlerInterface;
use Genedys\CsrfRouteBundle\Manager\CsrfTokenManager;
use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Genedys\CsrfRouteBundle\Routing\CsrfRouterInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 */
class CsrfRouter implements CsrfRouterInterface
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var Router
     */
    protected $parent;

    /**
     * @var CsrfTokenManager
     */
    protected $tokenManager;

    /**
     * @var TokenHandlerInterface
     */
    protected $tokenHandler;

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param Router $router
     */
    public function setParentRouter(Router $router)
    {
        $this->parent = $router;
    }

    /**
     * @param CsrfTokenManager $tokenManager
     */
    public function setTokenManager(CsrfTokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param TokenHandlerInterface $tokenHandler
     */
    public function setTokenHandler(TokenHandlerInterface $tokenHandler)
    {
        $this->tokenHandler = $tokenHandler;
    }

    /**
     * @param RequestContext $context
     */
    public function setContext(RequestContext $context)
    {
        $this->parent->setContext($context);
    }

    /**
     * @return RequestContext
     */
    public function getContext()
    {
        return $this->parent->getContext();
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->parent->getRouteCollection();
    }

    /**
     * @param string $pathinfo
     * @return boolean
     */
    public function match($pathinfo)
    {
        return $this->parent->match($pathinfo);
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool|string $referenceType
     * @return string
     */
    public function generate($name, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        // Add Csrf token if required
        if ($this->enabled) {
            $token = $this->getCsrfToken($name);

            if ($token) {
                $parameters[$token->getToken()] = $this->tokenHandler->getToken($token->getIntention() ?: $name);
            }
        }

        return $this->parent->generate(
            $name, $parameters, $referenceType
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function matchRequest(Request $request)
    {
        return $this->parent->matchRequest($request);
    }

    /**
     * @param string $name
     * @return CsrfToken|null
     */
    public function getCsrfToken($name)
    {
        return $this->tokenManager->getTokenFromRoute($this->getRouteCollection()->get($name));
    }
}
