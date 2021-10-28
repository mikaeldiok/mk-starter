<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donators', function (Blueprint $table) {
            $table->id();
            $table->string('donator_name')->nullable();
            $table->string('donator_email')->unique();
            $table->string('donator_phone')->nullable();
            $table->text('donator_address')->nullable();
            $table->string('password')->nullable();
            $table->string('donator_bank_code')->nullable();
            $table->string('donator_bank_name')->nullable();
            $table->string('donator_bank_account')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donators');
    }
}
