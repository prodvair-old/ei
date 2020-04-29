<?php

use yii\db\Migration;

use common\models\db\User;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Notification;

/**
 * Class m200428_181633_user_fill
 */
class m200428_181633_user_fill extends Migration
{
    const TABLE = '{{%user}}';

    private static $group = [
        'superAdmin' => User::GROUP_ADMIN, 
        'manager'    => User::GROUP_MANAGER, 
        'agent'      => User::GROUP_AGENT, 
        'user'       => User::GROUP_USER,
    ];

    public function safeUp()
    {
        // получение пользователей из существующего справочника
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM site.user ORDER BY "user".created_at'
        );
        $rows = $select->queryAll();
        
        // добавление пользователей в новый справочник
        foreach($rows as $row) {
            // User
            $obj = json_decode($row['info']);
            $created_at = strtotime($row['created_at']);
            $updated_at = strtotime($row['updated_at']);
            $user_id = $row['id'];

            $user = new User([
                'id'            => $user_id,
                'username'      => $row['username'],
                'email'         => $row['username'],
                'auth_key'      => $row['auth_key'],
                'password_hash' => $row['password'],
                'status'        => ($row['status'] ? 1 : 2),
                'group'         => self::getGroup($row['role']),
                'created_at'    => $created_at,
                'updated_at'    => $updated_at,
            ]);
            if ($user->save()) {
                // Profile
                $inn = isset($obj->inn) && $obj->inn ? $obj->inn : null;
                $phone = isset($obj->contacts->phones) && $obj->contacts->phones[0] ? $obj->contacts->phones[0] : '';
                $first_name = isset($obj->first_name) && $obj->first_name ? $obj->first_name : '';
                $last_name = isset($obj->last_name) && $obj->last_name ? $obj->last_name : '';
                if ($inn || $phone || $first_name || $last_name) {
                    $profile = new Profile([
                        'model'         => User::INT_CODE,
                        'parent_id'     => $user_id,
                        'inn'           => $inn,
                        'gender'        => (isset($obj->sex) && $obj->sex ? ($obj->sex == 'Мужской' ? Profile::GENDER_MALE : Profile::GENDER_FEMALE) : null),
                        'birthday'      => (isset($obj->birthday) && $obj->birthday ? self::getBirthday($obj->birthday) : null),
                        'phone'         => ($phone ? str_replace(['+7', ' ', '-', '(', ')'], '', $phone) : ''),
                        'first_name'    => ($first_name ?: '-'),
                        'last_name'     => $last_name,
                        'middle_name'   => (isset($obj->middle_name) ? $obj->middle_name : ''),
                        'created_at'    => $created_at,
                        'updated_at'    => $updated_at,
                    ]);
                    $profile->save();
                }

                // Place
                $city = isset($obj->contacts->city) ? $obj->contacts->city : '';
                $region = isset($obj->contacts->region) ? $obj->contacts->region : null;
                $district = isset($obj->contacts->district) ? $obj->contacts->district : '';
                $address = isset($obj->contacts->address) ? $obj->contacts->address : $city;
                if ($city || $address) {
                    $place = new Place([
                        'model'         => User::INT_CODE,
                        'parent_id'     => $user_id,
                        'city'          => $city,
                        'region'        => $region,
                        'district'      => $district,
                        'address'       => $address,
                        'created_at'    => $created_at,
                        'updated_at'    => $updated_at,
                    ]);
                    $place->save();
                }

                // Notification
                $notifications = isset($obj->notifications) && $obj->notifications ? $obj->notifications : null;
                if ($notifications) {
                    $notification = new Notification([
                        'user_id'         => $user_id,
                        'new_picture'     => (isset($notifications->new_picture) ? $notifications->new_picture : false),
                        'new_report'      => (isset($notifications->new_report) ? $notifications->new_report : false),
                        'price_reduction' => (isset($notifications->price_reduction) ? $notifications->price_reduction : false),
                        'created_at'      => $created_at,
                        'updated_at'      => $updated_at,
                    ]);
                    $notification->save();
                }
            }
        }
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE {{%notification}} CASCADE')->execute();
        $db->createCommand('TRUNCATE TABLE {{%place}} CASCADE')->execute();
        $db->createCommand('TRUNCATE TABLE {{%profile}} CASCADE')->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }

    /**
     * @param string $role
     * @return integer group ID
     */
    public static function getGroup($role)
    {
        return self::$group[$role];
    }

    /**
     * @param string $birthday - 13.05.2001
     * @return integer Unixtimestamp
     */
    public static function getBirthday($birthday)
    {
        $a = [];
        if ($birthday)
            $a = explode('.', $birthday);
        return (count($a) == 3)
            ? strtotime($a[2] . '-' . $a[1] . '-' . $a[0])
            : null;
    }
}
