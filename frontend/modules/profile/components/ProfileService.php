<?php


namespace frontend\modules\profile\components;


use common\models\db\Place;
use common\models\db\Profile;
use common\models\db\User;
use frontend\modules\profile\forms\ProfileForm;
use Yii;
use yii\base\Component;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class ProfileService extends Component
{

    /**
     * @var Profile
     */
    protected $profile;

    /**
     * @var ProfileForm
     */
    protected $form;

    /**
     * @param ProfileForm $form
     * @return bool
     * @throws NotFoundHttpException
     */
    public function save(ProfileForm $form)
    {
        $profile = $this->getProfileByUserId(Yii::$app->user->identity->getId());
        $userPlace = $this->getPlaceByUserId(Yii::$app->user->identity->getId());
        $model = Profile::findOne($profile->id);
        $user = User::findOne(Yii::$app->user->identity->getId());
        $place = Place::findOne($userPlace->id);

        if (!$model) {
            $model = new Profile();
        }

        if (!$place) {
            $place = new Place();
        }

        $data = $form->getAttributes();
        $model->first_name = $data[ 'first_name' ];
        $model->last_name = $data[ 'last_name' ];
        $model->middle_name = $data[ 'middle_name' ];
        $model->phone = $data[ 'phone' ];
        $model->birthday = $data[ 'birthday' ];
        $model->gender = $data[ 'gender' ];
        $model->parent_id = \Yii::$app->user->identity->getId();
        $model->model = User::INT_CODE;

        $user->email = $data[ 'email' ];

        $place->city = $data[ 'city' ];
        $place->address = $data[ 'address' ];
        $place->parent_id = $user->id;
        $place->model = User::INT_CODE;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->save() && $user->save() && $place->save()) {
                if ($form->new_password != '') {
                    if ($this->changePassword($user, $form)) {
                        $transaction->commit();
                        return true;
                    }
                    else {
                        $transaction->rollBack();
                        return false;
                    }
                } else {
                    $transaction->commit();
                    return true;
                }
            }

            $transaction->rollBack();
            return false;

        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public function savePhone($phone)
    {
        $profile = $this->getProfileByUserId(Yii::$app->user->identity->getId());
        $model = Profile::findOne($profile->id);

        if (!$model) {
            $model = new Profile();
        }

        $model->phone = $phone;

        $model->parent_id = \Yii::$app->user->identity->getId();
        $model->model = User::INT_CODE;

        if ($model->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return \yii\db\ActiveQuery
     * @throws NotFoundHttpException
     */
    public function findProfile($id)
    {
        $model = Profile::find()->select(['*'])->where(['profile.parent_id' => $id, 'profile.model' => User::INT_CODE])
            ->innerJoin(User::tableName() . 'as u', 'u.id = profile.parent_id')
            ->leftJoin(Place::tableName(), 'place.parent_id = u.id and place.model = :m', ['m' => User::INT_CODE]);

        if (!$model) {
            throw new NotFoundHttpException('The requested model does not exist.');
        }

        return $model;

    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function getProfileByUserId($id)
    {
        $model = Profile::find()->select(['id'])->where(['parent_id' => $id, 'model' => User::INT_CODE])->one();

        if (!$model) {
            throw new NotFoundHttpException('The requested model does not exist.');
        }

        return $model;

    }

    /**
     * @param $id
     * @return array|ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function getPlaceByUserId($id)
    {
        $model = Place::find()->select(['id'])->where(['parent_id' => $id, 'model' => User::INT_CODE])->one();

        if (!$model) {
            throw new NotFoundHttpException('The requested model does not exist.');
        }

        return $model;

    }

    public function changePassword(User $user, ProfileForm $form)
    {
        if ($form->new_password != '' && $form->old_password != '' && $form->repeat_password) {
            if ($user->validatePassword($form->old_password)) {

                if ($form->old_password != $form->new_password) {

                    if ($form->new_password == $form->repeat_password) {
                        $user->setPassword($form->new_password);
                        $form->old_password = $form->new_password = $form->repeat_password = null;
                        return $user->save();
                    } else {
                        $form->addError('repeat_password', 'Пароли не совпадают');
                    }

                } else {
                    $form->addError('new_password', 'Новый пароль не должен совподать со старым');
                }

            } else {
                $form->addError('old_password', 'Не верный пароль');
            }
        }

        return false;
    }
}