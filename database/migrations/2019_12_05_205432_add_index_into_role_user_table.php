<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexIntoRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('role_user')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->index(['user_id', 'role_id', 'user_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('role_user')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'role_id', 'user_type']);
            });
        }
    }
}
