<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessShipmentLabel;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\ShipmentApiService;
use Illuminate\Support\Facades\Response;
use Inertia\{ Inertia };

class ShipmentController extends Controller
{
    protected ShipmentApiService $shipmentApiService;

    public function __construct(ShipmentApiService $shipmentApiService)
    {
        $this->shipmentApiService = $shipmentApiService;
    }

    public function create(Request $request)
    {
        $orderId = $request->input('orderId');
        $order = Order::with(['billingAddress', 'deliveryAddress', 'orderLines'])->findOrFail($orderId);

        $shipmentData = $this->shipmentApiService->newShipment($order);

        if (array_key_exists('error', $shipmentData)) {
            return Inertia::render('Order/Show', [
                'order' => $order,
                'error' => 'Failed to create shipment.',
            ]);
        }

        $shipment = Shipment::create([
            'order_id' => $order->id, // Associate the shipment with the order
            'label' => $shipmentData['data']['labels']['a4']['offset_0'],
            'tracking_url' => $shipmentData['data']['shipments'][0]['tracking_url'],
        ]);

        $order->shipment_id = $shipment->id;
        $order->save();

        ProcessShipmentLabel::dispatch($order, $shipment)->onQueue('default');

        return Inertia::location(route('orders.show', $order->id));
    }

    public function downloadPDF(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $order = Order::with(['shipment'])->findOrFail($request->order);
        $filename = str_replace('#', '', $order->number);
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/storage/output/{$filename}.pdf";

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, "{$filename}.pdf", $headers);

        return Inertia::location(route('orders.show', $order->id));
    }
}
