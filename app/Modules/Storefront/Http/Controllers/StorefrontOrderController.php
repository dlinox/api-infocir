<?php

namespace App\Modules\Storefront\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Sales\Order\Services\OrderService;
use App\Modules\Storefront\Http\Requests\StorefrontOrderRequest;

class StorefrontOrderController
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store(StorefrontOrderRequest $request)
    {
        $orders = $this->orderService->createOrder($request->validated());

        $data = array_map(fn ($order) => [
            'code'       => $order->code,
            'total'      => (float) $order->total,
            'plantName'  => $order->plant?->name,
            'plantPhone' => $order->plant?->cellphone,
        ], $orders);

        return ApiResponse::created($data, 'Pedido registrado correctamente');
    }
}
