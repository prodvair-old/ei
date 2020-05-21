<?php

use yii\db\Migration;

/**
 * Class m200518_185219_add_lot_search_index
 */
class m200518_185219_add_lot_search_index extends Migration
{
    public function safeUp()
    {
        /*
         * PREPARE SEARCH CONFIGURATION
         *----------------------------
         */
//        $this->getDb()->createCommand(
//            '
//           CREATE TEXT SEARCH DICTIONARY ispell_ru (
//           template  =   ispell,
//           dictfile  =   ru_ru,
//           afffile   =   ru_ru,
//           stopwords =   russian
//           );
//           '
//        )->execute();
//        $this->getDb()->createCommand('CREATE TEXT SEARCH CONFIGURATION ru ( COPY = russian );')->execute();
        $this->getDb()->createCommand(
            'ALTER TEXT SEARCH CONFIGURATION ru
           ALTER MAPPING
           FOR word, hword, hword_part
           WITH ispell_ru, russian_stem;
           '
        )->execute();
        $this->getDb()->createCommand('SET default_text_search_config = \'ru\';')->execute();

        /** ADD tsvector column **/
//        $this->getDb()->createCommand(
//            '
//           ALTER TABLE {{%lot}} ADD COLUMN fts tsvector;
//        '
//        )->execute();
        $this->getDb()->createCommand(
            '
           UPDATE {{%lot}} SET fts=
setweight( coalesce( to_tsvector(\'ru\', [[title]]),\'\'),\'A\') || \' \' ||
setweight( coalesce( to_tsvector(\'ru\', [[description]]),\'\'),\'B\') || \' \';
        '
        )->execute();
//        $this->getDb()->createCommand('create index fts_index on {{%lot}} using gin (fts);')->execute();

        /**
         * ---   ADD AUTO FILL fts TRIGGER ON INSERT NEW RECORD
         * (in my case 'on update' trigger not neccessary)
         **/
//        $this->getDb()->createCommand(
//            '
//            CREATE FUNCTION fts_vector_update() RETURNS TRIGGER AS $$
//BEGIN
//   NEW.fts=setweight( coalesce( to_tsvector(\'ru\', NEW.title),\'\'),\'A\') || \' \' ||
//			setweight( coalesce( to_tsvector(\'ru\', NEW.full_title),\'\'),\'B\') || \' \';
//			RETURN NEW;
//END;
//$$ LANGUAGE \'plpgsql\';
//CREATE TRIGGER lot_fts_update BEFORE INSERT ON {{%lot}}
//FOR EACH ROW EXECUTE PROCEDURE fts_vector_update();
//        '
//        )->execute();
    }

    public function safeDown()
    {
//        $this->dropIndex('fts_index', '{{%lot}}');
//        $this->dropColumn('{{%lot}}', 'fts');
//        $this->getDb()->createCommand('DROP TRIGGER lot_fts_update ON {{%lot}}')->execute();
//        $this->getDb()->createCommand('DROP FUNCTION IF EXISTS fts_vector_update()')->execute();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200518_185219_add_lot_search_index cannot be reverted.\n";

        return false;
    }
    */
}
