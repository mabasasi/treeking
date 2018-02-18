<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 最後に参照したブランチ
            $table->unsignedInteger('current_branch_id')->nullable()->after('password');

            // SNS アカウント連携(いずれ)
            $table->string('social')->nullable()->after('password');


            $table->foreign('current_branch_id')->references('id')->on('branches');
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
            $table->dropForeign(['current_branch_id']);

            $table->dropColumn('current_branch_id');
            $table->dropColumn('social');
        });
    }
}
