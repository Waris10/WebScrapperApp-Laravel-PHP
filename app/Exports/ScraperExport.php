<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScraperExport implements FromArray, WithHeadings
{
    protected $scraper;

    public function __construct($scraper)
    {
        $this->scraper = $scraper;
    }

    public function array(): array
    {
        return [
            [
                'url' => $this->scraper->url,
                'result' => $this->scraper->result,
                'created_at' => $this->scraper->created_at,
            ],
        ];
    }


    public function headings(): array
    {
        return [
            'URL',
            'Result',
            'Created At',
        ];
    }
}