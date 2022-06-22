<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeExtraDetailInEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'employees', function (Blueprint $table){
            $table->string('emergency_contact')->nullable()->after('salary');
            $table->string('account_holder_name')->nullable()->after('salary');
            $table->string('account_number')->nullable()->after('salary');
            $table->string('bank_name')->nullable()->after('salary');
            $table->string('bank_identifier_code')->nullable()->after('salary');
            $table->string('branch_location')->nullable()->after('salary');
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'employees', function (Blueprint $table){
            $table->dropColumn('emergency_contact');
            $table->dropColumn('account_holder_name');
            $table->dropColumn('account_number');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_identifier_code');
            $table->dropColumn('branch_location');
        }
        );
    }
}
