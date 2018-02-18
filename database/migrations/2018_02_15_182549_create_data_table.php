<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // テーブル定義
        Schema::create('leaf_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sprig_id');        // 所属する 小枝
            $table->unsignedInteger('leaf_type_id');    // 葉 の種類
            $table->string('revision')->nullable();     // 葉 のバージョン情報 (基本は数字、任意で文字列)
            $table->text('content')->nullable();        // 中身

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sprigs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();                         // 小枝 の名前 (タイトルだからなくでもok)
            $table->unsignedInteger('branch_id')->nullable();           // 所属する 幹
            $table->unsignedInteger('parent_sprig_id')->nullable();     // 親の 小枝
            $table->unsignedInteger('origin_sprig_id')->nullable();     // 関連する親の 小枝 (ramify, graft 時)
            $table->unsignedInteger('current_leaf_id')->nullable();     // 現在参照している 葉

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');                                 // 幹 の名前
            $table->unsignedInteger('head_sprig_id')->nullable();   // 幹 の最新の 枝
            $table->unsignedInteger('tail_sprig_id')->nullable();   // 幹 の先頭の 枝

            $table->timestamps();
            $table->softDeletes();
        });



        // リレーションの定義
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('leaf_type_id')->references('id')->on('leaf_types');
        });

        Schema::table('sprigs', function (Blueprint $table) {
            $table->foreign('parent_sprig_id')->references('id')->on('sprigs');
            $table->foreign('origin_sprig_id')->references('id')->on('sprigs');
            $table->foreign('current_leaf_id')->references('id')->on('leaves');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('head_sprig_id')->references('id')->on('sprigs');
            $table->foreign('tail_sprig_id')->references('id')->on('sprigs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['leaf_type_id']);
        });
        Schema::table('sprigs', function (Blueprint $table) {
            $table->dropForeign(['parent_sprig_id', 'origin_sprig_id', 'current_leaf_id']);
        });
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['head_sprig_id', 'tail_sprig_id']);
        });

        Schema::dropIfExists('branches');
        Schema::dropIfExists('sprigs');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('leaf_types');
    }
}
