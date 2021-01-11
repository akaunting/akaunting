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

        return view('components.documents.form.advanced', compact('categories', 'category_type'));
    }
}
