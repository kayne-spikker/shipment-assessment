<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Http;

class ShipmentApiService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('services.shipment_api.url');
        $this->username = config('services.shipment_api.user');
        $this->password = config('services.shipment_api.pass');
    }

    public function newShipment(Order $order)
    {
        $order = $order->toArray();

        // Usually brandId and companyId would be provided through dynamic data
        // For example by being attached to the user (company) that is logged into the portal
        $brandId = 'e41c8d26-bdfd-4999-9086-e5939d67ae28';
        $companyId = '9e606e6b-44a4-4a4e-a309-cc70ddd3a103';

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(10)
                ->retry(3, 100)
                ->post("{$this->baseUrl}/{$companyId}/shipments", [
                    'brand_id' => $brandId,
                    'reference' => $order['number'],
                    'weight' => 1000,
                    'product_id' => 2,
                    'product_combination_id' => 3,
                    'cod_amount' => 0,
                    'piece_total' => 1,
                    'receiver_contact' => [
                        'companyname' => $order['delivery_address']['companyname'],
                        'name' => $order['delivery_address']['name'],
                        'street' => $order['delivery_address']['street'],
                        'housenumber' => $order['delivery_address']['housenumber'],
                        'postalcode' => $order['delivery_address']['zipcode'],
                        'locality' => $order['delivery_address']['city'],
                        'country' => $order['delivery_address']['country'],
                        'email' => $order['billing_address']['email'],
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return ['error' => 'API request failed'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
