<?php namespace App\Models; class SaleItem extends BaseModel { public function material(){return $this->belongsTo(Material::class);} }
