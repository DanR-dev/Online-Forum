<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_role', function (Blueprint $table) {
            $table->unique(["profile_id", "role_id"], 'profile_role_id');
            $table->foreignId('profile_id')->contrained()->onDelete('cascade')->onUpdate('cascade'); //profile
            $table->foreignId('role_id')->contrained()->onDelete('cascade')->onUpdate('cascade'); //role of profile
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_role');
    }
}
