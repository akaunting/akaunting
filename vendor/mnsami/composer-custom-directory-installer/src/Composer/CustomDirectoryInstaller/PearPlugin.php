<?php

namespace Composer\CustomDirectoryInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class PearPlugin implements PluginInterface
{
  public function activate (Composer $composer, IOInterface $io)
  {
    if (!class_exists('Composer\Composer\Installer\PearInstaller')) {
      return;
    }
    $installer = new PearInstaller($io, $composer);
    $composer->getInstallationManager()->addInstaller($installer);
  }

  public function deactivate(Composer $composer, IOInterface $io)
  {
  }

  public function uninstall(Composer $composer, IOInterface $io)
  {
  }
}
