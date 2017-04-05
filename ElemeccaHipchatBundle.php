<?php

namespace Elemecca\HipchatBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElemeccaHipchatBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $this->addDoctrineMappingsPass($container);
    }

    private function addDoctrineMappingsPass(ContainerBuilder $container)
    {
        $mappingDir = realpath(__DIR__ . '/Resources/config/doctrine');
        $mappings = [ $mappingDir       => 'Elemecca\HipchatBundle\Model' ];
        $aliases  = [ 'ElemeccaHipchat' => 'Elemecca\HipchatBundle\Model' ];

        if (class_exists(DoctrineOrmMappingsPass::class)) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createXmlMappingDriver(
                    $mappings,
                    ['elemecca_hipchat.doctrine.manager_name'],
                    'elemecca_hipchat.doctrine.orm_enabled',
                    $aliases
                )
            );
        }
    }
}