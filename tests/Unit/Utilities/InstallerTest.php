<?php

namespace Tests\Unit\Utilities;

use App\Utilities\Installer;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class InstallerTest extends TestCase
{
    /**
     * @dataProvider plainValues
     */
    public function testItWritesPlainValuesUnquoted(string $value): void
    {
        $this->assertSame($value, Installer::formatEnvValue($value));
        $this->assertSame($value, $this->parseMailFromName($value));
    }

    /**
     * @dataProvider unsafeValues
     */
    public function testItKeepsUnsafeValuesOnTheirOwnLine(string $value, string $expected): void
    {
        $this->assertSame($expected, $this->parseMailFromName($value));
    }

    public function testItWritesEmptyValues(): void
    {
        $this->assertSame('', Installer::formatEnvValue(null));
        $this->assertSame('', Installer::formatEnvValue(''));
    }

    public static function plainValues(): array
    {
        return [
            'app key' => ['base64:Zm9vYmFy+/9z='],
            'url' => ['https://example.com:8080/~akaunting'],
            'prefix' => ['abc_'],
            'time' => ['09:00'],
            'locale' => ['en-GB'],
            'email' => ['info@example.com'],
            'boolean' => ['true'],
        ];
    }

    public static function unsafeValues(): array
    {
        return [
            'line breaks' => ["Test\"\nAPP_INSTALLED=false\r\nx=\"", 'Test" APP_INSTALLED=false x="'],
            'double quotes' => ['Acme "North" Ltd', 'Acme "North" Ltd'],
            'variable expansion' => ['${APP_KEY} $APP_KEY', '${APP_KEY} $APP_KEY'],
            'backslashes' => ['C:\\path\\', 'C:\\path\\'],
            'comment' => ['p#ss word', 'p#ss word'],
            'unicode' => ['Şirket Ltd', 'Şirket Ltd'],
        ];
    }

    /**
     * Parse a .env written with the value, failing if it adds or changes any other key.
     */
    protected function parseMailFromName(string $value): string
    {
        $env = Dotenv::parse("APP_KEY=base64:real\nAPP_INSTALLED=true\nMAIL_FROM_NAME=" . Installer::formatEnvValue($value) . "\n");

        $this->assertSame(['APP_KEY', 'APP_INSTALLED', 'MAIL_FROM_NAME'], array_keys($env));
        $this->assertSame('base64:real', $env['APP_KEY']);
        $this->assertSame('true', $env['APP_INSTALLED']);

        return $env['MAIL_FROM_NAME'];
    }
}
