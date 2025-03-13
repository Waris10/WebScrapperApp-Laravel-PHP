<?php

namespace App\Exports;

use App\Models\Scraper;
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
                'images' => json_encode($this->scraper->images),
                'videos' => json_encode($this->scraper->videos),
                'external_links' => json_encode($this->scraper->external_links),
                'created_at' => $this->scraper->created_at,
            ],
        ];
    }


    public function headings(): array
    {
        return [
            'URL',
            'Result',
            'Images',
            'Videos',
            'External Links',
            'Created At',
        ];
    }
}
