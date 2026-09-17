<?php
namespace App\Services;
use App\Models\Material; use App\Models\StockMovement;
class InventoryService {
 public function move(Material $material, float $quantity, string $type, float $unitCost=0, ?string $referenceType=null, ?int $referenceId=null, ?string $reason=null): void {
  $before=(float)$material->current_stock; $after=$before+$quantity;
  if($after < -0.0001) throw new \DomainException("Insufficient {$material->name} stock.");
  if($quantity>0 && $unitCost>0) $material->average_cost=(($before*(float)$material->average_cost)+($quantity*$unitCost))/$after;
  $material->current_stock=$after; $material->save();
  StockMovement::create(['material_id'=>$material->id,'type'=>$type,'quantity'=>$quantity,'stock_before'=>$before,'stock_after'=>$after,'unit_cost'=>$unitCost,'reference_type'=>$referenceType,'reference_id'=>$referenceId,'reason'=>$reason,'moved_at'=>now()]);
 }
}
