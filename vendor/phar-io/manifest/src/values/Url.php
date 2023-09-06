<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

class Url {
    /** @var string */
    private $url;

    public function __construct(string $url) {
        $this->ensureUrlIsValid($url);

        $this->url = $url;
    }

    public function asString(): string {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @throws InvalidUrlException
     */
    private function ensureUrlIsValid($url): void {
        if (\filter_var($url, \FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrlException;
        }
    }
}
