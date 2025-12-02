<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('phone_number', 'phone')->index();
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('country_code_id')->nullable()->after('email');
            $table->integer('country_code')->nullable()->after('country_code_id');
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->dropColumn('country_code');
            $table->dropColumn('country_code_id');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('phone', 'phone_number')->index();
        });
    }
};
