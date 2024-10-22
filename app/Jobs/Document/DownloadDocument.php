<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentPrinting;
use App\Traits\Documents;

class DownloadDocument extends Job
{
    use Documents;

    public $document;

    public $folder_path;

    public $zip_archive;

    public $close_zip;

    public $method;

    public function __construct($document, $folder_path = null, $zip_archive = null, $close_zip = false, $method = 'save')
    {
        $this->document = $document;
        $this->folder_path = $folder_path;
        $this->zip_archive = $zip_archive;
        $this->close_zip = $close_zip;
        $this->method = $method;
    }

    public function handle()
    {
        event(new DocumentPrinting($this->document));

        $data = [
            $this->document->type => $this->document,
            'currency_style' => true
        ];

        $view = view($this->document->template_path, $data)->render();
            
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getDocumentFileName($this->document);

        switch ($this->method) {
            case 'download':
                return $pdf->download($file_name);

                break;
            default:
                if (empty($this->zip_archive)) {
                    return;
                }

                $pdf_path = get_storage_path($this->folder_path . $file_name);

                // Save the PDF file into temp folder
                $pdf->save($pdf_path);

                $this->zip_archive->addFile($pdf_path, $file_name);

                if ($this->close_zip) {
                    $this->zip_archive->close();
                }

                return;

                break;
        }
    }
}