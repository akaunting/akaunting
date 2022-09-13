<?php

namespace App\View\Components\Script;

use App\Abstracts\View\Component;
use Illuminate\Support\Js;

class Declaration extends Component
{
    /** @var string */
    public $kind;

    /** @var array */
    public $value;

    public $scripts = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $kind = 'var',array $value = []
    ) {
        $this->kind = $kind;
        $this->value = $value;

        $this->scripts = $this->getScripts();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.script.declaration');
    }

    protected function getScripts()
    {
        $scripts = '';

        foreach ($this->value as $key => $value) {
            $scripts .= $this->getScript($key, $value) . "\n";
        }

        return $scripts;
    }

    protected function getScript($key, $value = null)
    {
        $script = $this->kind . ' ' . $key;

        if (! is_null($value)) {
            $script .= ' = ' . Js::from($value)->toHtml() . ';';
        } else {
            $script .= ' = ' . 'null;';
        }

        return $script;
    }
}
