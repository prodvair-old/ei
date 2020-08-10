<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use common\models\db\User;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Notification;
use console\traits\Keeper;
use console\traits\District;

/**
 * Users controller
 */
class UsersController extends Controller
{
    use Keeper;
    use District;

    const TABLE = '{{%user}}';

    private static $group = [
        'superAdmin' => User::ROLE_ADMIN, 
        'manager'    => User::ROLE_MANAGER, 
        'agent'      => User::ROLE_AGENT, 
        'user'       => User::ROLE_USER,
    ];

    public function actionUp($limit = 100, $offset = 0)
    {
        // получение пользователей из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $dbn = \Yii::$app->db;
        $selectN = $dbn->createCommand(
            'SELECT * FROM eidb.user ORDER BY "user".id ASC '
        );
        $rowsN = $selectN->queryAll();

        
        $ids = '';
        foreach ($rowsN as  $key => $row) {
            if ($key !== 0) {
                $ids .= ' AND ';
            }
            $ids .= '("user".id != '.$row['id']." AND \"user\".username != '".$row['username']."' AND \"user\".auth_key != '".$row['auth_key']."')";
        }

        $select = $db->createCommand(
            'SELECT * FROM site.user WHERE '.$ids.' ORDER BY "user".id DESC'
        );
        $rows = $select->queryAll();

        // var_dump('SELECT * FROM site.user WHERE '.$ids.' ORDER BY "user".id ASC');
        // die;
        
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
                'role'          => self::getRole($row['role']),
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
                $district = isset($obj->contacts->district) ? $this->districtConvertor($obj->contacts->district) : null;
                $address = isset($obj->contacts->address) ? $obj->contacts->address : $city;
                $geo_lat  = (isset($obg->contacts->geo_lat) && $obg->contacts->geo_lat ? $obg->contacts->geo_lat : null);
                $geo_lon  = (isset($obg->contacts->geo_lon) && $obg->contacts->geo_lon ? $obg->contacts->geo_lon : null);
                if ($city || $address) {
                    $p = [
                        'model'       => User::INT_CODE,
                        'parent_id'   => $user_id,
                        'city'        => $city,
                        'region_id'   => $region_id,
                        'district_id' => $district,
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
        var_dump(Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'username', 'email', 'auth_key', 'password_hash', 'status', 'role', 'created_at', 'updated_at'], $users)->execute(),
        Yii::$app->db->createCommand()->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles)->execute(),
        Yii::$app->db->createCommand()->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places)->execute(),
        Yii::$app->db->createCommand()->batchInsert('{{%notification}}', ['user_id', 'new_picture', 'new_report', 'price_reduction', 'created_at', 'updated_at'], $notifications)->execute());
    }

    public function actionDown()
    {
        $db = \Yii::$app->db;
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE {{%notification}}')->execute();
            $db->createCommand('TRUNCATE TABLE {{%place}}')->execute();
            $db->createCommand('TRUNCATE TABLE {{%profile}}')->execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $db->createCommand('TRUNCATE TABLE {{%notification}} CASCADE')->execute();
            $db->createCommand('TRUNCATE TABLE {{%place}} CASCADE')->execute();
            $db->createCommand('TRUNCATE TABLE {{%profile}} CASCADE')->execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }
    }

    /**
     * @param string $role
     * @return integer role ID
     */
    public static function getRole($role)
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

