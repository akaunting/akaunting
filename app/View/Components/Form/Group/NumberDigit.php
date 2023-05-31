<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;

class NumberDigit extends Form
{
    public $type = 'number_digit';

    public $number_digits;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'number_digit';
        }
        
        $this->number_digits = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
        ];

        if (empty($this->selected) && empty($this->getParentData('model'))) {
            $this->selected = setting('invoice.number_digit');
        }

        return view('components.form.group.number_digit');
    }
}
