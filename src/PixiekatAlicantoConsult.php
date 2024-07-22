<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Pixiekat\AlicantoConsult\DependencyInjection\AlicantoConsultExtension;

class PixiekatAlicantoConsult extends AbstractBundle {
  public function getPath(): string {
      return \dirname(__DIR__);
  }

  public function getContainerExtension(): ?ExtensionInterface {
    return new AlicantoConsultExtension();
  }
}