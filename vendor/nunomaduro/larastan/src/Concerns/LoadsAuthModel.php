<?php

declare(strict_types=1);

namespace NunoMaduro\Larastan\Concerns;

use Illuminate\Config\Repository as ConfigRepository;

trait LoadsAuthModel
{
    private function getAuthModel(ConfigRepository $config, ?string $guard = null): ?string
    {
        if (
            ($guard === null && ! ($guard = $config->get('auth.defaults.guard'))) ||
            ! ($provider = $config->get('auth.guards.'.$guard.'.provider')) ||
            ! ($authModel = $config->get('auth.providers.'.$provider.'.model'))
        ) {
            return null;
        }

        return $authModel;
    }
}
