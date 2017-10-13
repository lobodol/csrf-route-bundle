<?php

namespace Genedys\CsrfRouteBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Fabien Antoine <fabien@fantoine.fr>
 */
class GenedysCsrfRouteExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('genedys_csrf_route.enabled', $config['enabled']);
        $container->setParameter('genedys_csrf_route.field_name', $config['field_name']);
        $container->setParameter('genedys_csrf_route.token_provider_class', $config['token_provider_class']);
        $container->setParameter('genedys_csrf_route.token_provider_base_class', $config['token_provider_base_class']);
        $container->setParameter('genedys_csrf_route.token_provider_dumper_class', $config['token_provider_dumper_class']);
        $container->setParameter('genedys_csrf_route.token_provider_cache_class', $config['token_provider_cache_class']);


        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');
        $loader->load('twig.xml');

        // Load events only if it's enabled
        if ($config['enabled']) {
            $loader->load('events.xml');
        }
    }
}
