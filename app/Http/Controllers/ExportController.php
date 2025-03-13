<?php

namespace App\Http\Controllers;

use SimpleXMLElement;
use App\Models\Scraper;
use Illuminate\Http\Request;
use App\Exports\ScraperExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function exportCsv($id)
    {
        $scraper = Scraper::where('user_id', Auth::id())->findOrFail($id);
        return Excel::download(new ScraperExport($scraper), 'scraper_' . $scraper->id . '.csv');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function exportJson($id)
    {
        $scraper = Scraper::where('user_id', Auth::id())->findOrFail($id);
        $data = [
            'url' => $scraper->url,
            'result' => $scraper->result,
            'created_at' => $scraper->created_at,
        ];

        return response()->json($data)->withHeaders([
            'Content-Disposition' => 'attachment; filename=scraper_' . $scraper->id . '.json',
        ]);
    }

    public function exportPdf($id)
    {
        $scraper = Scraper::where('user_id', Auth::id())->findOrFail($id);
        $pdf = Pdf::loadView('exports.pdf', compact('scraper'));

        return $pdf->download("scraper_" . $scraper->id . ".pdf");
    }


    public function exportXml($id)
    {
        $scraper = Scraper::where('user_id', Auth::id())->findOrFail($id);
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><scraper></scraper>');
$xml->addChild('url', htmlspecialchars($scraper->url));
$xml->addChild('result', htmlspecialchars($scraper->result));
$xml->addChild('created_at', $scraper->created_at);

return response($xml->asXML(), 200)
->header('Content-Type', 'application/xml')
->header('Content-Disposition', 'attachment; filename="scraper_' . $scraper->id . '.xml"');
}
}