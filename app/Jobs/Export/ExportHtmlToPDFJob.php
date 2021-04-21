<?php

namespace App\Jobs\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mpdf\Mpdf;

class ExportHtmlToPDFJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $html;
    /**
     * @var string
     */
    private $filename;


    /**
     * ExportHtmlToPDFJob constructor.
     * @param $html
     * @param string $filename
     */
    public function __construct($html, $filename = "example.pdf")
    {
        //
        $this->html = $html;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {

        $mpdf = new Mpdf([
            'default_font' => 'XB'
        ]);
        $html = mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8');
        if(app()->getLocale() === 'ar') $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);
        return $mpdf->Output($this->filename,'D');
    }
}
