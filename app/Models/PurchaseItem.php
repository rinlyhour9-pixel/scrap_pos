<?php namespace App\Models; class PurchaseItem extends BaseModel { public function material(){return $this->belongsTo(Material::class);} }
