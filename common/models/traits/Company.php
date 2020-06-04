<?php

namespace common\models\traits;

use common\models\db\Place;
use common\models\db\Organization;

trait Company
{
    /**
     * Get organization.
     * 
     * @return yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['parent_id' => 'id'])->where(['model' => $this->intCode]);
    }

    /**
     * Get place.
     * 
     * @return yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['parent_id' => 'id'])->where(['model' => $this->intCode]);
    }
}
