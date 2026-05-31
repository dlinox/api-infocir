<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Services;

use Illuminate\Http\Request;
use App\Models\Dairy\Order;
use App\Modules\Admin\Dairy\Sales\Order\Repositories\OrderRepository;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    public function dataTable(Request $request, ?int $plantId = null)
    {
        return $this->orderRepository->dataTable($request, $plantId);
    }

    public function findById(string $id, ?int $plantId = null)
    {
        return $this->orderRepository->findById($id, $plantId);
    }

    public function updateStatus(string $id, string $status, ?int $plantId = null)
    {
        return $this->orderRepository->updateStatus($id, $status, $plantId);
    }

    /**
     * @return Order[]
     */
    public function createOrder(array $data): array
    {
        return $this->orderRepository->createForCart($data);
    }

    public function addItem(string $orderId, int $presentationId, int $quantity, ?int $plantId = null): Order
    {
        return $this->orderRepository->addItem($orderId, $presentationId, $quantity, $plantId);
    }

    public function updateItemQuantity(string $orderId, int $itemId, int $quantity, ?int $plantId = null): Order
    {
        return $this->orderRepository->updateItemQuantity($orderId, $itemId, $quantity, $plantId);
    }

    public function removeItem(string $orderId, int $itemId, ?int $plantId = null): Order
    {
        return $this->orderRepository->removeItem($orderId, $itemId, $plantId);
    }

    public function plantPresentations(string $orderId, ?int $plantId = null): array
    {
        return $this->orderRepository->plantPresentations($orderId, $plantId);
    }
}
