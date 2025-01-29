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
use setasign\Fpdi\Fpdi;

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

        // Add order details to the pdf
        $orderDetails = [
            'number' => $this->order->number,
            'customer_name' => $this->order->billingAddress->name,
        ];
        $sourcePdfPath = Storage::disk('public')->path("pdfs/{$filename}");
        $outputPdfPath = Storage::disk('public')->path("output/{$filename}");

        $this->addOrderDataToPdf($sourcePdfPath, $outputPdfPath, $orderDetails);
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

    public function addOrderDataToPdf($sourcePdfPath, $outputPdfPath, $orderDetails): bool
    {
        $pdf = new FPDI();

        try {
            // Load the existing PDF
            $pdf->setSourceFile($sourcePdfPath); // Get the number of pages in the existing PDF
            $template = $pdf->importPage(1); // Import the first page (you can use other page numbers too)

            // Add a new page to modify (it will inherit the original page's content)
            $pdf->addPage();
            $pdf->useTemplate($template); // Apply the template of the existing page

            // Set the font for adding new text
            $pdf->SetFont('Arial', '', 12);

            // Add order details to the existing page (adjust the coordinates as necessary)
            $pdf->SetXY(10, 50); // Change the position based on where you want to add the text
            $pdf->MultiCell(0, 10, "Order Number: " . $orderDetails['number']);

            $pdf->SetXY(10, 60); // Adjust position as needed
            $pdf->MultiCell(0, 10, "Customer Name: " . $orderDetails['customer_name']);

            // You can add more text or modify the page as needed

            // Output the modified PDF to a new file (use 'I' to output to the browser)
            $pdf->Output('F', $outputPdfPath); // Save it to a file
        } catch (\Exception $e) {
            die(var_dump($e->getMessage()));
        }

        return $outputPdfPath;
    }
}
