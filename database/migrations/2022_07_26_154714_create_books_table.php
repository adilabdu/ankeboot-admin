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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['cash', 'consignment']);
            $table->string('title');
            $table->string('alternate_title')->nullable();
            $table->string('author')->nullable();
            $table->string('category')->nullable();
            $table->integer('balance');
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->fullText(['title', 'alternate_title', 'author', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
