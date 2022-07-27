<?php

namespace App\View\Components\Form;

use App\Abstracts\View\Component;

class Buttons extends Component
{
    public $groupClass = 'sm:col-span-6';

    public $withoutCancel;

    public $cancel;

    public $cancelRoute;

    public $cancelUrl;

    public $cancelClass = 'px-6 py-1.5 hover:bg-gray-200 rounded-lg ltr:mr-2 rtl:ml-2';

    public $cancelText;

    public $saveDisabled;

    public $saveLoading;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $groupClass = '',
        $cancel = '', $cancelRoute = '', $cancelUrl = '', $cancelClass = '', $cancelText = '', $withoutCancel = false,
        $saveDisabled = '', $saveLoading = ''
    ) {
        $this->groupClass = $this->getGroupClass($groupClass);

        $this->cancel = $this->getCancel($cancel, $cancelRoute, $cancelUrl);
        $this->cancelClass = $this->getCancelClass($cancelClass);
        $this->cancelText = $this->getCancelText($cancelText);
        $this->withoutCancel = $withoutCancel;

        $this->saveDisabled = ! empty($saveDisabled) ? $saveDisabled : 'form.loading';
        $this->saveLoading = ! empty($saveLoading) ? $saveLoading : 'form.loading';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.form.buttons');
    }

    protected function getGroupClass($groupClass)
    {
        if (! empty($groupClass)) {
            return $groupClass;
        }

        return $this->groupClass;
    }

    protected function getCancel($cancel, $route, $url)
    {
        if (! empty($cancel)) {
            return $cancel;
        }

        if (! empty($route)) {
            return $this->getRouteAction($route);
        }

        if (! empty($url)) {
            return $this->getUrlAction($url);
        }

        return '';
    }

    /**
     * Get the action for a "url" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getUrlAction($options)
    {
        if (is_array($options)) {
            return url($options[0], array_slice($options, 1));
        }

        return url($options);
    }

    /**
     * Get the action for a "route" option.
     *
     * @param  array|string $options
     *
     * @return string
     */
    protected function getRouteAction($options)
    {
        if (is_array($options)) {
            $parameters = array_slice($options, 1);

            if (array_keys($options) === [0, 1]) {
                $parameters = head($parameters);
            }

            return route($options[0], $parameters);
        }

        return route($options);
    }

    protected function getCancelClass($cancelClass)
    {
        if (! empty($cancelClass)) {
            return $cancelClass;
        }

        return $this->cancelClass;
    }

    protected function getCancelText($cancelText)
    {
        if (! empty($cancelText)) {
            return $cancelText;
        }

        return trans('general.cancel');
    }
}