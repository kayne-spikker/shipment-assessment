<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessShipmentLabel implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected Order $order;
    protected Shipment $shipment;
    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, Shipment $shipment)
    {
        $this->order = $order;
        $this->shipment = $shipment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Save the PDF label provided externally
        $cleanedOrderNumber = str_replace('#', '', $this->order->number);
        $timestamp = $this->shipment->created_at->toDateString();
        $filename = "{$cleanedOrderNumber}_{$timestamp}.pdf";
        $this->savePdfFromUrl($this->shipment->label, $filename);

        //
    }

    public function savePdfFromUrl(string $fileUrl, string $filename)
    {
        $response = Http::get($fileUrl);

        if ($response->successful()) {
            $content = $response->body();

            Storage::disk('public')->put("pdfs/{$filename}", $content);

            return response()->json(['message' => 'PDF saved successfully.']);
        }

        abort(404, 'File not found or could not be downloaded.');
    }

    public function addOrderDataToPdf($sourcePdfPath, $outputPdfPath, $orderDetails)
    {

    }
}
