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
        string $page, string $imageEmptyPage = '', string $textEmptyPage = '', string $textPage = '',
        string $urlDocsPath = '', bool $checkPermissionCreate = true, string $permissionCreate  = '', string $routeCreate = ''
    ) {
        $this->page = $page;
        $this->imageEmptyPage = $this->getImageEmptyPage($page, $imageEmptyPage);
        $this->textEmptyPage = $this->getTextEmptyPage($page, $textEmptyPage);
        $this->textPage = $this->getTextPage($page, $textPage);
        $this->urlDocsPath = $this->getUrlDocsPath($page, $urlDocsPath);
        $this->checkPermissionCreate = $checkPermissionCreate;
        $this->permissionCreate = $this->getPermissionCreate($page, $permissionCreate);
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

    protected function getUrlDocsPath($page, $urlDocsPath)
    {
        if ($urlDocsPath) {
            return $urlDocsPath;
        }

        return 'https://akaunting.com/docs/user-manual/' . $page;
    }

    protected function getPermissionCreate($page, $permissionCreate)
    {
        if ($permissionCreate) {
            return $permissionCreate;
        }

        $pages = [
            'items' => 'create-commen-items',
        ];

        if (array_key_exists($page, $pages)) {
            $permissionCreate = $pages[$page];
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
