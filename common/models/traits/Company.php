<?php

namespace common\models\traits;

use common\models\db\Organization;
use yii\helpers\ArrayHelper;

trait Company
{
    /**
     * Get organization
     * @return ActiveRecord | null
     */
    public function getOrganization()
    {
        return Organization::findOne([
            'model'     => self::INT_CODE,
            'parent_id' => $this->id,
        ]);
    }

    /**
     * @return Organization[]
     */
    public static function getOrganizationList()
    {
        $res = (new \yii\db\Query())
            ->select(['id', 'title'])
            ->from(Organization::tableName())
            ->where(['model' => self::INT_CODE])
            ->all();

        return ArrayHelper::map($res, 'id', 'title');
    }

    /**
     * Get place
     * @return ActiveRecord | null
     */
    public function getPlace()
    {
        return Place::findOne([
            'model'     => self::INT_CODE,
            'parent_id' => $this->id,
        ]);
    }
}
