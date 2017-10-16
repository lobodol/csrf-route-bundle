<?php

namespace Genedys\CsrfRouteBundle\EventListener;

use Genedys\CsrfRouteBundle\Handler\TokenHandlerInterface;
use Genedys\CsrfRouteBundle\Routing\CsrfRouterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 */
class ControllerListener implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @var CsrfRouterInterface
     */
    protected $router;

    /**
     * @var TokenHandlerInterface
     */
    protected $tokenHandler;

    /**
     * @param CsrfRouterInterface   $router
     * @param TokenHandlerInterface $tokenHandler
     */
    public function __construct(
        CsrfRouterInterface $router,
        TokenHandlerInterface $tokenHandler
    ) {
        $this->router         = $router;
        $this->tokenHandler   = $tokenHandler;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        // Get route name
        $routeName = $request->attributes->get('_route');
        if (!$routeName) {
            return;
        }

        // Validate route
        $this->validateRoute($routeName, $request);
    }

    /**
     * @param string $routeName
     * @param Request $request
     */
    public function validateRoute($routeName, Request $request)
    {
        $token = $this->router->getCsrfToken($routeName);
        if (!$token) {
            return;
        }

        // Check HTTP method
        if (!in_array($request->getMethod(), $token->getMethods())) {
            return;
        }

        // Validate token
        $query = $request->query;
        if (!$query->has($token->getToken())) {
            $this->accessDenied();
        }

        $valid = $this->tokenHandler->isTokenValid(
            $token->getIntention() ?: $routeName,
            $query->get($token->getToken())
        );

        if (!$valid) {
            $this->accessDenied();
        }
    }

    /**
     * @throws AccessDeniedHttpException
     */
    protected function accessDenied()
    {
        throw new AccessDeniedHttpException('Invalid CSRF token');
    }
}
