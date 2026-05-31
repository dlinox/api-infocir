<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Services;

use App\Models\Dairy\Order;
use Mpdf\Mpdf;

class OrderReceiptService
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * Genera el PDF del recibo simple del pedido y devuelve el binario.
     * Asigna número de recibo la primera vez.
     */
    public function generate(string $orderId, ?int $plantId = null): array
    {
        $order = $this->orderService->findById($orderId, $plantId)->load(['items', 'plant']);

        if (!$order->receipt_number) {
            $order->update([
                'receipt_number'    => 'REC-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
                'receipt_issued_at' => now(),
            ]);
            $order->refresh();
        }

        $mpdf = new Mpdf(['format' => 'A5', 'margin_left' => 12, 'margin_right' => 12, 'margin_top' => 12, 'margin_bottom' => 12]);
        $mpdf->WriteHTML($this->buildHtml($order));

        return [
            'content'  => $mpdf->Output('', 'S'),
            'filename' => "recibo-{$order->code}.pdf",
        ];
    }

    private function buildHtml(Order $order): string
    {
        $rows = '';
        foreach ($order->items as $item) {
            $rows .= '<tr>'
                . '<td>' . e($item->product_name) . '<br><small style="color:#888">' . e($item->presentation_name) . '</small></td>'
                . '<td style="text-align:center">' . (int) $item->quantity . '</td>'
                . '<td style="text-align:right">S/ ' . number_format((float) $item->unit_price, 2) . '</td>'
                . '<td style="text-align:right">S/ ' . number_format((float) $item->subtotal, 2) . '</td>'
                . '</tr>';
        }

        $plantName = e($order->plant?->name ?? 'Vía Láctea');
        $clientLine = e(trim(implode(', ', array_filter([$order->address, $order->district, $order->city]))));

        return <<<HTML
<style>
  body { font-family: sans-serif; color: #1c1917; font-size: 11px; }
  h1 { font-size: 16px; margin: 0; color: #0f766e; }
  .muted { color: #78716c; }
  table { width: 100%; border-collapse: collapse; margin-top: 8px; }
  th, td { padding: 6px 4px; border-bottom: 1px solid #e7e5e4; font-size: 11px; }
  th { text-align: left; color: #57534e; border-bottom: 2px solid #d6d3d1; }
  .total { font-size: 14px; font-weight: bold; text-align: right; margin-top: 10px; }
  .box { background: #f5f5f4; padding: 8px 10px; border-radius: 6px; margin-top: 10px; }
</style>
<table style="border:0; margin:0">
  <tr style="border:0">
    <td style="border:0"><h1>{$plantName}</h1><div class="muted">Recibo de pedido</div></td>
    <td style="border:0; text-align:right">
      <strong>{$order->receipt_number}</strong><br>
      <span class="muted">Pedido {$order->code}</span><br>
      <span class="muted">{$order->receipt_issued_at}</span>
    </td>
  </tr>
</table>
<div class="box">
  <strong>Cliente:</strong> {$order->customer_name} · {$order->customer_phone}<br>
  <span class="muted">{$clientLine}</span>
</div>
<table>
  <thead>
    <tr><th>Producto</th><th style="text-align:center">Cant.</th><th style="text-align:right">P. unit.</th><th style="text-align:right">Subtotal</th></tr>
  </thead>
  <tbody>{$rows}</tbody>
</table>
<div class="total">Total: S/ {$this->money($order->total)}</div>
<p class="muted" style="margin-top:16px; font-size:9px">
  Documento informativo (recibo simple). No constituye comprobante de pago electrónico ante SUNAT.
</p>
HTML;
    }

    private function money($value): string
    {
        return number_format((float) $value, 2);
    }
}
