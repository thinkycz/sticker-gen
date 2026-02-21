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
        Schema::create('sticker_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            
            // Paper dimensions
            $table->decimal('paper_width', 8, 2);
            $table->decimal('paper_height', 8, 2);
            $table->string('paper_unit')->default('mm'); // 'mm' or 'in'
            
            // Sticker dimensions
            $table->decimal('sticker_width', 8, 2);
            $table->decimal('sticker_height', 8, 2);
            
            // Grid config
            $table->integer('rows');
            $table->integer('columns');
            $table->decimal('gap_horizontal', 8, 2)->default(0);
            $table->decimal('gap_vertical', 8, 2)->default(0);
            
            // Margins
            $table->decimal('margin_top', 8, 2)->default(0);
            $table->decimal('margin_bottom', 8, 2)->default(0);
            $table->decimal('margin_left', 8, 2)->default(0);
            $table->decimal('margin_right', 8, 2)->default(0);
            
            // Design Data (JSON)
            $table->json('design_data')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sticker_sheets');
    }
};
