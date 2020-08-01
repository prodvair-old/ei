<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%invoice}}`.
 */
class m200728_221848_add_columns_to_invoice_table extends Migration
{
    const TABLE = '{{%invoice}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'orderExternalId','string not null unique');
        $this->addColumn(self::TABLE, 'orderInnerId','string not null unique');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'orderExternalId');
        $this->dropColumn(self::TABLE, 'orderInnerId');
    }
}
