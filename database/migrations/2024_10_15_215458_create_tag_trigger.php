<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::statement(
            '
            CREATE OR REPLACE FUNCTION check_tag_count() RETURNS trigger as
            $check_tag_count$
            BEGIN
                IF (SELECT count(*) FROM post_tag WHERE tag_id = OLD.tag_id LIMIT 1) = 0 THEN
                    DELETE
                    FROM tags
                    WHERE id = OLD.tag_id;
                END IF;
                RETURN NULL;
            END;
            $check_tag_count$ LANGUAGE plpgsql;
        '
        );
        DB::statement(
            '
            CREATE OR REPLACE TRIGGER check_tag_count_trigger
            AFTER DELETE
            ON post_tag
            FOR EACH ROW
            EXECUTE FUNCTION check_tag_count();
            '
        );
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS check_tag_count_trigger ON post_tag;');
        DB::statement('DROP FUNCTION check_tag_count();');
    }
};
