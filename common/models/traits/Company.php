<?php

namespace common\models\traits;

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
