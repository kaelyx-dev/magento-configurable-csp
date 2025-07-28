<?php

namespace Kaelyx\ConfigurableCSP\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup, 
        \Magento\Framework\Setup\ModuleContextInterface $context
    ){
        $installer = $setup;
        $installer->startSetup();

        if($installer->tableExists(\Kaelyx\ConfigurableCSP\Helper\Constants::DATABASE_TABLE_NAME)){
            $installer->endSetup();
            return;
        }

        $table = $installer->getConnection()->newTable(
            $installer->getTable(\Kaelyx\ConfigurableCSP\Helper\Constants::DATABASE_TABLE_NAME)
        )->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'directive',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Directive'
        )->addColumn(
            'value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            ['nullable' => false],
            'Value'
        )->setComment('Kaelyx Configurable CSP Table');

        $installer->getConnection()->createTable($table);
        $installer->getConnection()->addIndex(
            $installer->getTable(\Kaelyx\ConfigurableCSP\Helper\Constants::DATABASE_TABLE_NAME),
            $setup->getIdxName(
                $installer->getTable(\Kaelyx\ConfigurableCSP\Helper\Constants::DATABASE_TABLE_NAME),
                ['directive'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['directive'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        );
        $installer->endSetup();
    }
}