<?php

namespace Elemecca\HipchatBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ElemeccaHipchatExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $desc = array_merge($config['addon'], $config['install']);
        $container->setParameter('elemecca_hipchat.descriptor', $desc);

        $container->setParameter(
            'elemecca_hipchat.doctrine.orm_enabled',
            true
        );
        $container->setParameter(
            'elemecca_hipchat.doctrine.manager_name',
            $config['doctrine']['manager_name']
        );


        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.xml');
    }
}
