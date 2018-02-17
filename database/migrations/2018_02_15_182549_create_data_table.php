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
        Schema::create('fruit_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fruits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('leaf_id')->nullable(); // 所属する leaf

            $table->unsignedInteger('fruit_type_id');       // fruit の種別
            $table->string('revision')->nullable();         // fruit のバージョン情報(基本は数字、任意で文字列)
            $table->string('title')->nullable();            // fruit の内容
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tree_id')->nullable();             // 所属する tree
            $table->unsignedInteger('parent_leaf_id')->nullable();      // 親の leaf
            $table->unsignedInteger('origin_leaf_id')->nullable();      // マージ元の leaf

            $table->unsignedInteger('current_fruit_id')->nullable();    // 現在の参照先の fruit

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('trees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');  // tree の名前

            $table->unsignedInteger('head_leaf_id')->nullable();    // tree 内の最新の leaf
            $table->unsignedInteger('tail_leaf_id')->nullable();    // tree 内の一番下の leaf

            $table->timestamps();
            $table->softDeletes();
        });



        // リレーションの定義
        Schema::table('fruits', function (Blueprint $table) {
            $table->foreign('fruit_type_id')->references('id')->on('fruit_types');
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('parent_leaf_id')->references('id')->on('leaves');
            $table->foreign('origin_leaf_id')->references('id')->on('leaves');
            $table->foreign('current_fruit_id')->references('id')->on('fruits');
        });

        Schema::table('trees', function (Blueprint $table) {
            $table->foreign('head_leaf_id')->references('id')->on('leaves');
            $table->foreign('tail_leaf_id')->references('id')->on('leaves');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fruits', function (Blueprint $table) {
            $table->dropForeign(['fruit_type_id']);
        });
        Schema::table('leafs', function (Blueprint $table) {
            $table->dropForeign(['parent_leaf_id', 'origin_leaf_id', 'current_fruit_id']);
        });
        Schema::table('trees', function (Blueprint $table) {
            $table->dropForeign(['head_leaf_id', 'tail_leaf_id']);
        });

        Schema::dropIfExists('trees');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('fruit_types');
        Schema::dropIfExists('fruits');
    }
}
