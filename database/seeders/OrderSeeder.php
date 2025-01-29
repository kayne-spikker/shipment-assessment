<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderLine;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Order data
        $orderData = [
            'number' => '#958201',
            'billing_address' => [
                'companyname' => null,
                'name' => 'John Doe',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address_line_2' => '',
                'zipcode' => '3316GD',
                'city' => 'Dordrecht',
                'country' => 'NL',
                'email' => 'email@example.com',
                'phone' => '0101234567',
            ],
            'delivery_address' => [
                'companyname' => '',
                'name' => 'John Doe',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address_line_2' => '',
                'zipcode' => '3316GD',
                'city' => 'Dordrecht',
                'country' => 'NL',
            ],
            'order_lines' => [
                [
                    'amount_ordered' => 2,
                    'name' => 'Jeans - Black - 36',
                    'sku' => 69205,
                    'ean' => '8710552295268',
                ],
                [
                    'amount_ordered' => 1,
                    'name' => 'Sjaal - Rood Oranje',
                    'sku' => 25920,
                    'ean' => '3059943009097',
                ],
            ],
        ];

        // Create the order
        $order = Order::create([
            'number' => $orderData['number'],
        ]);

        // Create billing address
        Address::create([
            'order_id' => $order->id,
            'type' => 'billing',
            'companyname' => $orderData['billing_address']['companyname'],
            'name' => $orderData['billing_address']['name'],
            'street' => $orderData['billing_address']['street'],
            'housenumber' => $orderData['billing_address']['housenumber'],
            'address_line_2' => $orderData['billing_address']['address_line_2'],
            'zipcode' => $orderData['billing_address']['zipcode'],
            'city' => $orderData['billing_address']['city'],
            'country' => $orderData['billing_address']['country'],
            'email' => $orderData['billing_address']['email'],
            'phone' => $orderData['billing_address']['phone'],
        ]);

        // Create delivery address
        Address::create([
            'order_id' => $order->id,
            'type' => 'delivery',
            'companyname' => $orderData['delivery_address']['companyname'],
            'name' => $orderData['delivery_address']['name'],
            'street' => $orderData['delivery_address']['street'],
            'housenumber' => $orderData['delivery_address']['housenumber'],
            'address_line_2' => $orderData['delivery_address']['address_line_2'],
            'zipcode' => $orderData['delivery_address']['zipcode'],
            'city' => $orderData['delivery_address']['city'],
            'country' => $orderData['delivery_address']['country'],
        ]);

        // Create order lines
        foreach ($orderData['order_lines'] as $line) {
            OrderLine::create([
                'order_id' => $order->id,
                'amount_ordered' => $line['amount_ordered'],
                'name' => $line['name'],
                'sku' => $line['sku'],
                'ean' => $line['ean'],
            ]);
        }
    }
}
