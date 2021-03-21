<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\Autolink;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Element\Text;

final class UrlAutolinkProcessor
{
    // RegEx adapted from https://github.com/symfony/symfony/blob/4.2/src/Symfony/Component/Validator/Constraints/UrlValidator.php
    const REGEX = '~
        (?<=^|[ \\t\\n\\x0b\\x0c\\x0d*_\\~\\(])  # Can only come at the beginning of a line, after whitespace, or certain delimiting characters
        (
            # Must start with a supported scheme + auth, or "www"
            (?:
                (?:%s)://                                 # protocol
                (?:([\.\pL\pN-]+:)?([\.\pL\pN-]+)@)?      # basic auth
            |www\.)
            (?:
                (?:[\pL\pN\pS\-\.])+(?:\.?(?:[\pL\pN]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                    |                                                 # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                    # an IP address
                    |                                                 # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]  # an IPv6 address
            )
            (?::[0-9]+)?                              # a port (optional)
            (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*      # a path
            (?:\? (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a query (optional)
            (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a fragment (optional)
        )~ixu';

    /** @var string */
    private $finalRegex;

    /**
     * @param array<int, string> $allowedProtocols
     */
    public function __construct(array $allowedProtocols = ['http', 'https', 'ftp'])
    {
        $this->finalRegex = \sprintf(self::REGEX, \implode('|', $allowedProtocols));
    }

    /**
     * @param DocumentParsedEvent $e
     *
     * @return void
     */
    public function __invoke(DocumentParsedEvent $e)
    {
        $walker = $e->getDocument()->walker();

        while ($event = $walker->next()) {
            $node = $event->getNode();
            if ($node instanceof Text && !($node->parent() instanceof Link)) {
                self::processAutolinks($node, $this->finalRegex);
            }
        }
    }

    private static function processAutolinks(Text $node, string $regex): void
    {
        $contents = \preg_split($regex, $node->getContent(), -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($contents === false || \count($contents) === 1) {
            return;
        }

        $leftovers = '';
        foreach ($contents as $i => $content) {
            // Even-indexed elements are things before/after the URLs
            if ($i % 2 === 0) {
                // Insert any left-over characters here as well
                $text = $leftovers . $content;
                if ($text !== '') {
                    $node->insertBefore(new Text($leftovers . $content));
                }

                $leftovers = '';
                continue;
            }

            $leftovers = '';

            // Does the URL end with punctuation that should be stripped?
            if (\preg_match('/(.+)([?!.,:*_~]+)$/', $content, $matches)) {
                // Add the punctuation later
                $content = $matches[1];
                $leftovers = $matches[2];
            }

            // Does the URL end with something that looks like an entity reference?
            if (\preg_match('/(.+)(&[A-Za-z0-9]+;)$/', $content, $matches)) {
                $content = $matches[1];
                $leftovers = $matches[2] . $leftovers;
            }

            // Does the URL need its closing paren chopped off?
            if (\substr($content, -1) === ')' && ($diff = self::diffParens($content)) > 0) {
                $content = \substr($content, 0, -$diff);
                $leftovers = str_repeat(')', $diff) . $leftovers;
            }

            self::addLink($node, $content);
        }

        $node->detach();
    }

    private static function addLink(Text $node, string $url): void
    {
        // Auto-prefix 'http://' onto 'www' URLs
        if (\substr($url, 0, 4) === 'www.') {
            $node->insertBefore(new Link('http://' . $url, $url));

            return;
        }

        $node->insertBefore(new Link($url, $url));
    }

    /**
     * @param string $content
     *
     * @return int
     */
    private static function diffParens(string $content): int
    {
        // Scan the entire autolink for the total number of parentheses.
        // If there is a greater number of closing parentheses than opening ones,
        // we don’t consider ANY of the last characters as part of the autolink,
        // in order to facilitate including an autolink inside a parenthesis.
        \preg_match_all('/[()]/', $content, $matches);

        $charCount = ['(' => 0, ')' => 0];
        foreach ($matches[0] as $char) {
            $charCount[$char]++;
        }

        return $charCount[')'] - $charCount['('];
    }
}
