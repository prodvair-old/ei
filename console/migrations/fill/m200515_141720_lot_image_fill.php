<?php

use yii\db\Migration;
use yii\helpers\Url;
use common\models\db\Lot;
use console\traits\Keeper;
use sergmoro1\uploader\models\OneFile;;
use sergmoro1\uploader\behaviors\ImageTransformationBehavior;

/**
 * Class m200515_141720_lot_image_fill
 */
class m200515_141720_lot_image_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%onefile}}';
    const LIMIT = 1000;
    const SITE  = 'https://ei.ru';

    private $contextOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ];  

    public $sizes;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => ImageTransformationBehavior::className()],
        ];
    }
    
    public function safeUp()
    {
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot"."lots" WHERE images NOTNULL'
        );
        $result = $select->queryAll();
        
        $offset = 0;
   
        // добавление информации по лотам
        while ($offset < $result[0]['count']) {

            $this->insertPoole($db, $offset);

            $offset = $offset + self::LIMIT;

            $sleep = rand(1, 3);
            sleep($sleep);
        }

    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        
        if (isset(Yii::$app->queue))
            FileHelper::removeDirectory(Yii::getAlias('@console/runtime/queue'));
        $dir = Yii::getAlias('@absolute') . Yii::getAlias('@uploader') . '/lot';
        FileHelper::removeDirectory(Yii::getAlias($dir));
    }

    private function insertPoole($db, $offset)
    {
        $files = [];

        $query = $db->createCommand(
            'SELECT id, images, "createdAt", "updatedAt" FROM "eiLot"."lots" WHERE images NOTNULL ORDER BY lots.id ASC LIMIT ' . self::LIMIT . ' OFFSET ' . $offset
        );

        $rows = $query->queryAll();

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

                $headers = get_headers($full_file_name, 0, stream_context_create($this->contextOptions));
                if (strpos($headers[0], '200')) {
                    $image = file_get_contents($full_file_name, false, stream_context_create($this->contextOptions));

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
                
                    $this->validateAndKeep($onefile, $files, $of);

                    $dir = Yii::getAlias('@absolute') . Yii::getAlias('@uploader') . '/lot/' . $lot_id;
                    if (!is_dir($dir))
                        mkdir($dir);
                    file_put_contents($dir . '/' . $tmp_name, $image);
                    
                    $lot = Lot::findOne($lot_id);
                    $this->sizes = $lot->sizes;
                    $this->resizeSave($lot->setFilePath($lot_id), $tmp_name, $new_name);
                }
            }
        }
        
        $this->batchInsert(self::TABLE, ['model', 'parent_id', 'original', 'name', 'subdir', 'type', 'size', 'created_at', 'updated_at'], $files);
    }
}
