<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => [
                'en' => $this->getTranslation('name', 'en'),
                'ar' => $this->getTranslation('name', 'ar')
            ],
            'description' => $this->description,
            'price' => $this->price,
            'club' => $this->club,
            'product_types_id' =>  $this->productType,
            'images'=>ImagesProductResource::collection($this->images)
            ,'players'=>$this->players
            ,'coachs'=>$this->coachs
        ];
    }
}
