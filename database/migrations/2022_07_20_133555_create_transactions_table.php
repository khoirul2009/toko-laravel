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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->string('kode_transaksi')->unique();
            $table->string('pembayaran');
            $table->double('total_bayar');
            $table->integer('status');
            $table->string('pengiriman');
            $table->double('ongkir');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('resi')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
