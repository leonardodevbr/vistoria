<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionItemPhoto extends Model
{
    protected $fillable = ['inspection_item_id', 'path'];

    public function inspectionItem(): BelongsTo
    {
        return $this->belongsTo(InspectionItem::class);
    }
}
