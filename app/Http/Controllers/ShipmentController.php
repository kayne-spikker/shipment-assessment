<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use App\Services\ShipmentApiService;

class ShipmentController extends Controller
{
    protected ShipmentApiService $shipmentApiService;

    public function __construct(ShipmentApiService $shipmentApiService)
    {
        $this->shipmentApiService = $shipmentApiService;
    }

    public function create(Request $request): JsonResponse
    {
        $orderId = $request->input('orderId');
        $shipmentData = $this->shipmentApiService->newShipment((int)$orderId);

        return response()->json($shipmentData);
    }
}
