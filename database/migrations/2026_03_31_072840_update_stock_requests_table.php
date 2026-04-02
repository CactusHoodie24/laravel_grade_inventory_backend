<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function(Blueprint $table) {
            // Drop existing string columns first
            $table->dropColumn('requested_by');
            $table->dropColumn('approved_by');
        });

        Schema::table('stock_requests', function(Blueprint $table) {
            // Re-add as foreign keys
            $table->foreignId('requested_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function(Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn('requested_by');
            $table->dropColumn('approved_by');
        });

        Schema::table('stock_requests', function(Blueprint $table) {
            // Restore original string columns
            $table->string('requested_by')->nullable();
            $table->string('approved_by')->nullable();
        });
    }
};