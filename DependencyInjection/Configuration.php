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

                ->scalarNode('key')
                  ->cannotBeEmpty()
                  ->info("The add-on's registered Atlassian Marketplace key")
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
          ->end();

        return $treeBuilder;
    }
}
