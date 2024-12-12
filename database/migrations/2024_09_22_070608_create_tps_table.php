<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->string('namaTps');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7); 
            $table->text('alamat');
            $table->enum('status', ['tersedia', 'penuh' , 'pemeliharaan'])->default('tersedia'); 
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
        Schema::dropIfExists('tps');
    }
}
