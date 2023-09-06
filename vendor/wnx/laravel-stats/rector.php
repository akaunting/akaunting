<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SETS, [
        SetList::CODE_QUALITY,
        SetList::DEAD_DOC_BLOCK,
    ]);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);

    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);
};
