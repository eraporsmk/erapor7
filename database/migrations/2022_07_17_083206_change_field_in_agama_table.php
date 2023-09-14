<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldInAgamaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      try {
        if(!Schema::hasColumn('ref.agama', 'agama_id')){
          Schema::table('ref.agama', function (Blueprint $table) {
            $table->renameColumn('id', 'agama_id');
          });
		    }
      } catch (Exception $e) {
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      try {
        if(!Schema::hasColumn('ref.agama', 'id')){
          Schema::table('ref.agama', function (Blueprint $table) {
            $table->renameColumn('agama_id', 'id');
          });
        }
      } catch (Exception $e) {
      }
    }
}
