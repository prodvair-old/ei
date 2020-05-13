<?php

use yii\db\Migration;

use common\models\db\User;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Notification;
use console\traits\Keeper;

/**
 * Class m200428_181633_user_fill
 */
class m200428_181633_user_fill extends Migration
{
    use Keeper;
    
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
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM site.user ORDER BY "user".created_at'
        );
        $rows = $select->queryAll();
        
        $usernames = [];
        
        $users = [];
        $profiles = [];
        $places = [];
        $notifications = [];
        
        // добавление пользователей в новый справочник
        foreach($rows as $row) {
            // пропустить дубли
            $username = strtolower($row['username']);
            if (in_array($username, $usernames))
                continue;
            else
                $usernames[] = $username;
            
            // User
            $obj = json_decode($row['info']);
            $created_at = strtotime($row['created_at']);
            $updated_at = strtotime($row['updated_at']);
            $user_id = $row['id'];

            $u = [
                'id'            => $user_id,
                'username'      => $username,
                'email'         => $username,
                'auth_key'      => $row['auth_key'],
                'password_hash' => $row['password'],
                'status'        => ($row['status'] ? 1 : 2),
                'group'         => self::getGroup($row['role']),
                'created_at'    => $created_at,
                'updated_at'    => $updated_at,
            ];
            $user = new User($u);
            
            if ($this->validateAndKeep($user, $users, $u)) {
                
                // Profile
                $inn = isset($obj->inn) && $obj->inn ? $obj->inn : null;
                $phone = isset($obj->contacts->phones) && $obj->contacts->phones[0] ? $obj->contacts->phones[0] : '';
                $first_name = isset($obj->first_name) && $obj->first_name ? $obj->first_name : '';
                $last_name = isset($obj->last_name) && $obj->last_name ? $obj->last_name : '';
                
                if ($inn || $first_name || $last_name) {
                    $p = [
                        'model'       => User::INT_CODE,
                        'parent_id'   => $user_id,
                        'activity'    => Profile::ACTIVITY_SIMPLE,
                        'inn'         => $inn,
                        'gender'      => (isset($obj->sex) && $obj->sex ? ($obj->sex == 'Мужской' ? Profile::GENDER_MALE : Profile::GENDER_FEMALE) : null),
                        'birthday'    => (isset($obj->birthday) && $obj->birthday ? self::getBirthday($obj->birthday) : null),
                        'phone'       => ($phone ? str_replace(['+7', ' ', '-', '(', ')'], '', $phone) : ''),
                        'first_name'  => ($first_name ?: '-'),
                        'last_name'   => $last_name,
                        'middle_name' => (isset($obj->middle_name) ? $obj->middle_name : ''),
                        'created_at'  => $created_at,
                        'updated_at'  => $updated_at,
                    ];
                    $profile = new Profile($p);
                    $this->validateAndKeep($profile, $profiles, $p);
                }

                // Place
                $city = isset($obj->contacts->city) ? $obj->contacts->city : '';
                $region_id = isset($obj->contacts->region) ? $obj->contacts->region : null;
                $district = isset($obj->contacts->district) ? $obj->contacts->district : '';
                $address = isset($obj->contacts->address) ? $obj->contacts->address : $city;
                $geo_lat  = (isset($obg->contacts->geo_lat) && $obg->contacts->geo_lat ? $obg->contacts->geo_lat : null);
                $geo_lon  = (isset($obg->contacts->geo_lon) && $obg->contacts->geo_lon ? $obg->contacts->geo_lon : null);
                if ($city || $address) {
                    $p = [
                        'model'       => User::INT_CODE,
                        'parent_id'   => $user_id,
                        'city'        => $city,
                        'region_id'   => $region_id,
                        'district'    => $district,
                        'address'     => ($address ?: '-'),
                        'geo_lat'     => $geo_lat,
                        'geo_lon'     => $geo_lon,
                        'created_at'  => $created_at,
                        'updated_at'  => $updated_at,
                    ];
                    $place = new Place($p);
                    $this->validateAndKeep($place, $places, $p);
                }

                // Notification
                $know_about = isset($obj->notifications) && $obj->notifications ? $obj->notifications : null;
                if ($know_about) {
                    $n = [
                        'user_id'         => $user_id,
                        'new_picture'     => (isset($know_about->new_picture) ? ($know_about->new_picture ? true : false) : false),
                        'new_report'      => (isset($know_about->new_report) ? ($know_about->new_report ? true : false) : false),
                        'price_reduction' => (isset($know_about->price_reduction) ? ($know_about->price_reduction ? true : false) : false),
                        'created_at'      => $created_at,
                        'updated_at'      => $updated_at,
                    ];
                    $notification = new Notification($n);
                    $this->validateAndKeep($notification, $notifications, $n);
                }
            }
        }
        $this->batchInsert(self::TABLE, ['id', 'username', 'email', 'auth_key', 'password_hash', 'status', 'group', 'created_at', 'updated_at'], $users);
        $this->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
        $this->batchInsert('{{%notification}}', ['user_id', 'new_picture', 'new_report', 'price_reduction', 'created_at', 'updated_at'], $notifications);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
        $db->createCommand('TRUNCATE TABLE {{%notification}}')->execute();
        $db->createCommand('TRUNCATE TABLE {{%place}}')->execute();
        $db->createCommand('TRUNCATE TABLE {{%profile}}')->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
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
