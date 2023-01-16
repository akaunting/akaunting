<?php declare(strict_types=1);

return [

    /*
     * List of folders to be analyzed.
     */
    'paths' => [
        base_path('app'),
        base_path('database'),
        base_path('modules'),
        base_path('tests'),
    ],

    /*
     * List of files/folders to be excluded from analysis.
     */
    'exclude' => [
        // base_path('app/helpers.php'),
        // base_path('app/Services'),
    ],

    /*
     * List of your custom Classifiers
     */
    'custom_component_classifier' => [
        'App\Classifiers\BulkAction',
        'App\Classifiers\Event',
        'App\Classifiers\Export',
        'App\Classifiers\Import',
        'App\Classifiers\Job',
        'App\Classifiers\Observer',
        'App\Classifiers\Report',
        'App\Classifiers\Scope',
        'App\Classifiers\Transformer',
        'App\Classifiers\Widget',
    ],

    /*
     * The Strategy used to reject Classes from the project statistics.
     *
     * By default all Classes located in
     * the vendor directory are being rejected and don't
     * count to the statistics.
     *
     * The package ships with 2 strategies:
     * - \Wnx\LaravelStats\RejectionStrategies\RejectVendorClasses::class
     * - \Wnx\LaravelStats\RejectionStrategies\RejectInternalClasses::class
     *
     * If none of the default strategies fit for your usecase, you can
     * write your own class which implements the RejectionStrategy Contract.
     */
    'rejection_strategy' => 'Wnx\LaravelStats\RejectionStrategies\RejectVendorClasses',

    /*
     * Namespaces which should be ignored.
     * Laravel Stats uses the `Str::startsWith()` helper to
     * check if a Namespace should be ignored.
     *
     * You can use `Illuminate` to ignore the entire `Illuminate`-namespace
     * or `Illuminate\Support` to ignore a subset of the namespace.
     */
    'ignored_namespaces' => [
        'Akaunting',
        'Barryvdh',
        'Collective',
        'Composer',
        'ConsoleTVs',
        'Dingo',
        'Doctrine',
        'Dompdf',
        'Facade',
        'Fruitcake',
        'GeneaLabs',
        'GrahamCampbell',
        'Hoa',
        'Http',
        'Illuminate',
        'Intervention',
        'Jenssegers',
        'Kyslik',
        'Laracasts',
        'Laratrust',
        'Lorisleiva',
        'Maatwebsite',
        'Monooso',
        'NunoMaduro',
        'Omnipay',
        'Plank',
        'Psr',
        'Riverskies',
        'SebastianBergmann',
        'Symfony',
        'Wnx\LaravelStats',
    ],

];
