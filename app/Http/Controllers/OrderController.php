<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreOrderRequest, UpdateOrderRequest};
use Inertia\{Inertia, Response};
use App\Models\Order;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $orders = Order::with(['billingAddress', 'deliveryAddress', 'orderLines'])->get();
        return Inertia::render('Order/Index', ['orders' => $orders]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Order/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = new Order($request->all());
        $order->save();

        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        $order->load('billingAddress', 'deliveryAddress', 'orderLines', 'shipment');

        return Inertia::render('Order/Show', ['order' => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): Response
    {
        return Inertia::render('Order/Edit', ['order' => $order]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->all());
        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return redirect()->back();
    }
}
