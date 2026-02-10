<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('seats', 255)->change(); 
            // varchar(255), tu peux ajuster la taille selon ton besoin
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->longText('seats')->change(); 
            // rollback vers longtext
        });
    }
};
