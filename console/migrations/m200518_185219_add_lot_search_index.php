<?php

use yii\db\Migration;

/**
 * Class m200518_185219_add_lot_search_index
 */
class m200518_185219_add_lot_search_index extends Migration
{
    public function safeUp()
    {
        $this->getDb()->createCommand(
            '
        DO
            $$BEGIN
        CREATE TEXT SEARCH DICTIONARY ispell_ru (
            template = ispell,
            dictfile = ru_ru,
            afffile = ru_ru,
            stopwords = russian
        );
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
           '
        )->execute();
        $this->getDb()->createCommand(
            '
        DO
        $$BEGIN
            CREATE TEXT SEARCH CONFIGURATION ru ( COPY = russian );
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
        '
        )->execute();
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
           ALTER TABLE {{%lot}} ADD COLUMN fts tsvector;
        '
        )->execute();
        $this->getDb()->createCommand(
            '
           UPDATE {{%lot}} SET fts=
setweight( coalesce( to_tsvector(\'ru\', [[title]]),\'\'),\'A\') || \' \' ||
setweight( coalesce( to_tsvector(\'ru\', [[description]]),\'\'),\'B\') || \' \';
        '
        )->execute();
        $this->getDb()->createCommand('create index fts_index on {{%lot}} using gin (fts);')->execute();

        /**
         * ---   ADD AUTO FILL fts TRIGGER ON INSERT AND UPDATE NEW RECORD
         **/
        $this->getDb()->createCommand(
            '
        CREATE FUNCTION fts_vector_update() RETURNS TRIGGER AS
        $$
        BEGIN
            NEW.fts = setweight(coalesce(to_tsvector(\'ru\', NEW.title), \'\'), \'A\') || \' \' ||
                      setweight(coalesce(to_tsvector(\'ru\', NEW.description), \'\'), \'B\') || \' \';
            RETURN NEW;
        END;
        $$ LANGUAGE \'plpgsql\';
        '
        )->execute();

        $this->getDb()->createCommand(
            '
        DO
        $$BEGIN
            CREATE TRIGGER lot_fts_insert
                BEFORE INSERT
                ON eidb.lot
                FOR EACH ROW
            EXECUTE PROCEDURE fts_vector_update();
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
        '
        )->execute();

        $this->getDb()->createCommand(
            '
            DO
        $$BEGIN
            CREATE TRIGGER lot_fts_update
                BEFORE UPDATE
                ON eidb.lot
                FOR EACH ROW
            EXECUTE PROCEDURE fts_vector_update();
        EXCEPTION
           WHEN unique_violation THEN
              NULL;
        END;$$;
        '
        )->execute();
    }

    public function safeDown()
    {
        $this->dropIndex('fts_index', 'eidb.lot');
        $this->dropColumn('{{%lot}}', 'fts');
        $this->getDb()->createCommand('DROP FUNCTION IF EXISTS fts_vector_update() CASCADE')->execute();
    }

}
