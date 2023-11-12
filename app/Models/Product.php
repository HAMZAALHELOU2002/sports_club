<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory;
    use HasTranslations;
    public $translatable =['name'];
    protected $guarded = [''];

    public function club()
    {
        return $this->belongsTo(Club::class);

    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_types_id', 'id');
    }

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_products', 'product_id', 'player_id');
    }

    public function coachs()
    {
        return $this->belongsToMany(Coach::class, 'coach_products', 'product_id', 'coach_id');
    }
}
