<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Manuel Aguirre
 */
class OptimeEmailExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
//        $configuration = new Configuration();
//        $config = $this->processConfiguration($configuration, $configs);

//        $container->addResource(new DirectoryResource(dirname(__DIR__)));

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config'),
            $container->getParameter('kernel.environment'),
        );
        $loader->load('services.yaml');
    }
}