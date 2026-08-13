<?php

namespace App\Traits;

trait Firewall
{
    /**
     * How many times an input is decoded. Guards against layered encoding,
     * i.e. &amp;#106; needing two passes, while keeping the work bounded.
     */
    public int $firewall_decode_rounds = 3;

    /**
     * Normalize a request value the way a browser would, before it is matched
     * against the firewall patterns.
     *
     * The patterns look for literal strings such as "javascript:", so a payload
     * can slip past them by being encoded, i.e. java&#115;cript: or java%73cript:,
     * or by being split with characters that browsers drop from URLs, i.e. tab,
     * newline and NUL. Decoding here keeps every pattern plain and readable.
     */
    public function normalizeInput(string $value): string
    {
        $rounds = 0;

        do {
            $previous = $value;

            $value = $this->decodeInput($value);
        } while (($value !== $previous) && (++$rounds < $this->firewall_decode_rounds));

        return $this->stripInvisibleCharacters($value);
    }

    public function decodeInput(string $value): string
    {
        // Named, decimal and hex HTML entities, i.e. &lt; &#106; &#x6A;
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Entities browsers still accept without the closing semicolon, and the
        // ones an outer layer has just uncovered, i.e. &#106 and &#x6A
        $value = $this->decodeCallback('/&#(\d{1,7});?/', $value, fn ($match) => (int) $match[1]);
        $value = $this->decodeCallback('/&#x([0-9a-f]{1,6});?/i', $value, fn ($match) => hexdec($match[1]));

        // Javascript escapes, i.e. j \u{6a} \x6a
        $value = $this->decodeCallback('/\\\\u\{?([0-9a-f]{1,6})\}?/i', $value, fn ($match) => hexdec($match[1]));
        $value = $this->decodeCallback('/\\\\x([0-9a-f]{2})/i', $value, fn ($match) => hexdec($match[1]));

        // Percent encoding, i.e. %6A
        $value = $this->decodeCallback('/%([0-9a-f]{2})/i', $value, fn ($match) => hexdec($match[1]));

        return $value;
    }

    public function decodeCallback(string $pattern, string $value, callable $codepoint): string
    {
        $replaced = preg_replace_callback($pattern, function ($match) use ($codepoint) {
            $code = $codepoint($match);

            if (($code < 1) || ($code > 0x10FFFF)) {
                return $match[0];
            }

            return mb_chr($code, 'UTF-8') ?: $match[0];
        }, $value);

        return $replaced ?? $value;
    }

    /**
     * Drop the characters browsers strip from URLs, i.e. tab, newline, NUL and
     * the rest of the control range, plus zero width characters, so they cannot
     * be used to split a payload.
     *
     * The regular space is deliberately kept. Removing it would turn ordinary
     * prose such as "java script:" into a match.
     */
    public function stripInvisibleCharacters(string $value): string
    {
        $value = preg_replace('/[\x00-\x1F\x7F]/', '', $value) ?? $value;

        return preg_replace('/\x{00AD}|[\x{200B}-\x{200F}]|\x{2060}|\x{FEFF}/u', '', $value) ?? $value;
    }
}
