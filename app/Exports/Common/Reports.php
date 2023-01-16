<?php

namespace App\Exports\Common;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class Reports implements FromView, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $view;

    protected $class;

    public function __construct($view, $class)
    {
        $this->view = $view;
        $this->class = $class;
    }

    public function view(): View
    {
        return view($this->view, ['class' => $this->class]);
    }

    public function title(): string
    {
        return 'reports';
    }
}
