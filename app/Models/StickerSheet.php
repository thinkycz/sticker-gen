<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StickerSheet extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'design_data' => 'array',
        'paper_width' => 'decimal:2',
        'paper_height' => 'decimal:2',
        'sticker_width' => 'decimal:2',
        'sticker_height' => 'decimal:2',
        'gap_horizontal' => 'decimal:2',
        'gap_vertical' => 'decimal:2',
        'margin_top' => 'decimal:2',
        'margin_bottom' => 'decimal:2',
        'margin_left' => 'decimal:2',
        'margin_right' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
