<?php

namespace Hakam\MultiTenancyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @category Database
 *
 * @author   Ramy Hakam <ramyhakam1@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('hakam_multi_tenancy');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->variableNode('tenant_database_className')->info('Tenant dbs configuration Class Name')->defaultValue('TenantDb')->end()
            ->variableNode('tenant_database_identifier')->info('tenant db column name to get db configuration')->defaultValue('id')->end()
            ->arrayNode('tenant_connection')->info('tenant entity manager connection configuration')
            ->ignoreExtraKeys()
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('url')->defaultValue('mysql://root:password@127.0.0.1:3306/tenant1?serverVersion=8&charset=utf8mb4')->end()
            ->variableNode('host')->defaultValue('127.0.0.1')->end()
            ->variableNode('port')->defaultValue('3306')->end()
            ->variableNode('driver')->defaultValue('pdo_mysql')->end()
            ->variableNode('charset')->defaultValue('utf8')->end()
            ->variableNode('server_version')->defaultValue('5.7')->end()
            ->variableNode('dbname')->info('default tenant database to initialise the tenant connection')->defaultValue('tenant_db')->end()
            ->variableNode('user')->info('default tenant database username')->defaultValue('root')->end()
            ->variableNode('password')->info('default tenant database password')->defaultNull()->end()
            ->end()
            ->end()
            ->end()
            ->children()
            ->arrayNode('tenant_migration')
            ->info('tenant db migration configurations, Its recommended to have a different migration for tenants dbs than you main migration config ')
            ->ignoreExtraKeys()
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('tenant_migration_namespace')->defaultValue('DoctrineMigrations\Tenant')->end()
            ->variableNode('tenant_migration_path')->defaultValue('%kernel.project_dir%/migrations/Tenant')->end()
            ->end()
            ->end()
            ->end()
            ->children()
            ->arrayNode('tenant_entity_manager')
            ->info('tenant entity manger configuration , which is used to manage tenant entities')
            ->ignoreExtraKeys()
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('tenant_naming_strategy')->defaultValue('doctrine.orm.naming_strategy.default')->end()
            ->arrayNode('mappings')
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->beforeNormalization()
            ->ifString()
            ->then(static function ($v) {
                return ['type' => $v];
            })
            ->end()
            ->treatNullLike([])
            ->treatFalseLike(['mapping' => false])
            ->performNoDeepMerging()
            ->children()
            ->scalarNode('mapping')->defaultValue(true)->end()
            ->scalarNode('type')->end()
            ->scalarNode('dir')->end()
            ->scalarNode('alias')->end()
            ->scalarNode('prefix')->end()
            ->booleanNode('is_bundle')->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('mapping')
            ->info('tenant Entity Manager mapping configuration, Its recommended to have a different mapping config than your main entity config')
            ->ignoreExtraKeys()
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('type')->defaultValue('attribute')->info('mapping type default attribute')->end()
            ->variableNode('dir')->defaultValue('%kernel.project_dir%/src/Entity')->info('directory of tenant entities, it could be different from main directory')->end()
            ->variableNode('prefix')->defaultValue('App\Tenant')->info('Tenant entities prefix example " #App\Entity\Tenant" ')->end()
            ->variableNode('alias')->info('Tenant entities alias example " Tenant " ')->end()
            ->variableNode('is_bundle')->defaultValue(false)->info('Tenant entities alias example " Tenant " ')->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
