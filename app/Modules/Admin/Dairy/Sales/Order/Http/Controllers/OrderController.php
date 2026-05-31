<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Sales\Order\Http\Requests\Order\OrderItemRequest;
use App\Modules\Admin\Dairy\Sales\Order\Http\Requests\Order\OrderStatusRequest;
use App\Modules\Admin\Dairy\Sales\Order\Http\Resources\Order\OrderDataTableItemResource;
use App\Modules\Admin\Dairy\Sales\Order\Http\Resources\Order\OrderFormResource;
use App\Modules\Admin\Dairy\Sales\Order\Services\OrderReceiptService;
use App\Modules\Admin\Dairy\Sales\Order\Services\OrderService;

class OrderController
{
    public function __construct(
        private OrderService $orderService,
        private OrderReceiptService $orderReceiptService,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->orderService->dataTable($request);
        $items['data'] = OrderDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $order = $this->orderService->findById($id);
        return ApiResponse::success(new OrderFormResource($order));
    }

    public function updateStatus(OrderStatusRequest $request, string $id)
    {
        $order = $this->orderService->updateStatus($id, $request->validated()['status']);
        return ApiResponse::success(new OrderFormResource($order), 'Estado del pedido actualizado correctamente');
    }

    public function addItem(OrderItemRequest $request, string $id)
    {
        $data = $request->validated();
        $order = $this->orderService->addItem($id, (int) $data['presentation_id'], max(1, (int) $data['quantity']));
        return ApiResponse::success(new OrderFormResource($order), 'Producto agregado al pedido');
    }

    public function updateItem(OrderItemRequest $request, string $id, string $itemId)
    {
        $order = $this->orderService->updateItemQuantity($id, (int) $itemId, (int) $request->validated()['quantity']);
        return ApiResponse::success(new OrderFormResource($order), 'Pedido actualizado');
    }

    public function removeItem(string $id, string $itemId)
    {
        $order = $this->orderService->removeItem($id, (int) $itemId);
        return ApiResponse::success(new OrderFormResource($order), 'Producto eliminado del pedido');
    }

    public function presentations(string $id)
    {
        return ApiResponse::success($this->orderService->plantPresentations($id));
    }

    public function receipt(string $id)
    {
        $pdf = $this->orderReceiptService->generate($id);
        return response($pdf['content'], 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $pdf['filename'] . '"',
        ]);
    }
}
