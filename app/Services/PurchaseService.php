<?php
namespace App\Services;
use App\Models\{Customer,Material,Payment,Purchase,PurchaseItem}; use Illuminate\Support\Facades\DB;
class PurchaseService {
 public function create(array $data): Purchase { return DB::transaction(function() use($data) {
  $sub=0;$weight=0; foreach($data['items'] as $item){$net=max(0,(float)$item['gross_weight']-(float)($item['tare_weight']??0)); if($net<=0) throw new \DomainException('Net weight must be greater than zero.'); $sub+=$net*(float)$item['unit_price'];$weight+=$net;}
  $total=$sub-(float)($data['discount']??0)+(float)($data['other_cost']??0);$paid=min($total,(float)($data['amount_paid']??0));
  $purchase=Purchase::create(['number'=>$this->number(),'customer_id'=>$data['customer_id']??null,'supplier_id'=>$data['supplier_id']??null,'purchased_at'=>now(),'total_weight'=>$weight,'subtotal'=>$sub,'discount'=>$data['discount']??0,'other_cost'=>$data['other_cost']??0,'grand_total'=>$total,'amount_paid'=>$paid,'balance'=>$total-$paid,'payment_method'=>$data['payment_method']??'Cash','status'=>'completed','notes'=>$data['notes']??null]);
  $inventory=app(InventoryService::class); foreach($data['items'] as $row){$m=Material::lockForUpdate()->findOrFail($row['material_id']);$net=max(0,(float)$row['gross_weight']-(float)($row['tare_weight']??0));$line=$net*(float)$row['unit_price'];$item=PurchaseItem::create(['purchase_id'=>$purchase->id,'material_id'=>$m->id,'gross_weight'=>$row['gross_weight'],'tare_weight'=>$row['tare_weight']??0,'net_weight'=>$net,'unit_price'=>$row['unit_price'],'subtotal'=>$line,'cost_per_unit'=>$row['unit_price']]);$inventory->move($m,$net,'purchase',(float)$row['unit_price'],Purchase::class,$purchase->id);}
  if($paid>0) Payment::create(['number'=>$this->paymentNumber(),'customer_id'=>$purchase->customer_id,'purchase_id'=>$purchase->id,'amount'=>$paid,'payment_method'=>$purchase->payment_method,'paid_at'=>now()]);
  if($purchase->customer_id && $purchase->balance>0) Customer::whereKey($purchase->customer_id)->increment('outstanding_balance',$purchase->balance);
  return $purchase;
 }); }
 private function number():string{return 'PUR-'.now()->format('Ymd').'-'.str_pad((string)(Purchase::whereDate('created_at',today())->count()+1),4,'0',STR_PAD_LEFT);}
 private function paymentNumber():string{return 'PAY-'.now()->format('Ymd').'-'.str_pad((string)(Payment::whereDate('created_at',today())->count()+1),4,'0',STR_PAD_LEFT);}
}
