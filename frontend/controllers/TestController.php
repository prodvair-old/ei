<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\Query\LotsSubCategory;
use common\models\Query\LotsCategory;

use arogachev\excel\import\advanced\Importer;

use common\models\Query\Lot\Lots;
use common\models\Query\Municipal\Torgs;

use common\models\Query\Regions;

use common\models\db\Category;
use moonland\phpexcel\Excel;

/**
 * Test controller
 */
class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $categorys = [];
    $parent = null;
    foreach (Category::items() as $key => $value) {
      if ($key < 20) {
        $parent = ['id' => $key, 'value' => $value];
      } else if ($key < 20000){
        $categorys[] = ['parentId' => $parent['id'], 'parentValue' => $parent['value'], 'categoryId' => $key, 'category' => $value];
      }
    }
    Excel::export([
      'models' => $categorys,
      'columns' => [
          'parentId:text',
          'parentValue:text',
          'categoryId:text',
          'category:text',
      ],
      'headers' => [
          'parentId' => 'Id категория',
          'parentValue' => 'Категория',
          'categoryId' => 'Id подкатегория',
          'category' => 'Подкатегория',
      ],
    ]);

        // return $lots;
    }
    public function actionConvert()
    {
        function translit($s) {
            $s = (string) $s; // преобразуем в строковое значение
            $s = strip_tags($s); // убираем HTML-теги
            $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
            $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
            $s = trim($s); // убираем пробелы в начале и конце строки
            $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
            $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
            $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
            $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
            return $s; // возвращаем результат
        }

        $arr = [
            11202 => 'Материальные активы',
            11203 => 'Оружие спортивное, охотничье и военная техника двойного применения',
            11204 => 'Аппараты летательные космические',
            11205 => 'Велосипеды и коляски инвалидные',
            11206 => 'Животноводство. Растениеводство',
            11207 => 'Продукция птицеводства',
            11208 => 'Продукция пчелеводства, шмелеводства, шелководства',
            11209 => 'Продукция рыбоводства',
            11210 => 'Природные активы',
            11211 => 'Земельные участки',
            11212 => 'Сырье. Материалы для производства. Продукция промышленных производств',
            11213 => 'Прокат цветных металлов',
            11214 => 'Взрывные устройства и взрывчатые вещества народнохозяйственного назначения',
            11215 => 'Товары народного потребления',
            11216 => 'Растения, цветы, сопутствующие товары',
            11217 => 'Нематериальные активы',
            11218 => 'КОМПЬЮТЕРНОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ',
            11219 => 'Базы данных',
            11220 => 'НАУКОЕМКИЕ ПРОМЫШЛЕННЫЕ ТЕХНОЛОГИИ',
            11221 => 'Наукоемкие промышленные технологии в области электронной техники',
            11222 => 'Наукоемкие промышленные технологии в области спецхимии',
            11223 => 'Биотехнологии',
            11224 => 'Наукоемкие промышленные технологии в области ракетно-космической техники',
            11225 => 'Наукоемкие промышленные технологии в области атомной техники',
            11226 => 'Наукоемкие промышленные технологии в области судостроения',
            11227 => 'Наукоемкие промышленные технологии в области радиотехники и средств связи',
            11228 => 'Наукоемкие промышленные технологии в прочих областях',
            11229 => 'Наукоемкие промышленные технологии в области авиастроения и авиационной техники',
            11230 => 'НЕМАТЕРИАЛЬНЫЕ ОСНОВНЫЕ ФОНДЫ ПРОЧИЕ',
            11231 => 'Топологии интегральных микросхем',
            11232 => 'Финансовые активы',
            11233 => 'Имущественные права',
            11234 => 'Вещные права на имущество',
        ];

        $categorys = [
            1   => [
                'ids'   => [11204, 11205],
                'name'  => false,
            ],
            2   => [
                'ids'   => [11211],
                'name'  => false,
            ],
            3   => [
                'ids'   => [11203],
                'name'  => false,
            ],
            4   => [
                'ids'   => [11207, 11208, 11209, 11216],
                'name'  => false,
            ],
            9   => [
                'ids'   => [11210, 11213, 11214],
                'name'  => false,
            ],
            11  => [
                'ids'   => [11202, 11217, 11232],
                'name'  => 'Активы',
            ],
            12  => [
                'ids'   => [11206],
                'name'  => 'Животноводство',
            ],
            13  => [
                'ids'   => [11212],
                'name'  => 'Материалы для производства',
            ],
            14  => [
                'ids'   => [11215],
                'name'  => 'Товары народного потребления',
            ],
            15  => [
                'ids'   => [11218, 11219],
                'name'  => 'Компьютерное ПО',
            ],
            16  => [
                'ids'   => [11220, 11221, 11222, 11223, 11224, 11225, 11226, 11227, 11228, 11229],
                'name'  => 'Наукоемкие промышленные технологии',
            ],
            17  => [
                'ids'   => [11230, 11231],
                'name'  => 'Нематериальные основные фонды',
            ],
            18  => [
                'ids'   => [11233, 11234],
                'name'  => 'Имущественные права',
            ],
        ];

        // создание корневой категории
        // $category = new Category(['id' => 20, 'name' => 'Новые', 'slug' => 'new']);
        // $category->makeRoot();
        $category = Category::findOne(0);

        foreach ($categorys as $id => $data) {
            if ($data['name']) {
                $node = new Category([
                    'id'   => $id,
                    'name' => $data['name'],
                    'slug' => translit($data['name']),
                ]);
                $node->appendTo($category);
            } else {
                $node = Category::findOne($id);
            }

            
            foreach ($data['ids'] as $i) {
                $leaf = new Category([
                    'id'   => $i+100,
                    'name' => $arr[$i],
                    'slug' => translit($arr[$i]),
                ]);
                $leaf->appendTo($node);
            }
        }
    }
    public function actionIndexssa()
    {
        $json = [
            "0101" => [
                "name" => "Легковые автомобили",
                "translit" => "legkovye-avtomobili",
                "bankruptIds" => [
                    1060, 1176
                ],
                "arrestIds" => [
                    14, 65
                ]
            ],
            "0102" => [
                "name" => "Водный транспорт",
                "translit" => "vodnyy-transport",
                "bankruptIds" => [
                    1120, 1127
                ],
                "arrestIds" => [
                    34
                ]
            ],
            "0103" => [
                "name" => "Спецтехника",
                "translit" => "spectehnika",
                "bankruptIds" => [
                    1062, 1118
                ],
                "arrestIds" => [
                    54
                ]
            ],
            "0104" => [
                "name" => "Автобусы и микроавтобусы",
                "translit" => "avtobusy-i-mikroavtobusy",
                "bankruptIds" => [
                    1092
                ],
                "arrestIds" => [
                    45
                ]
            ],
            "0105" => [
                "name" => "Мототранспортные средства",
                "translit" => "mototransportnye-sredstva",
                "bankruptIds" => [
                    1145
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0106" => [
                "name" => "Аппараты летательные воздушные",
                "translit" => "apparaty-letatelnye-vozdushnye",
                "bankruptIds" => [
                    1077
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0108" => [
                "name" => "Грузовые автомобили",
                "translit" => "gruzovye-avtomobili",
                "bankruptIds" => [
                    
                ],
                "arrestIds" => [
                    51
                ]
            ],
            "0109" => [
                "name" => "Средства транспортные железнодорожные",
                "translit" => "sredstva-transportnye-zheleznodorozhnye",
                "bankruptIds" => [
                    1124
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0110" => [
                "name" => "Прочие транспортные средства",
                "translit" => "prochie-transportnye-sredstva",
                "bankruptIds" => [
                    1073
                ],
                "arrestIds" => [
                    42, 92
                ]
            ],
            "0111" => [
                "name" => "Прицепы и полуприцепы, фургоны",
                "translit" => "pricepy-i-polupricepy-furgony",
                "bankruptIds" => [
                    1075
                ],
                "arrestIds" => [
                    
                ]
            ],
            // Недвижимость
            "1001" => [
                "name" => "Недвижимость (жилая)",
                "translit" => "nedvizhimost-zhilaya",
                "bankruptIds" => [
                    1061, 1088
                ],
                "arrestIds" => [
                    28, 29
                ]
            ],
            "1002" => [
                "name" => "Недвижимость (коммерческая)",
                "translit" => "nedvizhimost-kommercheskaya",
                "bankruptIds" => [
                    1078, 1148, 1157, 1143, 
                ],
                "arrestIds" => [
                    2, 3, 19, 26, 37, 38
                ]
            ],
            "0203" => [
                "name" => "Имущественный комплекс",
                "translit" => "imushchestvennyy-kompleks",
                "bankruptIds" => [
                    
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0204" => [
                "name" => "Земельные участки",
                "translit" => "zemelnye-uchastki",
                "bankruptIds" => [
                    1068
                ],
                "arrestIds" => [
                    25, 1, 23
                ]
            ],
            "0205" => [
                "name" => "Незавершенное строительство",
                "translit" => "nezavershennoe-stroitelstvo",
                "bankruptIds" => [
                    1090, 1102
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0206" => [
                "name" => "Недвижимость (прочее)",
                "translit" => "nedvizhimost-prochee",
                "bankruptIds" => [
                    1064, 1161
                ],
                "arrestIds" => [
                    31, 30, 17
                ]
            ],
            "0207" => [
                "name" => "Сооружения спортивно-оздоровительные",
                "translit" => "sooruzheniya-sportivno-ozdorovitelnye",
                "bankruptIds" => [
                    1140
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0208" => [
                "name" => "Здания предприятий здравоохранения, науки и научного обслуживания, образования, культуры и искусства",
                "translit" => "zdaniya-predpriyatiy-zdravoohraneniya-nauki-i-nauchnogo-obsluzhivaniya-obrazovaniya-kultury-i-iskusstva",
                "bankruptIds" => [
                    1136
                ],
                "arrestIds" => [
                    
                ]
            ],
            "1009" => [
                "name" => "Здания для органов государственного управления, обороны, государственной безопасности, финансов и иностранных представительств",
                "translit" => "zdaniya-dlya-organov-gosudarstvennogo-upravleniya-oborony-gosudarstvennoy-bezopasnosti-finansov-i-inostrannyh-predstavitelstv",
                "bankruptIds" => [
                    1173
                ],
                "arrestIds" => [
                    
                ]
            ],
        
            // Оборудование
            "0301" => [
                "name" => "Компьютеры, офисная техника, ПО",
                "translit" => "kompyutery-ofisnaya-tehnika-po",
                "bankruptIds" => [
                    1066
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0302" => [
                "name" => "Имущественный комплекс",
                "translit" => "imushchestvennyy-kompleks",
                "bankruptIds" => [
                    
                ],
                "arrestIds" => [
                    63, 103
                ]
            ],
            "0303" => [
                "name" => "Бытовая, телевизионная, аудио-видео техника",
                "translit" => "bytovaya-televizionnaya-audio-video-tehnika",
                "bankruptIds" => [
                    1192, 1093, 1071
                ],
                "arrestIds" => [
                    49
                ]
            ],
            "0304" => [
                "name" => "Вентиляционное и климатическое оборудование",
                "translit" => "ventilyacionnoe-i-klimaticheskoe-oborudovanie",
                "bankruptIds" => [
                    1094
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0305" => [
                "name" => "Машины и оборудование прочие",
                "translit" => "mashiny-i-oborudovanie-prochie",
                "bankruptIds" => [
                    1065
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0306" => [
                "name" => "Электродвигатели, генераторы и трансформаторы силовые",
                "translit" => "elektrodvigateli-generatory-i-transformatory-silovye",
                "bankruptIds" => [
                    1083
                ],
                "arrestIds" => [
                    115
                ]
            ],
            "0307" => [
                "name" => "Пожарно-охранное оборудование, комплектующие и инструмент",
                "translit" => "pozharno-ohrannoe-oborudovanie-komplektuyushchie-i-instrument",
                "bankruptIds" => [
                    1095
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0308" => [
                "name" => "Производственное оборудование",
                "translit" => "proizvodstvennoe-oborudovanie",
                "bankruptIds" => [
                    1105
                ],
                "arrestIds" => [
                    88, 40, 20
                ]
            ],
            "0309" => [
                "name" => "Оборудование, комплектующие и инструменты",
                "translit" => "oborudovanie-komplektuyushchie-i-instrumenty",
                "bankruptIds" => [
                    1096, 1100, 1101, 1106, 1111, 1113, 1114, 1115, 1116, 1117, 1123, 1126, 1130, 1135, 1141, 1147, 1201, 
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0310" => [
                "name" => "Товарно-материальные ценности",
                "translit" => "tovarno-materialnye-cennosti",
                "bankruptIds" => [
                    
                ],
                "arrestIds" => [
                    
                ]
            ],
        
            // Сельское хозяйство
            "0401" => [
                "name" => "Недвижимость сельско-хозяйственного назначения",
                "translit" => "nedvizhimost-selsko-hozyaystvennogo-naznacheniya",
                "bankruptIds" => [
                    1079
                ],
                "arrestIds" => [
                    18, 43
                ]
            ],
            "0402" => [
                "name" => "С/Х Техника и оборудование",
                "translit" => "sh-tehnika-i-oborudovanie",
                "bankruptIds" => [
                    
                ],
                "arrestIds" => [
                    96, 109, 15
                ]
            ],
            "0403" => [
                "name" => "Сельское хозяйство",
                "translit" => "selskoe-hozyaystvo",
                "bankruptIds" => [
                    1110, 1137, 1172, 1179, 1185, 1188, 1189, 1191, 1200
                ],
                "arrestIds" => [
                    53
                ]
            ],
            // Имущественный комплекс
            "0501" => [
                "name" => "Имущественный комплекс",
                "translit" => "imushchestvennyy-kompleks",
                "bankruptIds" => [
                    1099, 1119, 1132, 1138, 1151, 1190
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0502" => [
                "name" => "Прочее(не распределено)",
                "translit" => "",
                "bankruptIds" => [
                    1067, 1069, 1070, 1076, 1089, 1098, 1112, 1131, 1134, 1149
                ],
                "arrestIds" => [
                    
                ]
            ],
            "0503" => [
                "name" => "Мебель",
                "translit" => "mebel",
                "bankruptIds" => [
                    1108, 1129
                ],
                "arrestIds" => [
                    
                ]
            ],
            // Товарно-материальные ценности
            "0601" => [
                "name" => "Товары",
                "translit" => "tovary",
                "bankruptIds" => [
                    1080, 1081, 1082, 1084, 1152, 1154, 1159, 1160, 1168, 1175, 1177, 1180, 1181, 1183
                ],
                "arrestIds" => [
                    41, 21
                ]
            ],
            // Дебиторская задолженность
            "0701" => [
                "name" => "Дебиторская задолженность",
                "translit" => "debitorskaya-zadolzhennost",
                "bankruptIds" => [
                    1121, 1142, 1169, 1170, 1199
                ],
                "arrestIds" => [
                    16, 39
                ]
            ],
            // Ценные бумаги НМА
            "0801" => [
                "name" => "Ценные бумаги НМА",
                "translit" => "cennye-bumagi-nma",
                "bankruptIds" => [
                    1072, 1074, 1091, 1144, 1166, 1171
                ],
                "arrestIds" => [
                    
                ]
            ],
            // Сырье
            "0901" => [
                "name" => "Сырье",
                "translit" => "syre",
                "bankruptIds" => [
                    1085, 1086, 1087, 1103, 1104, 1128, 1133, 1139, 1153, 1158, 1165, 1174, 1186, 1187
                ],
                "arrestIds" => [
                    
                ]
            ],
            // Прочее
            "1001" => [
                "name" => "Прочее",
                "translit" => "prochee",
                "bankruptIds" => [
                    1063, 1097, 1107, 1109, 1122, 1125, 1150, 1155, 1156, 1162, 1163, 1164, 1167, 1178, 1182, 1184, 1193
                ],
                "arrestIds" => [
                    4, 7, 70, 90 
                ]
            ],
        ];


        foreach (LotsSubCategory::find()->all() as $subCategory) {
            foreach ($subCategory->categorys->bankrupt_categorys as $id => $category) {
                if ($subCategory->bankruptCategorys != null) {
                    foreach ($subCategory->bankruptCategorys as $subId) {
                        if ($subId == $id) {
                            $result[$subCategory->categorys->name][$subCategory->name]['Банкротка'][] = [
                                'Номер' => $id,
                                'Название' => $category['name']
                            ];
                        }
                    }
                }
            }

            foreach ($subCategory->categorys->arrest_categorys as $id => $category) {
                if ($subCategory->arrestCategorys != null) {
                    foreach ($subCategory->arrestCategorys as $subId) {
                        if ($subId == $id) {
                            $result[$subCategory->categorys->name][$subCategory->name]['Арестовка'][] = [
                                'Номер' => $id,
                                'Название' => $category['name']
                            ];
                        }
                    }
                }
            }
        }

        // echo '<pre>';
        // var_dump($result);
        // echo '</pre>';
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $result;
    }

    public function actionStr()
    {
        foreach (LotsSubCategory::find()->all() as $category) {
            if ($subcategory = LotsSubCategory::find()->where(['name' => $category->name])->andWhere(['!=', 'id', $category->id])->one()) {
                $subcategory->delete();
            }
        }
    }

}
