<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class AlterDonatorTableForOneGuard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {                            
        Schema::table('donators', function (Blueprint $table) {
            $table->dropColumn('donator_name');
            $table->dropColumn('donator_email');
            $table->dropColumn('donator_phone');
            $table->dropColumn('donator_address');
            $table->dropColumn('password');
            $table->biginteger('user_id')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donators', function (Blueprint $table) {
            $table->string('donator_name')->nullable()->default("doe");
            $table->string('donator_email')->default("testeron@test.com");
            $table->string('donator_phone')->nullable()->default("12345678");
            $table->string('donator_address')->nullable()->default("jl abc");
            $table->string('password')->nullable();
            $table->dropColumn('user_id');
        });
    }
}
