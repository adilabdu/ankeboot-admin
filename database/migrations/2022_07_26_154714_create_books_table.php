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
            $table->string('subtitle')->nullable();
            $table->string('alternate_title')->nullable();
            $table->string('author')->nullable();
            $table->string('language')->default('English');
            $table->string('script')->default('English');
            $table->string('publisher')->nullable();
            $table->string('print_house')->nullable();
            $table->string('publishing_year')->nullable();
            $table->string('edition')->nullable();
            $table->string('category')->nullable();
            $table->integer('balance');
            $table->string('front_cover')->nullable();
            $table->string('back_cover')->nullable();
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at')->nullable();

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
