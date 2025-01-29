<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\ShipmentApiService;
use Inertia\{ Inertia, Response };

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

        return Inertia::render('Order/Show', [
            'order' => $order,
            'shipment' => $shipmentData,
        ]);
    }
}
