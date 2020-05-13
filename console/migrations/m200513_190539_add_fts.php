<?php

use yii\db\Migration;

/**
 * Class m200513_190539_add_fts
 */
class m200513_190539_add_fts extends Migration
{
    public function safeUp()
    {
        /*
         * PREPARE SEARCH CONFIGURATION
         *----------------------------
         */
        $this->getDb()->createCommand(
            '
           CREATE TEXT SEARCH DICTIONARY ispell_ru (
           template  =   ispell,
           dictfile  =   ru,
           afffile   =   ru,
           stopwords =   russian
           );
           '
        )->execute();
        $this->getDb()->createCommand('CREATE TEXT SEARCH CONFIGURATION ru ( COPY = russian );')->execute();
        $this->getDb()->createCommand(
            'ALTER TEXT SEARCH CONFIGURATION ru
           ALTER MAPPING
           FOR word, hword, hword_part
           WITH ispell_ru, russian_stem;
           '
        )->execute();
        $this->getDb()->createCommand('SET default_text_search_config = \'ru\';')->execute();

        /** ADD tsvector column **/
        $this->getDb()->createCommand(
            '
           ALTER TABLE {{%organization}} ADD COLUMN fts tsvector;
        '
        )->execute();
        $this->getDb()->createCommand(
            '
           UPDATE {{%organization}} SET fts=
setweight( coalesce( to_tsvector(\'ru\', [[title]]),\'\'),\'A\') || \' \' ||
setweight( coalesce( to_tsvector(\'ru\', [[full_title]]),\'\'),\'B\') || \' \';
        '
        )->execute();
        $this->getDb()->createCommand('create index fts_index on {{%organization}} using gin (fts);')->execute();

        /**
         * ---   ADD AUTO FILL fts TRIGGER ON INSERT NEW RECORD
         * (in my case 'on update' trigger not neccessary)
         **/
        $this->getDb()->createCommand(
            '
            CREATE FUNCTION fts_vector_update() RETURNS TRIGGER AS $$
BEGIN
   NEW.fts=setweight( coalesce( to_tsvector(\'ru\', NEW.title),\'\'),\'A\') || \' \' ||
			setweight( coalesce( to_tsvector(\'ru\', NEW.full_title),\'\'),\'B\') || \' \';
			RETURN NEW;
END;
$$ LANGUAGE \'plpgsql\';
CREATE TRIGGER organization_fts_update BEFORE INSERT ON {{%organization}}
FOR EACH ROW EXECUTE PROCEDURE fts_vector_update();
        '
        )->execute();
    }

    public function safeDown()
    {
        $this->dropIndex('fts_index', '{{%organization}}');
        $this->dropColumn('{{%organization}}', 'fts');
        $this->getDb()->createCommand('DROP TRIGGER organization_fts_update ON {{%organization}}')->execute();
        $this->getDb()->createCommand('DROP FUNCTION IF EXISTS fts_vector_update()')->execute();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200513_190539_add_fts cannot be reverted.\n";

        return false;
    }
    */
}
