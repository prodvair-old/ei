<?php

use yii\db\Migration;

/**
 * Class m200722_112030_invoice
 * An invoice for any type of product in the system that can be sold to the end user.
 * 
 * Сначала выписывается счет с определением товара и суммой. 
 * Так как товар может быть любым, определение сохраняется в json массиве. 
 * Если счет оплачен, информация о товаре сохраняется в соответствующей таблице - subscription, purchase.
 * Для suscription поле info будет иметь следующий вид:
 * ```php
 * {tariff_id:1, from_at:1534233, till_at:1536300}
 * ``` 
 * Для purchase:
 * ```php
 * {report_id:13}
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
            'info'       => $this->text()->notNull(),
            'sum'        => $this->integer()->notNull(),
            'paid'       => $this->boolean()->defaultValue(false),

            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addCommentOnColumn(self::TABLE, 'product', 'Вид товара - тариф, отчет');
        $this->addCommentOnColumn(self::TABLE, 'info', 'Определенеие товара в json массиве');
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
