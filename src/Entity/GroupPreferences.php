<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Entity;

use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Repository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: Repository\GroupPreferencesRepository::class)]
#[ORM\Table(name: "consult_group_preferences")]
class GroupPreferences {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: "integer")]
  private $id;

  #[ORM\OneToOne(inversedBy: 'groupPreferences', cascade: ['persist', 'remove'])]
  #[ORM\JoinColumn(nullable: false)]
  private ?Entity\Group $group = null;

  #[ORM\Column(type: "boolean", options: ["default" => false])]
  private $isPrivate = false;

  public function getId(): ?int {
    return $this->id;
  }

  public function getGroup(): ?Entity\Group {
    return $this->group;
  }

  public function isPrivate(): bool {
    return $this->isPrivate;
  }

  public function setGroup(Entity\Group $group): self {
    $this->group = $group;
    return $this;
  }

  public function setIsPrivate(bool $isPrivate): self {
    $this->isPrivate = $isPrivate;
    return $this;
  }
}