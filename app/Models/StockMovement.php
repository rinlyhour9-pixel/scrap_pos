<?php namespace App\Models; class StockMovement extends BaseModel { protected $casts=['moved_at'=>'datetime']; public function material(){return $this->belongsTo(Material::class);} }
