<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark;

/**
 * Converts CommonMark-compatible Markdown to HTML.
 */
class CommonMarkConverter extends Converter
{
    /**
     * The currently-installed version.
     *
     * This might be a typical `x.y.z` version, or `x.y-dev`.
     *
     * @deprecated in 1.5.0 and will be removed from 2.0.0.
     *   Use \Composer\InstalledVersions provided by composer-runtime-api instead.
     */
    public const VERSION = '1.5.7';

    /** @var EnvironmentInterface */
    protected $environment;

    /**
     * Create a new commonmark converter instance.
     *
     * @param array<string, mixed>      $config
     * @param EnvironmentInterface|null $environment
     */
    public function __construct(array $config = [], EnvironmentInterface $environment = null)
    {
        if ($environment === null) {
            $environment = Environment::createCommonMarkEnvironment();
        }

        if ($environment instanceof ConfigurableEnvironmentInterface) {
            $environment->mergeConfig($config);
        }

        $this->environment = $environment;

        parent::__construct(new DocParser($environment), new HtmlRenderer($environment));
    }

    public function getEnvironment(): EnvironmentInterface
    {
        return $this->environment;
    }
}
