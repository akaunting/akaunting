<?php

namespace App\View\Components\Modules;

use App\Traits\Modules;
use App\Models\Module\Module;
use App\Abstracts\View\Component;
use Illuminate\Support\Facades\Route;

class Items extends Component
{
    use Modules;

    public $type;

    public $modules;

    public $limit;

    public $seeMore;

    public $installedStatus;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $model = [], $limit = 4, $seeMore = false
    ) {
        $this->limit = $limit;
        $this->seeMore = $this->getSeeMore($seeMore, $model);
        $this->modules = $this->getModel($model);
        $this->installedStatus = $this->getInstalledStatus();

        $this->view = $this->getView();
        $this->type = $this->getType();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view($this->view);
    }

    protected function getSeeMore($seeMore, $model)
    {
        if (empty($seeMore)) {
            return $seeMore;
        }

        if (! empty($model) && ($model->current_page < $model->last_page)) {
            return true;
        }

        return false;
    }

    protected function getModel($model)
    {
        if (! empty($model)) {
            if (isset($model->data)) {
                return $model->data;
            }

            return $model;
        }

        $model = [];

        switch ($this->type) {
            case 'banners':
                $model = $this->getBanners();
                break;
            case 'new':
                $model = $this->getNew($this->limit);
                break;
            case 'paid':
                $model = $this->getPaid($this->limit);
                break;
            case 'free':
                $model = $this->getFree($this->limit);
                break;
            case 'pre_sale':
                $model = $this->getPreSale($this->limit);
                break;
            case 'purchased':
                $model = $this->getPurchased($this->limit);
                break;
            case 'installed':
                $model = $this->getInstalled();
                break;
            case 'no-apps':
                $model = $this->getTestimonials();
                break;
        }

        return $model;
    }

    protected function getView()
    {
        if (! empty($this->view)) {
            return $this->view;
        }

        return 'components.modules.items';
    }

    protected function getBanners()
    {
        $model = $this->getBannersOfModules();

        return $model;
    }

    protected function getNew($limit)
    {
        $model = [];

        $response = $this->getNewModules([
            'query' => [
                'limit' => $limit
            ]
        ]);

        if ($response) {
            $model = $response->data;
        }

        return $model;
    }

    protected function getFree($limit)
    {
        $model = [];

        $response = $this->getFreeModules([
            'query' => [
                'limit' => $limit
            ]
        ]);

        if ($response) {
            $model = $response->data;
        }

        return $model;
    }

    protected function getPaid($limit)
    {
        $model = [];

        $response = $this->getPaidModules([
            'query' => [
                'limit' => $limit
            ]
        ]);

        if ($response) {
            $model = $response->data;
        }

        return $model;
    }

    protected function getPreSale($limit)
    {
        $model = [];

        $response = $this->getPreSaleModules([
            'query' => [
                'limit' => $limit
            ]
        ]);

        if ($response) {
            $model = $response->data;
        }

        return $model;
    }

    protected function getPurchased($limit)
    {
        $model = [];
        $data = [];

        if ($limit != 4) {
            $data = [
                'query' => [
                    'limit' => $limit
                ]
            ];
        }

        $response = $this->getMyModules($data);

        if ($response) {
            $model = $response;
        }

        return $model;
    }

    protected function getInstalled()
    {
        $model = [];

        $response = $this->getInstalledModules();

        if ($response) {
            $model = $response;
        }

        return $model;
    }

    protected function getTestimonials()
    {
        $model = [];

        $response = $this->getTestimonialModules();

        if ($response) {
            $model = $response;
        }

        return $model;
    }

    protected function getInstalledStatus()
    {
        $installed = Module::where('company_id', '=', company_id())->pluck('enabled', 'alias')->toArray();

        return $installed;
    }

    protected function getType()
    {
        if (! empty($this->type)) {
            return $this->type;
        }

        $name = Route::currentRouteName();

        $keys = explode('.', $name);

        return count($keys) > 2 ? $keys[1] : last($keys);
    }
}
