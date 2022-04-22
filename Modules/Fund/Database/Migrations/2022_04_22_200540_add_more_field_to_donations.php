<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldToDonations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('notes')->nullable();
            $table->string('donator_bank_name')->nullable();
            $table->string('donator_bank_account')->nullable();
            $table->string('donator_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('notes');
            $table->dropColumn('donator_bank_name');
            $table->dropColumn('donator_bank_account');
            $table->dropColumn('donator_name');
        });
    }
}
