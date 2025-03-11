<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('collection_items', function (Blueprint $table) {
            $table->string('translate_title')->nullable();
            $table->text('translate_description')->nullable();
        });
    }

//    public function down(): void
//    {
//        Schema::table('collection_items', function (Blueprint $table) {
//            $table->dropColumn('new_column'); // Откат изменений
//            $table->text('translate_description')->nullable(); // Вернуть удаленную колонку
//        });
//    }
};
