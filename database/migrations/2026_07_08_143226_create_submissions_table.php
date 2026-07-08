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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('expense_categories')
                ->cascadeOnDelete();

            $table->string('submission_number')
                ->unique();

            $table->string('title');

            $table->text('description')
                ->nullable();

            $table->decimal('amount', 15, 2);

            $table->string('status')
                ->default('draft');

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamps();

            $table->index('status');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
