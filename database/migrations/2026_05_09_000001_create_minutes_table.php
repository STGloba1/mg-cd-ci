<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minutes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('transcript_analysis_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('meeting_date')->nullable();
            $table->json('participants');
            $table->text('executive_summary');
            $table->json('topics');
            $table->json('detected_problems');
            $table->json('proposed_solutions');
            $table->json('agreements');
            $table->json('pending_tasks');
            $table->json('risks');
            $table->json('next_steps');
            $table->unsignedTinyInteger('confidence_score')->default(0);
            $table->longText('editable_content')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('version')->default(1);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minutes');
    }
};
