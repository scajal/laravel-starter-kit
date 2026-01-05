<?php

declare(strict_types=1);

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
        Schema::create('jobs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
            $table->longText('payload');
            $table->string('queue')->index();
            $table->unsignedInteger('reserved_at')->nullable();
        });

        Schema::create('job_batches', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('failed_jobs');
            $table->integer('finished_at')->nullable();
            $table->longText('failed_job_ids');
            $table->string('name');
            $table->mediumText('options')->nullable();
            $table->integer('pending_jobs');
            $table->integer('total_jobs');
        });

        Schema::create('failed_jobs', function (Blueprint $table): void {
            $table->id();
            $table->text('connection');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
            $table->longText('payload');
            $table->text('queue');
            $table->string('uuid')->unique();
        });
    }
};
