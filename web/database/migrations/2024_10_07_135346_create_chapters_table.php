<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained();
            $table->text('title');
            $table->text('text');
            $table->timestamps();
        });
        DB::statement("
            create or replace function update_char_count() returns trigger as $$
            begin
                if tg_op = 'INSERT' then
                    update books set char_count = char_count + sub.len from (select char_length(NEW.text) as len) as sub where id = NEW.book_id;
                    return new;
                elsif tg_op = 'UPDATE' then
                    update books set char_count = char_count - sub.len from (select char_length(OLD.text) as len) as sub where id = OLD.book_id;
                    update books set char_count = char_count + sub.len from (select char_length(NEW.text) as len) as sub where id = NEW.book_id;
                    return new;
                elsif tg_op = 'DELETE' then
                    update books set char_count = char_count - sub.len from (select char_length(OLD.text) as len) as sub where id = OLD.book_id;
                    return new;
                end if;
            end
            $$ language plpgsql;
        ");
        DB::statement("create trigger update_char_count after insert or update or delete on chapters for each row execute procedure update_char_count();");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
        DB::statement("drop function update_char_count;");
    }
};
