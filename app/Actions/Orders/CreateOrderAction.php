<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\Product;

class CreateOrderAction
{
    public function execute(array $data): Order
    {
        $total_price = 0;

        foreach ($data['orderProducts'] as $orderProduct) {
            $total_price += $orderProduct['price'] * $orderProduct['quantity'];
            $product = Product::find($orderProduct['product_id']);

            $product->update(['stock' => $product->stock - $orderProduct['quantity']]);
        }
        $order = Order::create([
            'customer_id' => $data['customer_id'],
            'total_price' => $total_price,
            'status' => $data['status'],
            'order_number' => $data['order_number'],
            'notes' => $data['notes'] ?? '',
            'billing_name' => $data['billing_name'],
            'billing_address' => $data['billing_address'],
            'billing_phone' => $data['billing_phone'],
            'shipping_address' => $data['shipping_address'],
        ]);

        foreach ($data['orderProducts'] as $orderProduct) {
            $order->orderProducts()->create($orderProduct);
        }

        return $order;
    }
}
