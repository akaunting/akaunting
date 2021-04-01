<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EmptyPage extends Component
{
    /** @var string */
    public $page;

    /** @var string */
    public $group;

    /** @var string */
    public $imageEmptyPage;

    /** @var string */
    public $textEmptyPage;

    /** @var string */
    public $textPage;

    /** @var string */
    public $urlDocsPath;

    /** @var bool */
    public $checkPermissionCreate;

    /** @var string */
    public $permissionCreate;

    /** @var string */
    public $routeCreate;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $page, string $group = '', string $imageEmptyPage = '', string $textEmptyPage = '', string $textPage = '',
        string $urlDocsPath = '', bool $checkPermissionCreate = true, string $permissionCreate  = '', string $routeCreate = ''
    ) {
        $this->page = $page;
        $this->group = $group;
        $this->imageEmptyPage = $this->getImageEmptyPage($page, $imageEmptyPage);
        $this->textEmptyPage = $this->getTextEmptyPage($page, $textEmptyPage);
        $this->textPage = $this->getTextPage($page, $textPage);
        $this->urlDocsPath = $this->getUrlDocsPath($page, $group, $urlDocsPath);
        $this->checkPermissionCreate = $checkPermissionCreate;
        $this->permissionCreate = $this->getPermissionCreate($page, $group, $permissionCreate);
        $this->routeCreate = $this->getRouteCreate($page, $routeCreate);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.empty-page');
    }

    protected function getImageEmptyPage($page, $imageEmptyPage)
    {
        if ($imageEmptyPage) {
            return $imageEmptyPage;
        }

        return 'public/img/empty_pages/' . $page . '.png';
    }

    protected function getTextEmptyPage($page, $textEmptyPage)
    {
        if ($textEmptyPage) {
            return $textEmptyPage;
        }

        return 'general.empty.' . $page;
    }

    protected function getTextPage($page, $textPage)
    {
        if ($textPage) {
            return $textPage;
        }

        return 'general.' . $page;
    }

    protected function getUrlDocsPath($page, $group, $urlDocsPath)
    {
        if ($urlDocsPath) {
            return $urlDocsPath;
        }

        $docs_path = $page;
        
        if (!empty($group)) {
            $docs_path = $group . '/' . $page;
        }

        return 'https://akaunting.com/docs/user-manual/' . $docs_path;
    }

    protected function getPermissionCreate($page, $group, $permissionCreate)
    {
        if ($permissionCreate) {
            return $permissionCreate;
        }

        $pages = [
            'reconciliations' => 'create-banking-reconciliations',
            'transfers' => 'create-banking-transfers',
            'payments' => 'create-purchases-payments',
            'vendors' => 'create-purchases-vendors',
            'customers' => 'create-sales-customers',
            'revenues' => 'create-sales-revenues',
            'taxes' => 'create-settings-taxes',
            'items' => 'create-common-items',
        ];

        if (array_key_exists($page, $pages)) {
            $permissionCreate = $pages[$page];
        }

        if (empty($permissionCreate) && !empty($group)) {
            $permissionCreate = 'create-' . $group . '-' . $page;
        }

        return $permissionCreate;
    }

    protected function getRouteCreate($page, $routeCreate)
    {
        if ($routeCreate) {
            return $routeCreate;
        }

        return $page . '.create';
    }
}
