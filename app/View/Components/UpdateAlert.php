<?php

namespace App\View\Components;

use App\Abstracts\View\Component;
use App\Utilities\Versions;
use Illuminate\Support\Arr;

class UpdateAlert extends Component
{
    public $alerts;

        /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $alerts = [])
    {
        $this->alerts = $this->getAlerts($alerts);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.update-alert');
    }

    public function getAlerts()
    {
        $alerts = [];

        $updates = Versions::getUpdates();

        if (! $updates) {
            return $alerts;
        }

        foreach ($updates as $alias => $update) {
            if (! $update->errors) {
                continue;
            }

            foreach ($update->errors as $key => $error) {
                switch ($key) {
                    case 'core':
                        $type = 'danger';
                        break;
                    case 'expires':
                    case 'compatible':
                        $type = 'warning';
                        break;
                    default:
                        $type = 'danger';
                }

                if (is_object($error) || is_array($error)) {
                    foreach ($error as $message) {
                        $alerts[$type][] = $message;
                    }
                } else {
                    $alerts[$type][] = $error;
                }
            }
        }

        return $alerts;
    }
}
