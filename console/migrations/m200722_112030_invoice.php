<?php

use yii\db\Migration;

/**
 * Class m200722_112030_invoice
 * An invoice for any type of product in the system that can be sold to the end user.
 * 
 * Сначала выписывается счет с определением товара и суммой. 
 * Так как товар может быть любым, некоторые поля сохраняются в json массиве. 
 * Если счет оплачен, информация о товаре сохраняется в соответствующей таблице - subscription, purchase.
 * Для subscription поле info будет иметь следующий вид:
 * ```php
 * {from_at:1534233, till_at:1536300}
 * ``` 
 */
class m200722_112030_invoice extends Migration
{
    const TABLE = '{{%invoice}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'product'    => $this->smallInteger()->notNull(),
            'parent_id'  => $this->bigInteger()->notNull(),
            'user_id'    => $this->bigInteger(),
            'info'       => $this->text()->notNull(),
            'sum'        => $this->integer()->notNull(),
            'paid'       => $this->boolean()->defaultValue(false),

            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnColumn(self::TABLE, 'product', 'Вид товара (или Код модели) - тариф, отчет');
        $this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели');
        $this->addCommentOnColumn(self::TABLE, 'user_id', 'Юзер, запросивший счет');
        $this->addCommentOnColumn(self::TABLE, 'info', 'Дополнительные параметры товара в json массиве');
        $this->addCommentOnColumn(self::TABLE, 'sum', 'Сумма');
        $this->addCommentOnColumn(self::TABLE, 'paid', 'Оплачен');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
