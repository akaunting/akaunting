<?php

declare(strict_types=1);

namespace ZipStream\Test;

class FaultInjectionResource
{
    public const NAME = 'zipstream-php-test-broken-resource';

    /** @var resource */
    public $context;

    private array $injectFaults;

    private string $mode;

    /**
     * @return resource
     */
    public static function getResource(array $injectFaults)
    {
        self::register();

        return fopen(self::NAME . '://foobar', 'rw+', false, self::createStreamContext($injectFaults));
    }

    public function stream_open(string $path, string $mode, int $options, string &$opened_path = null): bool
    {
        $options = stream_context_get_options($this->context);

        if (!isset($options[self::NAME]['injectFaults'])) {
            return false;
        }

        $this->mode = $mode;
        $this->injectFaults = $options[self::NAME]['injectFaults'];

        if ($this->shouldFail(__FUNCTION__)) {
            return false;
        }

        return true;
    }

    public function stream_write(string $data)
    {
        if ($this->shouldFail(__FUNCTION__)) {
            return false;
        }
        return true;
    }

    public function stream_eof()
    {
        return true;
    }

    public function stream_seek(int $offset, int $whence): bool
    {
        if ($this->shouldFail(__FUNCTION__)) {
            return false;
        }

        return true;
    }

    public function stream_tell(): int
    {
        if ($this->shouldFail(__FUNCTION__)) {
            return false;
        }

        return 0;
    }

    public static function register(): void
    {
        if (!in_array(self::NAME, stream_get_wrappers(), true)) {
            stream_wrapper_register(self::NAME, __CLASS__);
        }
    }

    public function stream_stat(): array
    {
        static $modeMap = [
            'r'  => 33060,
            'rb' => 33060,
            'r+' => 33206,
            'w'  => 33188,
            'wb' => 33188,
        ];

        return [
            'dev'     => 0,
            'ino'     => 0,
            'mode'    => $modeMap[$this->mode],
            'nlink'   => 0,
            'uid'     => 0,
            'gid'     => 0,
            'rdev'    => 0,
            'size'    => 0,
            'atime'   => 0,
            'mtime'   => 0,
            'ctime'   => 0,
            'blksize' => 0,
            'blocks'  => 0,
        ];
    }

    public function url_stat(string $path, int $flags): array
    {
        return [
            'dev'     => 0,
            'ino'     => 0,
            'mode'    => 0,
            'nlink'   => 0,
            'uid'     => 0,
            'gid'     => 0,
            'rdev'    => 0,
            'size'    => 0,
            'atime'   => 0,
            'mtime'   => 0,
            'ctime'   => 0,
            'blksize' => 0,
            'blocks'  => 0,
        ];
    }

    private static function createStreamContext(array $injectFaults)
    {
        return stream_context_create([
            self::NAME => ['injectFaults' => $injectFaults],
        ]);
    }

    private function shouldFail(string $function): bool
    {
        return in_array($function, $this->injectFaults, true);
    }
}
