<?php

namespace backend\models\Editors\Querys;

/**
 * This is the ActiveQuery class for [[\backend\models\Editors\LotEditor]].
 *
 * @see \backend\models\Editors\LotEditor
 */
class Lot extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \backend\models\Editors\LotEditor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \backend\models\Editors\LotEditor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
