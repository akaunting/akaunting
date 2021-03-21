<?php

namespace Composer\CustomDirectoryInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class LibraryPlugin implements PluginInterface
{
  private $installer;

  public function activate (Composer $composer, IOInterface $io)
  {
    $this->installer = new LibraryInstaller($io, $composer);
    $composer->getInstallationManager()->addInstaller($this->installer);
  }

  public function deactivate(Composer $composer, IOInterface $io)
  {
    $composer->getInstallationManager()->removeInstaller($this->installer);
  }

  public function uninstall(Composer $composer, IOInterface $io)
  {
  }
}
