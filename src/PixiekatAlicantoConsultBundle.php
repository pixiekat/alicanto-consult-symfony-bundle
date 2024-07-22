<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsultBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Pixiekat\AlicantoConsultBundle\DependencyInjection\AlicantoConsultBundleExtension;

class PixiekatAlicantoConsultBundle extends AbstractBundle {
  public function getPath(): string {
      return \dirname(__DIR__);
  }

  public function getContainerExtension(): ?ExtensionInterface {
    return new AlicantoConsultBundleExtension();
  }
}