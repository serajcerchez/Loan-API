<?php

use App\Enums\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->decimal('amount');
            $table->dateTime('scheduled_at');
            $table->tinyInteger('state')->default(State::PENDING->value);
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
