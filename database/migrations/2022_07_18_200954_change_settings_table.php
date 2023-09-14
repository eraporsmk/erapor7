<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class ChangeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropPrimary('key_primary');
            $table->uuid('sekolah_id')->nullable();
            $table->string('semester_id', 5)->nullable();
            $table->foreign('sekolah_id')->references('sekolah_id')->on('sekolah')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('semester_id')->references('semester_id')->on('ref.semester')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('settings');
        Setting::whereNotNull('key')->delete();
        Schema::table('settings', function (Blueprint $table) {
            $table->dropPrimary('id_primary');
            $table->dropColumn(['id', 'sekolah_id', 'semester_id']);
            $table->primary('key');
        });
    }
}
