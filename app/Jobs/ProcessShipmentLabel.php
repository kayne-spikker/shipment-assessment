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
        // Save the PDF we retrieve from the QLS API
        $cleanedOrderNumber = str_replace('#', '', $this->order->number);
        $timestamp = $this->shipment->created_at->toDateString();
        $filename = "{$cleanedOrderNumber}_{$timestamp}.pdf";

        $this->savePdfFromUrl($this->shipment->label, $filename);

        $sourcePdfPath = Storage::disk('public')->path("pdfs/{$filename}");
        $outputPdfPath = Storage::disk('public')->path("output/{$filename}");

        $orderLines = $this->order->orderLines->toArray();

        $processedPdf = $this->addOrderDataToPdf($sourcePdfPath, $outputPdfPath, $orderLines);
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

    public function addOrderDataToPdf(string $sourcePdfPath, string $outputPdfPath, array $orderLines): bool
    {
        $pdf = new FPDI();

        try {
            // Load the existing PDF and import the existing pdf into the current FPDI instance
            $pdf->setSourceFile($sourcePdfPath);

            // Setting the template for our FPDI instance
            $template = $pdf->importPage(1);

            // Adding the first page of our FPDI Pdf creation, with the template from the existing pdf
            $pdf->addPage();
            $pdf->useTemplate($template);

            // Get the complete pageWidth
            $pageWidth = $pdf->getPageWidth();

            // Calculate the center position for the pdf file
            $middleX = $pageWidth / 2;

            // Setting the color for drawing shapes (black)
            $pdf->SetDrawColor(0, 0, 0);

            // Draw a line vertically in the middle of the page
            $pdf->Line($middleX, 10, $middleX, 280);

            // Setting the font to as closely match the styling of the existing label
            $pdf->SetFont('Arial', '', 8);

            // Define the headers for our table of order data
            $header = ['sku', 'ean', 'amount'];

            // The width of each column in the same sequence as defined above
            $columnWidths = [30, 50, 15];

            // This sets the X starting position for text/drawing within our PDF
            $pdf->SetX($middleX + 5);

            // This loop creates each of the headers for our table
            foreach ($header as $key => $col) {
                $pdf->Cell($columnWidths[$key], 5, $col, 1, 0, 'C');
            }

            // This moves to the next line,
            // calculating the amount of real-estate occupied by previous steps
            $pdf->Ln();

            // Defines the data we're using from our order lines when creating the rows
            $allowedData = ['sku', 'ean', 'amount_ordered'];

            // This loop inserts the rows of our table (the products ordered)
            foreach ($orderLines as $orderLine) {
                $filteredOrderLine = array_intersect_key($orderLine, array_flip($allowedData));
                $pdf->SetX($middleX + 5);
                foreach ($filteredOrderLine as $key => $cell) {
                    $pdf->Cell($columnWidths[$key], 10, $cell, 1, 0, 'C');
                }
                $pdf->Ln();
            }

            // And finally we save the file
            $pdf->Output('F', $outputPdfPath);
        } catch (\Exception) {
            return false;
        }

        return $outputPdfPath;
    }
}
