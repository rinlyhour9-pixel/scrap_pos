<?php namespace App\Models; class Customer extends BaseModel { public function purchases(){return $this->hasMany(Purchase::class);} public function sales(){return $this->hasMany(Sale::class);} }
