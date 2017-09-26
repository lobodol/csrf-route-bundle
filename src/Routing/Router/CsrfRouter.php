<?php

namespace Genedys\CsrfRouteBundle\Routing\Router;

use Genedys\CsrfRouteBundle\Handler\TokenHandlerInterface;
use Genedys\CsrfRouteBundle\Model\CsrfToken;
use Genedys\CsrfRouteBundle\Routing\CsrfRouterInterface;
use Genedys\CsrfRouteBundle\Routing\TokenProvider\Dumper\TokenProviderDumperInterface;
use Genedys\CsrfRouteBundle\Routing\TokenProviderInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Config\ConfigCacheFactory;
use Symfony\Component\Config\ConfigCacheFactoryInterface;
use Symfony\Component\Config\ConfigCacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class CsrfRouter implements CsrfRouterInterface
{
    /**
     * @var TokenHandlerInterface
     */
    protected $tokenHandler;

    /**
     * @var Router
     */
    protected $parent;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var TokenProviderInterface
     */
    protected $tokenProvider;

    /**
     * @var ConfigCacheFactoryInterface|null
     */
    protected $configCacheFactory;

    public function __construct(
        array $options,
        TokenHandlerInterface $tokenHandler,
        Router $parent
    )
    {
        $this->options = $options;
        $this->tokenHandler = $tokenHandler;
        $this->parent = $parent;
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
        if ($this->options['enabled']) {
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
        return $this->getTokenProvider()->getCsrfToken($name);
    }

    /**
     * @return TokenProviderInterface
     */
    public function getTokenProvider()
    {
        if (null !== $this->tokenProvider) {
            return $this->tokenProvider;
        }

        if (null === $this->options['cache_dir'] || null === $this->options['token_provider_cache_class']) {
            $this->tokenProvider = new $this->options['token_provider_class']($this->options['field_name'], $this->getRouteCollection());
        } else {
            $cache = $this->getConfigCacheFactory()->cache(
                $this->options['cache_dir'].'/'.$this->options['token_provider_cache_class'].'.php',
                function (ConfigCacheInterface $cache) {
                    $dumper = $this->getTokenProviderDumperInstance();

                    $options = array(
                        'class' => $this->options['token_provider_cache_class'],
                        'base_class' => $this->options['token_provider_base_class'],
                        'field_name' => $this->options['field_name'],
                    );

                    $cache->write($dumper->dump($options), $this->getRouteCollection()->getResources());
                }
            );

            require_once $cache->getPath();

            $this->tokenProvider = new $this->options['token_provider_cache_class']();
        }

        return $this->tokenProvider;
    }

    /**
     * @return TokenProviderDumperInterface
     */
    protected function getTokenProviderDumperInstance()
    {
        return new $this->options['token_provider_dumper_class']($this->getRouteCollection());
    }

    /**
     * Sets the ConfigCache factory to use.
     *
     * @param ConfigCacheFactoryInterface $configCacheFactory
     */
    public function setConfigCacheFactory(ConfigCacheFactoryInterface $configCacheFactory)
    {
        $this->configCacheFactory = $configCacheFactory;
    }

    /**
     * Provides the ConfigCache factory implementation, falling back to a default implementation if necessary.
     *
     * @return ConfigCacheFactoryInterface
     */
    protected function getConfigCacheFactory()
    {
        if (null === $this->configCacheFactory) {
            $this->configCacheFactory = new ConfigCacheFactory($this->options['debug']);
        }

        return $this->configCacheFactory;
    }
}
