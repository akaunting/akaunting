<?php

namespace App\View\Components\Table;

use App\Abstracts\View\Component;
use Illuminate\Support\Arr;
use ReflectionProperty;

class Tr extends Component
{
    /** @var string */
    public $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($class = '') {
        $this->class = $this->getClass($class);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.table.tr');
    }

    protected function getClass($class)
    {
        if (! empty($class)) {
            return $class;
        }

        $self = new ReflectionProperty($this::class, 'methodCache');
        $self->setAccessible(true);

        $values = $self->getValue();

        $self = new ReflectionProperty($this::class, 'factory');
        $self->setAccessible(true);

        $factory = $self->getValue();

        if (array_key_exists('App\View\Components\Table\Tbody', $values)
            && ($factory instanceof \Illuminate\View\Factory && $factory->getLoopStack())
        ) {
            return 'relative flex items-center px-1 group border-b hover:bg-gray-100';
        }

        else if (array_key_exists('App\View\Components\Table\Thead', $values)) {
            return 'flex items-center px-1';
        }

        return '';
    }
}
