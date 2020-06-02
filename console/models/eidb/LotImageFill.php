<?php
namespace console\models\eidb;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\helpers\FileHelper;

use common\models\db\Lot;

use console\traits\Keeper;

use sergmoro1\uploader\models\OneFile;;
use sergmoro1\uploader\behaviors\ImageTransformationBehavior;

class LotImageFill extends Model
{
    const TABLE = '{{%onefile}}';
    const OLD_TABLE = 'lots';
    const SITE  = 'https://ei.ru';

    private static $contextOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ];  

    public static $sizes;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => ImageTransformationBehavior::className()],
        ];
    }

    public function getData($limit, $offset)
    {
        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT id, images, "createdAt", "updatedAt" FROM "eiLot"."'.self::OLD_TABLE.'" WHERE images NOTNULL ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }
        
        $files = [];

        foreach($rows as $row)
        {
            $lot_id = $row['id'];

            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);

            $images = json_decode($row['images']);
            
            foreach($images as $image) {
                $full_file_name = $image->max;
                $full_file_name = (substr($image->max, 0, 4) == 'http')
                    ? $image->max
                    : self::SITE . '/' . $image->max;
                $a = explode('/', $image->max);
                $tmp_name = $a[count($a) - 1];
                $a = explode('.', $tmp_name);
                $ext = $a[count($a) - 1];
                $new_name = 'i' . uniqid() . '.' . $ext;

                $headers = get_headers($full_file_name, 0, stream_context_create(self::$contextOptions));
                if (strpos($headers[0], '200')) {
                    $image = file_get_contents($full_file_name, false, stream_context_create(self::$contextOptions));

                    // OneFile
                    $of = [
                        'model'      => Lot::className(),
                        'parent_id'  => $lot_id,
                        'original'   => $tmp_name,
                        'name'       => $new_name,
                        'subdir'     => $lot_id,
                        'type'       => ('image/' . $ext),
                        'size'       => strlen($image),
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $onefile = new OneFile($of);
                
                    Keeper::validateAndKeep($onefile, $files, $of);

                    $dir = Yii::getAlias('@absolute') . Yii::getAlias('@uploader') . '/lot/' . $lot_id;
                    if (!is_dir($dir))
                        mkdir($dir);
                    file_put_contents($dir . '/' . $tmp_name, $image);
                    
                    $lot = Lot::findOne($lot_id);
                    self::$sizes = $lot->sizes;
                    self::resizeSave($lot->setFilePath($lot_id), $tmp_name, $new_name);
                }
            }
        }
        
        $result = [];

        $result['onefile'] = Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['model', 'parent_id', 'original', 'name', 'subdir', 'type', 'size', 'created_at', 'updated_at'], $files)->execute();
            
        return $result;
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['onefile'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['onefile'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
        }

        return $result;
    }
}