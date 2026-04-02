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
    Schema::table('items', function (Blueprint $table) {
        $table->unsignedBigInteger('supplier_id')->nullable()->after('id'); // ← adds the column
        $table->foreign('supplier_id')                                      // ← adds the constraint
              ->references('id')
              ->on('suppliers')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('items', function (Blueprint $table) {
        $table->dropForeign(['supplier_id']);
        $table->dropColumn('supplier_id');
    });
}
};
