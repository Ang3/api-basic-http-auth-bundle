<?php

namespace Ang3\Bundle\ApiBasicHttpAuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}.
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ang3_api_basic_http_auth');

        $treeBuilder
            ->getRootNode()
            ->children()
            ->end()
        ;

        return $treeBuilder;
    }
}
