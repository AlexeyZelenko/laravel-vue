<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'collection_id',
        'translate_title',
        'translate_description',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function items()
    {
        return $this->hasMany(CollectionItem::class, 'collection_id');
    }
}
