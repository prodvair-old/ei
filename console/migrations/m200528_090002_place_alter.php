<?php

use yii\db\Migration;

/**
 * Class m200528_090002_place_alter
 */
class m200528_090002_place_alter extends Migration
{
    const TABLE = '{{%place}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'district_id', $this->integer());

        $db = \Yii::$app->db;
        $command = $db->createCommand('UPDATE place, district SET district_id = district.id WHERE place.district = district.name');
        $result = $command->execute();

        $this->dropColumn(self::TABLE, 'district');
    }

    public function down()
    {
        $this->addColumn(self::TABLE, 'district', $this->string());

        $db = \Yii::$app->db;
        $command = $db->createCommand('UPDATE place, district SET place.district = district.name WHERE place.district_id = district.id');
        $result = $command->execute();

        $this->dropColumn(self::TABLE, 'district_id');
    }
}
