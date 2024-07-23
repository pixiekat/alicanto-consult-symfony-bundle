<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Entity;

use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Repository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: Repository\UserProfileRepository::class)]
#[ORM\Table(name: "consult_user_profile")]
class UserProfile {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: "integer")]
  private $id;

  #[ORM\OneToOne(inversedBy: 'userProfile', cascade: ['persist', 'remove'])]
  #[ORM\JoinColumn(nullable: false)]
  private ?Entity\User $user = null;

  #[ORM\Column(type: "string", length: 255)]
  #[Assert\NotBlank(message: "user.first_name.not_blank")]
  private $firstName;

  #[ORM\Column(type: "string", length: 255)]
  #[Assert\NotBlank(message: "user.last_name.not_blank")]
  private $lastName;

  public function getId(): ?int {
    return $this->id;
  }

  public function getUser(): ?Entity\User {
    return $this->user;
  }

  public function getDisplayName(): ?string {
    return $this->firstName . ' ' . $this->lastName;
  }

  public function getFirstName(): ?string {
    return $this->firstName;
  }

  public function getLastName(): ?string {
    return $this->lastName;
  }

  public function setUser(Entity\User $user): self {
    $this->user = $user;
    return $this;
  }

  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;
    return $this;
  }

  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;
    return $this;
  }
}