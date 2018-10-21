<?php

namespace App\Tests;

use App\Infrastructure\Persistence\Doctrine\Type\OrderIdDoctrineType;
use App\Infrastructure\Persistence\Doctrine\Type\RouteDoctrineType;
use App\Infrastructure\Persistence\Doctrine\Type\StatusDoctrineType;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase as TC;

class TestCase extends TC
{
    protected $entityManager;
    protected $schemaTool;

    public function getTestEntityManager(): EntityManager
    {
        if (null === $this->entityManager) {

            \Doctrine\DBAL\DriverManager::getConnection(array(
                'driver' => 'pdo_sqlite',
                'dbname' => ':memory:'
            ));
            $config = new \Doctrine\ORM\Configuration();

            $config->setAutoGenerateProxyClasses(true);
            $config->setProxyDir(\sys_get_temp_dir());
            $config->setProxyNamespace(get_class($this) . '\Entities');
            $config->setMetadataDriverImpl(
                new \Doctrine\ORM\Mapping\Driver\XmlDriver(
                    array(
                        __DIR__ . '/../src/Infrastructure/Persistence/Doctrine/ORM'
                    )
                )
            );
            
            $config->setNamingStrategy(new \Doctrine\ORM\Mapping\UnderscoreNamingStrategy());
            $config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
            $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
            
            $this->entityManager = EntityManager::create(array(
                'driver' => 'pdo_sqlite',
                'memory' => true
            ), $config);
            
            //Add custom DDD types to map ValueObjects correctly
            if (!Type::hasType('order_id')) {
                Type::addType('order_id', OrderIdDoctrineType::class);
            }

            if (!Type::hasType('status_doctrine_type')) {
                Type::addType('status_doctrine_type', StatusDoctrineType::class);
            }

            if (!Type::hasType('route_doctrine_type')) {
                Type::addType('route_doctrine_type', RouteDoctrineType::class);
            }

        }

        return $this->entityManager;
    }

    public function createEntitySchema($entityNameOrNamespace, $pathToEntityDir = null): void
    {
        if (!is_null($pathToEntityDir)) {
            $dir = opendir($pathToEntityDir);

            $entityNameOrNamespace = trim($entityNameOrNamespace, '\\');

            while($file = readdir($dir)) {
                if (0 !== strpos($file, '.')) {
                    $entityClass = $entityNameOrNamespace . '\\' . str_replace('.php', '', $file);
                    $this->createEntitySchema($entityClass);
                }
            }

            return;
        }

        if (null === $this->schemaTool) {
            $this->schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->getTestEntityManager());
        }
        $schema = $this->getTestEntityManager()->getClassMetadata($entityNameOrNamespace);
        $this->schemaTool->dropSchema(array($schema));
        $this->schemaTool->createSchema(array($schema));
    }
}
