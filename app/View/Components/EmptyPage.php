<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use App\Traits\Modules;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EmptyPage extends Component
{
    use Modules;

    /** @var string */
    public $alias;

    /** @var string */
    public $group;

    /** @var string */
    public $page;

    /** @var string */
    public $title;

    /** @var string */
    public $createButtonTitle;

    /** @var string */
    public $importButtonTitle;

    /** @var string */
    public $description;

    /** @var string */
    public $docsCategory;

    /** @var string */
    public $image;

    /** @var string */
    public $imageEmptyPage;

    /** @var bool */
    public $checkPermissionCreate;

    /** @var string */
    public $permissionCreate;

    /** @var array */
    public $buttons;

    /** @var bool */
    public $hideButtonCreate;

    /** @var bool */
    public $hideButtonImport;

    /** @var bool */
    public $hideDocsDescription;

    /** @var string */
    public $importRoute;

    /** @var array */
    public $importRouteParameters;

    /** @var array */
    public $suggestion;

    /** @var array */
    public $suggestions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $alias = '', string $group = '', string $page = '',
        string $title = '', string $createButtonTitle = '', string $importButtonTitle = '', 
        string $description = '', string $docsCategory = 'accounting', string $image = '', 
        string $imageEmptyPage = '', bool $checkPermissionCreate = true, string $permissionCreate = '',
        array $buttons = [], bool $hideButtonCreate = false, bool $hideButtonImport = false,
        bool $hideDocsDescription = false, string $importRoute = '', array $importRouteParameters = []
    ) {
        if (empty($alias) && ! empty($group)) {
            $alias = $group;
        }

        $this->alias = (module($alias) === null) ? 'core': $alias;
        $this->group = $group;
        $this->page = $page;
        $this->docsCategory = $docsCategory;

        $this->title = $this->getTitle($title);
        $this->createButtonTitle = $createButtonTitle;
        $this->importButtonTitle = $importButtonTitle;

        $this->description = $this->getDescription($description);

        $this->imageEmptyPage = $imageEmptyPage;
        $this->image = $this->getImage($page, $image);

        $this->checkPermissionCreate = $checkPermissionCreate;
        $this->permissionCreate = $this->getPermissionCreate($page, $group, $permissionCreate);

        $this->hideButtonCreate = $hideButtonCreate;
        $this->hideButtonImport = $hideButtonImport;
        $this->hideDocsDescription = $hideDocsDescription;

        $this->buttons = $this->getButtons($page, $group, $buttons);

        $this->suggestions = $this->getSuggestionModules();
        $this->suggestion = $this->getSuggestionModule();
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

    protected function getTitle($title = null, $number = 2)
    {
        if (! empty($title)) {
            return $title;
        }

        switch ($this->alias) {
            case 'core':
                $text = 'general.' . $this->page;
                $text2 = 'general.' . Str::replace('-', '_', $this->page);
                break;
            default:
                $text = $this->alias . '::general.' . $this->page;
                $text2 = $this->alias . '::general.' . Str::replace('-', '_', $this->page);
        }

        $title = trans_choice($text, $number);

        if ($title == $text) {
            $title = trans_choice($text2, $number);
        }

        return $title;
    }

    protected function getDescription($description)
    {
        if (! empty($description)) {
            return $description;
        }

        switch ($this->alias) {
            case 'core':
                $text = 'general.empty.' . $this->page;
                $text2 = 'general.empty.' . Str::replace('-', '_', $this->page);
                break;
            default:
                $text = $this->alias . '::general.empty.' . $this->page;
                $text2 = $this->alias . '::general.empty.' . Str::replace('-', '_', $this->page);
        }

        $description = trans($text);

        if ($description == $text) {
            $description = trans($text2);
        }

        if ($this->hideDocsDescription) {
            $docs_url = $this->getDocsUrl();

            if (! empty($docs_url)) {
                $description .= ' ' . trans('general.empty.documentation', ['url' => $docs_url]);
            }
        }

        return $description;
    }

    protected function getDocsUrl()
    {
        switch ($this->alias) {
            case 'core':
                $docs_path = 'user-manual/';

                if (! empty($this->group)) {
                    $docs_path .= $this->group . '/';
                }

                $docs_path .= $this->page;
                break;
            default:
                $docs_path = 'app-manual/' . $this->docsCategory . '/' . $this->alias;
        }

        return 'https://akaunting.com/docs/' . $docs_path;
    }

    protected function getImage($page, $image)
    {
        if (! empty($image)) {
            return $image;
        }

        if (! empty($this->imageEmptyPage)) {
            return asset($this->imageEmptyPage);
        }

        $path = 'public/img/empty_pages/' . $page . '.png';

        if ($this->alias != 'core') {
            $path = 'modules/' . Str::studly($this->alias) . '/Resources/assets/img/empty-' . $page . '.png';

            if (! file_exists($path)) {
                $path = 'public/img/empty_pages/default.png';
            }
        }

        return asset($path);
    }

    protected function getPermissionCreate($page, $group, $permissionCreate)
    {
        if (! empty($permissionCreate)) {
            return $permissionCreate;
        }

        $pages = [
            'reconciliations' => 'create-banking-reconciliations',
            'transfers' => 'create-banking-transfers',
            'vendors' => 'create-purchases-vendors',
            'customers' => 'create-sales-customers',
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

    protected function getButtons($page, $group, $buttons)
    {
        if (! empty($buttons)) {
            $suggestion = $this->getSuggestionModule();

            if (! empty($suggestion)) {
                return array_slice($buttons, 0, 2);
            } else {
                return array_slice($buttons, 0, 3);
            }
        }

        if (! $this->hideButtonCreate) {
            $buttons[] = $this->getCreateButton($page, $group);
        }

        if (! $this->hideButtonImport) {
            $buttons[] = $this->getImportButton();
        }

        return $buttons;
    }

    protected function getCreateButton($page, $group)
    {
        try {
            $route = route($group . '.' . $page . '.create');
        } catch (\Exception $e) {
            $route = route($page . '.create');
        }

        $title = $this->createButtonTitle;

        if (! $title) {
            $title = $this->getTitle(null, 1);
        }

        return [
            'url'           => $route,
            'permission'    => $this->permissionCreate,
            'text'          => trans('general.title.new', ['type' => $title]),
            'description'   => trans('general.empty.actions.new', ['type' => strtolower($title)]),
            'active_badge'  => true,
        ];
    }

    protected function getImportButton()
    {
        $importRoute = $this->getImportRoute($this->importRoute);
        $importRouteParameters = $this->getImportRouteParameters($this->importRouteParameters);

        $title = $this->importButtonTitle;

        if (! $title) {
            $title = $this->getTitle();
        }

        return [
            'url'           => route($importRoute, $importRouteParameters),
            'permission'    => $this->permissionCreate,
            'text'          => trans('import.title', ['type' => $title]),
            'description'   => trans('general.empty.actions.import', ['type' => strtolower($title)]),
            'active_badge'  => false,
        ];
    }

    protected function getImportRoute($importRoute)
    {
        if (! empty($importRoute)) {
            return $importRoute;
        }

        $route = 'import.create';

        return $route;
    }

    protected function getImportRouteParameters($importRouteParameters)
    {
        if (! empty($importRouteParameters)) {
            return $importRouteParameters;
        }

        return array_slice(request()->segments(), -2, 2, true) ;
    }

    public function getSuggestionModule()
    {
        return ! empty($this->suggestions) ? Arr::random($this->suggestions) : false;
    }

    public function getSuggestionModules()
    {
        if ((! $user = user()) || $user->cannot('read-modules-home')) {
            return [];
        }

        if (! $path = Route::current()->uri()) {
            return [];
        }

        $path = str_replace('{company_id}/', '', $path);

        if (! $suggestions = $this->getSuggestions($path)) {
            return [];
        }

        $modules = [];

        foreach ($suggestions->modules as $s_module) {
            if ($this->moduleIsEnabled($s_module->alias)) {
                continue;
            }

            $s_module->action_url = company_id() . '/' . $s_module->action_url;

            $modules[] = $s_module;
        }

        if (empty($modules)) {
            return [];
        }

        return $modules;
    }
}
