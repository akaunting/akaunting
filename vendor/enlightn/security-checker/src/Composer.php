<?php

namespace Enlightn\SecurityChecker;

use RuntimeException;

class Composer
{
    /**
     * @param string $composerLockPath
     * @param false $excludeDev
     * @return array
     */
    public function getDependencies($composerLockPath, $excludeDev = false)
    {
        if (! is_file($composerLockPath)) {
            throw new RuntimeException("File not found at [$composerLockPath]");
        }

        if (! ($lockFileContent = file_get_contents($composerLockPath))) {
            throw new RuntimeException("Unable to read file");
        }

        $json = json_decode($lockFileContent, true);

        if (is_null($json) || ! isset($json['packages'])) {
            throw new RuntimeException("Invalid composer file format");
        }

        if ($excludeDev) {
            $packages = $json['packages'];
        } else {
            $packages = array_merge($json['packages'], isset($json['packages-dev']) ? $json['packages-dev'] : []);
        }

        if (empty($packages)) {
            return [];
        }

        return array_merge(...array_map(function ($package) {
            return [$package['name'] => [
                'version' => ltrim($package['version'], 'v'),
                'time' => isset($package['time']) ? $package['time'] : null,
            ]];
        }, $packages));
    }
}
