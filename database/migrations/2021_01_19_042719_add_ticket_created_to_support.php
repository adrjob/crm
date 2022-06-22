<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketCreatedToSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'supports', function (Blueprint $table){
            $table->string('ticket_created', 10)->default(0)->after('ticket_code');
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
            'supports', function (Blueprint $table){
            $table->string('ticket_created', 10)->default(0)->after('ticket_code');
        }
        );
    }
}
