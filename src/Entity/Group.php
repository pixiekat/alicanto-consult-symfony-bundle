<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Entity;

use Pixiekat\AlicantoConsult\Repository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Repository\GroupRepository::class)]
#[ORM\Table(name: "consult_groups")]
class Group {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: "integer")]
  private $id;

  #[ORM\Column(type: "string", length: 255)]
  private $name;

  #[ORM\Column(type: "string", length: 255, nullable: true)]
  private $description;

  #[ORM\Column(type: "string", length: 255, nullable: true)]
  private $groupEmail;

  public function getId(): ?int {
    return $this->id;
  }

  public function getName(): ?string {
    return $this->name;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function getGroupEmail(): ?string {
    return $this->groupEmail;
  }

  public function setName(string $name): self {
    $this->name = $name;
    return $this;
  }

  public function setDescription(?string $description): self {
    $this->description = $description;
    return $this;
  }

  public function setGroupEmail(?string $groupEmail): self {
    $this->groupEmail = $groupEmail;
    return $this;
  }
}