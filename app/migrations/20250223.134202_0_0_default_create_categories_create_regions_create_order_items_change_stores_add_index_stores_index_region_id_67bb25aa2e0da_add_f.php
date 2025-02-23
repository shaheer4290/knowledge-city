<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault7a85c6e0a2e2bcff7e9af393bbabc85c extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('categories')
        ->addColumn('category_id', 'bigPrimary', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => true,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('category_name', 'string', ['nullable' => false, 'defaultValue' => null, 'length' => 200, 'size' => 255])
        ->setPrimaryKeys(['category_id'])
        ->create();
        $this->table('regions')
        ->addColumn('region_id', 'bigPrimary', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => true,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('region_name', 'string', ['nullable' => false, 'defaultValue' => null, 'length' => 200, 'size' => 255])
        ->setPrimaryKeys(['region_id'])
        ->create();
        $this->table('products')
        ->addColumn('category_categoryId', 'bigInteger', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => false,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addIndex(['category_categoryId'], ['name' => 'products_index_category_categoryid_67bb25aa2e0c9', 'unique' => false])
        ->addForeignKey(['category_categoryId'], 'categories', ['category_id'], [
            'name' => 'products_foreign_category_categoryid_67bb25aa2e0d0',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->update();
        $this->table('order_items')
        ->addColumn('order_item_id', 'bigPrimary', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => true,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('order_id', 'bigInteger', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => false,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('product_id', 'bigInteger', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => false,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('quantity', 'integer', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 11,
            'autoIncrement' => false,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('unit_price', 'decimal', ['nullable' => false, 'defaultValue' => null, 'precision' => 10, 'scale' => 2])
        ->addColumn('total_amount', 'decimal', ['nullable' => false, 'defaultValue' => null, 'precision' => 10, 'scale' => 2])
        ->addColumn('order_orderId', 'bigInteger', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => false,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addColumn('product_productId', 'bigInteger', [
            'nullable' => false,
            'defaultValue' => null,
            'size' => 20,
            'autoIncrement' => false,
            'unsigned' => false,
            'zerofill' => false,
        ])
        ->addIndex(['order_orderId'], ['name' => 'order_items_index_order_orderid_67bb25aa2e0f2', 'unique' => false])
        ->addIndex(['product_productId'], ['name' => 'order_items_index_product_productid_67bb25aa2e10a', 'unique' => false])
        ->addForeignKey(['order_orderId'], 'orders', ['order_id'], [
            'name' => 'order_items_foreign_order_orderid_67bb25aa2e0f6',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->addForeignKey(['product_productId'], 'products', ['product_id'], [
            'name' => 'order_items_foreign_product_productid_67bb25aa2e10e',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->setPrimaryKeys(['order_item_id'])
        ->create();
        $this->table('stores')
        ->addIndex(['region_id'], ['name' => 'stores_index_region_id_67bb25aa2e0da', 'unique' => false])
        ->addForeignKey(['region_id'], 'regions', ['region_id'], [
            'name' => 'stores_foreign_region_id_67bb25aa2e0de',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->update();
    }

    public function down(): void
    {
        $this->table('stores')
        ->dropForeignKey(['region_id'])
        ->dropIndex(['region_id'])
        ->update();
        $this->table('order_items')->drop();
        $this->table('products')
        ->dropForeignKey(['category_categoryId'])
        ->dropIndex(['category_categoryId'])
        ->dropColumn('category_categoryId')
        ->update();
        $this->table('regions')->drop();
        $this->table('categories')->drop();
    }
}
