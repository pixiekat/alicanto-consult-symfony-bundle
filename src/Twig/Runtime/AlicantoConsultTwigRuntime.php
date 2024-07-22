<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Twig\Runtime;
use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Services;
use Twig\Extension\RuntimeExtensionInterface;

class AlicantoConsultTwigRuntime implements RuntimeExtensionInterface {
  
  /**
   * The constructor for this runtime.
   */
  public function __construct(
    private Services\GroupManager $groupManager,
  ) { }

  public function getGroupManager() {
    return $this->groupManager;
  }
}
