<?php

namespace Elemecca\HipchatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elemecca_hipchat');

        $rootNode
          ->children()
            ->arrayNode('addon')
              ->isRequired()
              ->info('Registration info for the HipChat add-on')
              ->children()
                ->scalarNode('key')
                  ->isRequired()->cannotBeEmpty()
                  ->info('A string that uniquely identifies the add-on')
                ->end()

                ->scalarNode('name')
                  ->isRequired()->cannotBeEmpty()
                  ->info('The display name of the add-on')
                ->end()

                ->scalarNode('description')
                  ->isRequired()->cannotBeEmpty()
                  ->info('A short description of the add-on')
                ->end()

                ->scalarNode('homepage')
                  ->cannotBeEmpty()
                  ->info('The URL or route key to the homepage of the add-on')
                ->end()

                ->arrayNode('vendor')
                  ->info('The vendor that maintains the add-on')
                  ->children()
                    ->scalarNode('name')
                      ->isRequired()->cannotBeEmpty()
                      ->info("The vendor's display name")
                    ->end()

                    ->scalarNode('url')
                      ->cannotBeEmpty()
                      ->info("The URL or route key to the vendor's website")
                    ->end()
                  ->end()
                ->end()
              ->end()
            ->end()

            ->arrayNode('install')
              ->addDefaultsIfNotSet()
              ->info('Settings for the add-on installation process')
              ->children()
                ->booleanNode('allow_global')
                  ->defaultTrue()
                  ->info('Whether the add-on can be installed globally')
                ->end()

                ->booleanNode('allow_room')
                  ->defaultTrue()
                  ->info('Whether the add-on can be installed in a single room')
                ->end()
              ->end()
            ->end()

            ->arrayNode('doctrine')
              ->addDefaultsIfNotSet()
              ->children()
                ->scalarNode('manager_name')
                  ->cannotBeEmpty()
                  ->defaultNull()
                  ->info('The name of the ObjectManager to use')
                ->end()
              ->end()
            ->end()
          ->end();

        return $treeBuilder;
    }
}
