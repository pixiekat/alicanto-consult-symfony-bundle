<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Twig\Extension;

use Pixiekat\AlicantoConsult\Twig\Runtime\AlicantoConsultTwigRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AlicantoConsultTwigExtension extends AbstractExtension {
  public function getFilters(): array {
    return [
      //new TwigFilter('get_promotion_manager', [PromotionExtensionRuntime::class, 'getPromotionManager']),
    ];
  }

  public function getFunctions(): array {
    return [
      new TwigFunction('get_group_manager', [AlicantoConsultTwigRuntime::class, 'getGroupManager']),
    ];
  }
}
