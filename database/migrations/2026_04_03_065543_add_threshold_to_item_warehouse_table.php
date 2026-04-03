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
    Schema::table('item_warehouse', function (Blueprint $table) {
        $table->integer('threshold')->default(10)->after('quantity');
    });
}

public function down(): void
{
    Schema::table('item_warehouse', function (Blueprint $table) {
        $table->dropColumn('threshold');
    });
}
};
