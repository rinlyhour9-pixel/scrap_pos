<?php
namespace App\Services;
use App\Models\{Customer,Material,Payment,Sale,SaleItem}; use Illuminate\Support\Facades\DB;
class SaleService {
 public function create(array $data): Sale { return DB::transaction(function() use($data) {
  $sub=0;$weight=0;$cogs=0; foreach($data['items'] as $item){$m=Material::lockForUpdate()->findOrFail($item['material_id']);$qty=(float)$item['weight'];if($qty<=0 || $qty>(float)$m->current_stock) throw new \DomainException("Insufficient {$m->name} stock.");$sub+=$qty*(float)$item['unit_price'];$weight+=$qty;$cogs+=$qty*(float)$m->average_cost;}
  $total=$sub-(float)($data['discount']??0)+(float)($data['other_cost']??0);$paid=min($total,(float)($data['amount_paid']??0));
  $sale=Sale::create(['number'=>$this->number(),'customer_id'=>$data['customer_id']??null,'sold_at'=>now(),'total_weight'=>$weight,'subtotal'=>$sub,'discount'=>$data['discount']??0,'other_cost'=>$data['other_cost']??0,'grand_total'=>$total,'amount_paid'=>$paid,'balance'=>$total-$paid,'cost_of_goods'=>$cogs,'payment_method'=>$data['payment_method']??'Cash','status'=>'completed','notes'=>$data['notes']??null]);
  $inventory=app(InventoryService::class);foreach($data['items'] as $row){$m=Material::lockForUpdate()->findOrFail($row['material_id']);$qty=(float)$row['weight'];$cost=(float)$m->average_cost;SaleItem::create(['sale_id'=>$sale->id,'material_id'=>$m->id,'weight'=>$qty,'unit_price'=>$row['unit_price'],'subtotal'=>$qty*$row['unit_price'],'cost_per_unit'=>$cost,'cost_total'=>$qty*$cost]);$inventory->move($m,-$qty,'sale',$cost,Sale::class,$sale->id);}
  if($paid>0) Payment::create(['number'=>$this->paymentNumber(),'customer_id'=>$sale->customer_id,'sale_id'=>$sale->id,'amount'=>$paid,'payment_method'=>$sale->payment_method,'paid_at'=>now()]);
  if($sale->customer_id && $sale->balance>0) Customer::whereKey($sale->customer_id)->increment('outstanding_balance',$sale->balance);return $sale;
 }); }
 private function number():string{return 'SAL-'.now()->format('Ymd').'-'.str_pad((string)(Sale::whereDate('created_at',today())->count()+1),4,'0',STR_PAD_LEFT);}
 private function paymentNumber():string{return 'PAY-'.now()->format('Ymd').'-'.str_pad((string)(Payment::whereDate('created_at',today())->count()+1),4,'0',STR_PAD_LEFT);}
}
