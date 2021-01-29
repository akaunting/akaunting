<?php

namespace App\View\Components\Documents\Form;

use App\Abstracts\View\Components\DocumentForm as Component;
use App\Models\Setting\Category;

class Advanced extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $category_type = $this->categoryType;

        if ($category_type) {
            $categories = Category::$category_type()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');
        } else {
            $categories = Category::enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');
        }

        $recurring_class = 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
        $more_class = 'col-sm-6 col-md-6 col-lg-6 col-xl-6';
        $more_form_class = 'col-md-12'; 

        if ($this->hideRecurring && (!$this->hideCategory || !$this->hideAttachment)) {
            $more_class = 'col-sm-12 col-md-12 col-lg-12 col-xl-12';
            $more_form_class = 'col-md-6';
        } else if ($this->hideRecurring && ($this->hideCategory && $this->hideAttachment)) {
            $recurring_class = 'col-sm-12 col-md-12 col-lg-12 col-xl-12';
        }

        $file_type_mimes = explode(',', config('filesystems.mimes'));

        $file_types = [];

        foreach ($file_type_mimes as $mime) {
            $file_types[] = '.' . $mime;
        }

        $file_types = implode(',', $file_types);

        return view('components.documents.form.advanced', compact('categories', 'category_type', 'recurring_class', 'more_class', 'more_form_class', 'file_types'));
    }
}
