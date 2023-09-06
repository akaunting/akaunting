<?php declare(strict_types=1);
/*
 * This file is part of PHPLOC.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\PHPLOC;

final class Arguments
{
    /**
     * @psalm-var list<string>
     */
    private $directories;

    /**
     * @psalm-var list<string>
     */
    private $suffixes;

    /**
     * @psalm-var list<string>
     */
    private $exclude;

    /**
     * @var bool
     */
    private $countTests;

    /**
     * @var ?string
     */
    private $csvLogfile;

    /**
     * @var ?string
     */
    private $jsonLogfile;

    /**
     * @var ?string
     */
    private $xmlLogfile;

    /**
     * @var bool
     */
    private $help;

    /**
     * @var bool
     */
    private $version;

    public function __construct(array $directories, array $suffixes, array $exclude, bool $countTests, ?string $csvLogfile, ?string $jsonLogfile, ?string $xmlLogfile, bool $help, bool $version)
    {
        $this->directories = $directories;
        $this->suffixes    = $suffixes;
        $this->exclude     = $exclude;
        $this->countTests  = $countTests;
        $this->csvLogfile  = $csvLogfile;
        $this->jsonLogfile = $jsonLogfile;
        $this->xmlLogfile  = $xmlLogfile;
        $this->help        = $help;
        $this->version     = $version;
    }

    /**
     * @psalm-return list<string>
     */
    public function directories(): array
    {
        return $this->directories;
    }

    /**
     * @psalm-return list<string>
     */
    public function suffixes(): array
    {
        return $this->suffixes;
    }

    /**
     * @psalm-return list<string>
     */
    public function exclude(): array
    {
        return $this->exclude;
    }

    public function countTests(): bool
    {
        return $this->countTests;
    }

    public function csvLogfile(): ?string
    {
        return $this->csvLogfile;
    }

    public function jsonLogfile(): ?string
    {
        return $this->jsonLogfile;
    }

    public function xmlLogfile(): ?string
    {
        return $this->xmlLogfile;
    }

    public function help(): bool
    {
        return $this->help;
    }

    public function version(): bool
    {
        return $this->version;
    }
}
