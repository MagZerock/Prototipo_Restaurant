<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

class Order extends Model {
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_email',
        'total_amount',
        'transaction_fee',
        'status',
        'order_date_time'
    ];

    public function details() {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

    public static function getAll() {
        $orders = self::with(['details.menuItem'])->orderBy('created_at', 'desc')->get();
        return self::mapCompatibility($orders);
    }

    public static function add($data) {
        $orderId = $data['id'] ?? (string)(time() . rand(100, 999));
        
        return DB::transaction(function() use ($orderId, $data) {
            $order = self::create([
                'order_id' => $orderId,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'total_amount' => $data['total'],
                'transaction_fee' => $data['transaction_fee'] ?? 0,
                'status' => 'Pendiente',
                'order_date_time' => time()
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $itemId => $item) {
                    $order->details()->create([
                        'item_id' => $itemId,
                        'quantity' => $item['quantity'],
                        'selling_price' => $item['selling_price'] ?? $item['price'],
                        'ingredient_cost' => $item['ingredient_cost'] ?? 0,
                        'removed_ingredients' => json_encode($item['ingredients'] ?? [])
                    ]);
                }
            }

            return $orderId;
        });
    }

    public static function updateStatus($id, $status) {
        return DB::transaction(function() use ($id, $status) {
            $order = self::where('order_id', $id)->first();
            if (!$order) return false;

            $oldStatus = $order->status;
            $order->status = $status;
            $order->save();

            if ($status === 'Completado' && $oldStatus !== 'Completado') {
                self::processInventoryDiscount($id);
            }

            return true;
        });
    }

    public static function processInventoryDiscount($orderId) {
        $order = self::with(['details.menuItem.ingredients'])->where('order_id', $orderId)->first();
        if (!$order) return;

        foreach ($order->details as $detail) {
            $dish = $detail->menuItem;
            if (!$dish) continue;

            foreach ($dish->ingredients as $ingredient) {
                $totalNeeded = $detail->quantity * ($ingredient->pivot->quantity_required ?? 0);
                if ($totalNeeded <= 0) continue;

                $batches = InventoryBatch::where('sku_code', $ingredient->sku_code)
                    ->where('quantity', '>', 0)
                    ->orderBy('purchase_date', 'asc')
                    ->get();

                $remainingToDiscount = $totalNeeded;

                foreach ($batches as $batch) {
                    if ($remainingToDiscount <= 0) break;

                    if ($batch->quantity >= $remainingToDiscount) {
                        $batch->quantity -= $remainingToDiscount;
                        $remainingToDiscount = 0;
                    } else {
                        $remainingToDiscount -= $batch->quantity;
                        $batch->quantity = 0;
                    }
                    $batch->save();
                }

                if ($remainingToDiscount > 0) {
                    throw new \Exception("Stock Insuficiente para " . $ingredient->name . ". Faltan " . $remainingToDiscount . " " . $ingredient->unit_of_measurement);
                }
            }
        }
    }

    public static function getUserHistory($email, $date = null) {
        $query = self::where('customer_email', $email)
                      ->with(['details.menuItem']);
        
        if ($date) {
            $start = strtotime($date . ' 00:00:00');
            $end = strtotime($date . ' 23:59:59');
            $query->whereBetween('order_date_time', [$start, $end]);
        }

        $orders = $query->orderBy('order_date_time', 'desc')->get();
        
        return self::mapCompatibility($orders);
    }

    public static function mapCompatibility($orders) {
        if (!$orders) return [];
        
        $result = [];
        foreach ($orders as $order) {
            $o = $order->toArray();
            $o['id'] = $order->order_id;
            $o['total'] = $order->total_amount;
            $o['items'] = [];
            $o['created_at'] = date('d/m/Y H:i', $order->order_date_time);

            if ($order->details) {
                foreach ($order->details as $detail) {
                    $item = $detail->toArray();
                    $item['name'] = $detail->menuItem->name ?? 'Plato Eliminado';
                    $item['ingredients'] = json_decode($detail->removed_ingredients ?? '[]', true);
                    $o['items'][] = $item;
                }
            }
            $result[] = $o;
        }
        return $result;
    }
}
