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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->references('id')->on('client');
            $table->foreignId('users_id')->references('id')->on('users');
            $table->string("fullname");
            $table->decimal("rate",10,2);
            $table->decimal("amount",10,2);
            $table->string("tenurity");
            $table->decimal("interest",10,2);
            $table->decimal("loan_outstanding",10,2);
            $table->decimal("monthly",10,2);
            $table->string("reference");
            $table->integer("status")->defualt(0);
            $table->string("remarks")->nullable();
            $table->decimal("payment_amount",10,2)->nullable();
            $table->decimal("balance_amount",10,2)->nullable();
            $table->decimal("penalty_interest",10,2)->nullable();
            $table->string("payment_for")->nullable();
            $table->string("payment_method")->nullable();
            $table->timestamp('disbursement_date')->nullable();
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamp('upcoming_due_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
};
