<?php namespace App\Models; class Expense extends BaseModel { public function category(){return $this->belongsTo(ExpenseCategory::class,'expense_category_id');} }
