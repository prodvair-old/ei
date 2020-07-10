<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\db\SearchQueries;
use frontend\modules\models\LotSearch;
use frontend\modules\models\Category;
use common\models\User;

/**
 * Search Preset Controller
 * Проверка поисковых запросов пользователей
 */
class SearchPresetController extends Controller
{
    // php yii search-preset
    public function actionIndex($step = 100, $delay = 'y', $sort = 'new') 
    {
        $searchQueriesCount = SearchQueries::find()->count();
        echo "Начало работы \n";

        $limit  = $step;
        $offset = 0;
        $check = true;

        while ($check !== false) {
            $searchQueries = SearchQueries::find()->limit($limit)->offset($offset)->all();
            echo "\nПолучен поисковой запрос \n";

            if (!isset($searchQueries[0])) {
                echo "Поисковой запрос пуст \n";
                $check = false;
            } else {
                foreach ($searchQueries as $searchQuery) {
                    $searchModel = new LotSearch();
                    $data = $searchQuery->getQueryParser();

                    $searchModel->publishedDate = $searchQuery->seached_at;
                    
                    switch ($data['path'][0]) {
                        case 'bankrupt':
                            $searchModel->type = 1;
                            break;
                        case 'arrest':
                            $searchModel->type = 2;
                            break;
                        case 'zalog':
                            $searchModel->type = 3;
                            break;
                        case 'municipal':
                            $searchModel->type = 4;
                            break;
                        default:
                            $searchModel->type = 0;
                            break;
                    }
            
                    if (!empty($items = Category::find()->where(['slug' => $data['path'][1], 'depth' => 1])->one())) {
                        $searchModel->mainCategory[] = $items->id;
                    }
                    
                    \Yii::$app->params['defaultPageLimit'] = 1000;
                    $dataProvider = $searchModel->search($data['query']);

                    $lots = $dataProvider->getModels();
                    echo "Выполнен поиск новых лотов\n";

                    $last_count = count($lots);
                    echo "Найдено лотов: $last_count\n";

                    $searchQuery->last_count = $last_count;
                    $searchQuery->seached_at = strtotime((new \DateTime())->format('Y-m-d H:i:s'));

                    if ($searchQuery->update() && $last_count > 0 && $searchQuery->send_email == true) {
                        $user = User::findOne(['id' => $searchQuery->user_id]);
                        echo "Отправка сообщения\n";

                        Yii::$app->mailer_support->compose(['html' => 'search-preset-html'], [
                            'user'          => $user, 
                            'count'         => $last_count,
                            'lots'          => $lots, 
                            'searchQuery'   => $searchQuery,
                        ])
                            ->setFrom([Yii::$app->params['email']['support'] => (Yii::$app->name . ' robot')])
                            ->setTo($user->email)
                            ->setSubject('Новые лоты по вашим запросам')
                            ->send();
                    }
                }
            }

            $offset = $offset + $step;
        }
        echo "\nКонец работы \n";

    }
}  