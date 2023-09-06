<?php declare(strict_types=1);

namespace Wnx\LaravelStats;

use Exception;
use ReflectionClass as NativeReflectionClass;
use Wnx\LaravelStats\Classifiers\BladeComponentClassifier;
use Wnx\LaravelStats\Classifiers\CommandClassifier;
use Wnx\LaravelStats\Classifiers\ControllerClassifier;
use Wnx\LaravelStats\Classifiers\CustomCastClassifier;
use Wnx\LaravelStats\Classifiers\DatabaseFactoryClassifier;
use Wnx\LaravelStats\Classifiers\EventClassifier;
use Wnx\LaravelStats\Classifiers\EventListenerClassifier;
use Wnx\LaravelStats\Classifiers\JobClassifier;
use Wnx\LaravelStats\Classifiers\LivewireComponentClassifier;
use Wnx\LaravelStats\Classifiers\MailClassifier;
use Wnx\LaravelStats\Classifiers\MiddlewareClassifier;
use Wnx\LaravelStats\Classifiers\MigrationClassifier;
use Wnx\LaravelStats\Classifiers\ModelClassifier;
use Wnx\LaravelStats\Classifiers\NotificationClassifier;
use Wnx\LaravelStats\Classifiers\Nova\ActionClassifier;
use Wnx\LaravelStats\Classifiers\Nova\DashboardClassifier;
use Wnx\LaravelStats\Classifiers\Nova\FilterClassifier;
use Wnx\LaravelStats\Classifiers\Nova\LensClassifier;
use Wnx\LaravelStats\Classifiers\Nova\ResourceClassifier as NovaResourceClassifier;
use Wnx\LaravelStats\Classifiers\NullClassifier;
use Wnx\LaravelStats\Classifiers\ObserverClassifier;
use Wnx\LaravelStats\Classifiers\PolicyClassifier;
use Wnx\LaravelStats\Classifiers\RequestClassifier;
use Wnx\LaravelStats\Classifiers\ResourceClassifier;
use Wnx\LaravelStats\Classifiers\RuleClassifier;
use Wnx\LaravelStats\Classifiers\SeederClassifier;
use Wnx\LaravelStats\Classifiers\ServiceProviderClassifier;
use Wnx\LaravelStats\Classifiers\Testing\BrowserKitTestClassifier;
use Wnx\LaravelStats\Classifiers\Testing\DuskClassifier;
use Wnx\LaravelStats\Classifiers\Testing\PhpUnitClassifier;
use Wnx\LaravelStats\Contracts\Classifier as ClassifierContract;

class Classifier
{
    public const DEFAULT_CLASSIFIER = [
        LivewireComponentClassifier::class,
        ControllerClassifier::class,
        ModelClassifier::class,
        CommandClassifier::class,
        RuleClassifier::class,
        PolicyClassifier::class,
        MiddlewareClassifier::class,
        EventClassifier::class,
        EventListenerClassifier::class,
        ObserverClassifier::class,
        MailClassifier::class,
        NotificationClassifier::class,
        JobClassifier::class,
        MigrationClassifier::class,
        RequestClassifier::class,
        ResourceClassifier::class,
        SeederClassifier::class,
        ServiceProviderClassifier::class,
        BladeComponentClassifier::class,
        CustomCastClassifier::class,
        DatabaseFactoryClassifier::class,
        BrowserKitTestClassifier::class,
        DuskClassifier::class,
        PhpUnitClassifier::class,

        // Nova Classifiers
        ActionClassifier::class,
        DashboardClassifier::class,
        FilterClassifier::class,
        LensClassifier::class,
        NovaResourceClassifier::class,
    ];

    /**
     * @var array
     */
    private $availableClassifiers = [];

    public function __construct()
    {
        $this->availableClassifiers = array_merge(
            self::DEFAULT_CLASSIFIER,
            config('stats.custom_component_classifier', [])
        );
    }

    public function getClassifierForClassInstance(ReflectionClass $class): ClassifierContract
    {
        foreach ($this->availableClassifiers as $classifier) {
            $classifierInstance = new $classifier();

            if (! $this->implementsContract($classifier)) {
                throw new Exception("Classifier {$classifier} does not implement ".ClassifierContract::class.'.');
            }

            try {
                $satisfied = $classifierInstance->satisfies($class);
            } catch (Exception $e) {
                $satisfied = false;
            }

            if ($satisfied) {
                return $classifierInstance;
            }
        }

        return new NullClassifier;
    }

    /**
     * Check if a class implements our Classifier Contract.
     *
     * @throws \ReflectionException
     */
    protected function implementsContract(string $classifier): bool
    {
        return (new NativeReflectionClass($classifier))->implementsInterface(ClassifierContract::class);
    }
}
