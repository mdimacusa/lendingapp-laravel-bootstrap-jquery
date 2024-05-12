<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable();
            $table->string('users_id');
            $table->string("name");
            $table->string("reference");
            $table->string("category");
            $table->enum('type',['DEBIT','CREDIT'])->default('CREDIT');
            $table->decimal("amount",10,2);
            $table->string("message");
            $table->enum('_seen',['No','Yes'])->default('No');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
