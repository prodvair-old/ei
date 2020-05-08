<?php

use yii\db\Migration;

/**
 * Class m200508_090815_owner
 */
class m200508_090815_owner extends Migration
{
    const TABLE = '{{%owner}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),

            'organizer_id'  => $this->bigInteger()->notNull(),

            'link'          => $this->text(),
            'logo'          => $this->text(),
            'bg'            => $this->text(),
            'color_btn'     => $this->string(),
            'color_1'       => $this->string(),
            'color_2'       => $this->string(),
            'color_3'       => $this->string(),
            'color_4'       => $this->string(),
            'color_5'       => $this->string(),
            'color_6'       => $this->string(),

            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-owner-organization', self::TABLE, 'organizer_id', '{{%organization}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'organizer_id', 'Компания торговой площадки');
		$this->addCommentOnColumn(self::TABLE, 'link', 'Ссылка транслит на сайте');
		$this->addCommentOnColumn(self::TABLE, 'logo', 'Лотготип');
		$this->addCommentOnColumn(self::TABLE, 'bg', 'Фоновая картинка');
		$this->addCommentOnColumn(self::TABLE, 'color_btn', 'Цвет кнопки');
		$this->addCommentOnColumn(self::TABLE, 'color_1', 'Цвет на странице 1');
		$this->addCommentOnColumn(self::TABLE, 'color_2', 'Цвет на странице 2');
		$this->addCommentOnColumn(self::TABLE, 'color_3', 'Цвет на странице 3');
		$this->addCommentOnColumn(self::TABLE, 'color_4', 'Цвет на странице 4');
		$this->addCommentOnColumn(self::TABLE, 'color_5', 'Цвет на странице 5');
		$this->addCommentOnColumn(self::TABLE, 'color_6', 'Цвет на странице 6');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-owner-organization',  self::TABLE);
        $this->dropTable(self::TABLE);
    }

}
