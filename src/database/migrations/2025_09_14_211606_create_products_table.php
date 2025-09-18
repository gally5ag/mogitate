<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // 商品名
            $table->unsignedInteger('price');    // 価格
            $table->string('image_path')->nullable(); // 画像保存パス
            $table->string('season')->nullable();     // 文字列に変更
            $table->text('description')->nullable();   // 商品説明
            $table->timestamps();

            $table->index(['price', 'season']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
