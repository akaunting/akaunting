<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Composer;

class LicenseAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not rely on dependencies you are not legally allowed to use.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_CRITICAL;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 60;

    /**
     * The blacklisted packages.
     *
     * @var \Illuminate\Support\Collection
     */
    public $blacklistedPackages;

    /**
     * All the packages used by the application.
     *
     * @var \Illuminate\Support\Collection
     */
    public $allPackages;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application has a total of {$this->blacklistedPackages->count()} package(s) that you may not be legally "
            ."allowed to use. By default, we assume the MIT, Apache-2.0, ISC, BSD Clause 2 & 3 and LGPL licenses to be "
            ."legally valid for use for proprietary or commercial applications. However, you are free to change this "
            ."in the Enlightn config. Unsafe packages include {$this->formatBlacklistedPackages()}";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Composer $composer
     * @return void
     */
    public function handle(Composer $composer)
    {
        $whitelistedLicenses = array_map('strtoupper', config('enlightn.license_whitelist', [
            'Apache-2.0', 'Apache2', 'BSD-2-Clause', 'BSD-3-Clause', 'LGPL-2.1-only', 'LGPL-2.1',
            'LGPL-2.1-or-later', 'LGPL-3.0', 'LGPL-3.0-only', 'LGPL-3.0-or-later', 'MIT', 'ISC',
            'CC0-1.0', 'Unlicense', 'WTFPL',
        ]));

        $commercialPackages = config('enlightn.commercial_packages', []);

        $this->allPackages = $composer->getLicenses();
        $this->blacklistedPackages = $this->allPackages->map(function ($licenses) {
            return array_map('strtoupper', $licenses);
        })->filter(function ($licenses, $package) use ($whitelistedLicenses, $commercialPackages) {
            // Get all packages that have any licenses that are not whitelisted
            return ! empty(array_diff($licenses, $whitelistedLicenses))
                && ! in_array($package, $commercialPackages);
        });

        if ($this->blacklistedPackages->count() > 0) {
            $this->markFailed();
        }
    }

    /**
     * @return string
     */
    public function formatBlacklistedPackages()
    {
        return $this->allPackages->intersectByKeys($this->blacklistedPackages)
            ->map(function ($licenses, $package) {
                return '['.$package.': '.implode(', ', $licenses).']';
            })->join(', ', ' and ');
    }
}
