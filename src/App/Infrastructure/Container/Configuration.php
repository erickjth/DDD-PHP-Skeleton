<?php

namespace App\Infrastructure\Container;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link https://symfony.com/doc/current/components/config/definition.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Tree builder root name
     *
     * @var string
     */
    private $rootName;

    /**
     * Config constructor.
     *
     * @param string $rootName
     */
    public function __construct(string $rootName)
    {
        $this->rootName = $rootName;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root($this->rootName);

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
