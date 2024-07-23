<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Entity;

use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: Repository\UserRepository::class)]
#[ORM\Table(name: "consult_users")]
class User {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: "integer")]
  private $id;

  #[ORM\Column(type: "string", length: 255)]
  #[Assert\Email(message: "user.email_address.email")]
  #[Assert\NotBlank(message: "user.email_address.not_blank")]
  private $emailAddress;

  #[ORM\Column]
  private array $roles = [];

  #[ORM\Column(type: "boolean", options: ["default" => true])]
  private $isActive = true;

  #[Assert\Type(type: Entity\UserProfile::class)]
  #[Assert\Valid]
  #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
  private ?Entity\UserProfile $userProfile = null;

  #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'groupMembers')]
  private Collection $groupMemberships;

  public function __construct() {
    $this->groupMemberships = new ArrayCollection();
  }

  public function addGroupMembership(Group $groupMembership): static {
    if (!$this->groupMemberships->contains($groupMembership)) {
      $this->groupMemberships->add($groupMembership);
      $groupMembership->addGroupMember($this);
    }

    return $this;
  }

  public function addUserRole(UserRoles $userRole): static {
    if (!$this->userRoles->contains($userRole)) {
      $this->userRoles->add($userRole);
    }

    return $this;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getEmailAddress(): ?string {
    return $this->emailAddress;
  }

  public function getGroupMemberships(): Collection {
    return $this->groupMemberships;
  }

  public function getUserProfile(): ?Entity\UserProfile {
    return $this->userProfile;
  }

  public function getRoles(): array {
    $roles = $this->roles;
    $roles[] = "ROLE_USER";

    // allow superuser to have all roles
    if ($this->getId() == 1) {
      $roles[] = "ROLE_SYSADMIN";
    }

    return array_unique($roles);
  }

  public function isActive(): bool {
    return $this->isActive;
  }

  public function removeGroupMembership(Group $groupMembership): static {
    if ($this->groupMemberships->removeElement($groupMembership)) {
      $groupMembership->removeGroupMember($this);
    }

    return $this;
  }

  public function removeUserRole(UserRoles $userRole): static {
    $this->userRoles->removeElement($userRole);

    return $this;
  }

  public function setEmailAddress(string $emailAddress): self {
    $this->emailAddress = $emailAddress;
    return $this;
  }

  public function setIsActive(bool $isActive): self {
    $this->isActive = $isActive;
    return $this;
  }

  public function setRoles(array $roles): static {
    //$allowedRoles = [self::ROLE_CANDIDATE, self::ROLE_REFEREE, self::ROLE_360_EVALUATOR, self::ROLE_DIVISION_CHIEF, self::ROLE_TEST_USER, self::ROLE_LEAD_ADMIN, self::ROLE_ADMIN, self::ROLE_DECISION_ADMIN, self::ROLE_SYSADMIN];
    //$setRoles = [];
    //foreach ($roles as $role) {
    //  if (in_array($role, $allowedRoles)) {
    //    $setRoles[] = $role;
    //  }
    //}
    $this->roles = $roles;

    return $this;
  }

  public function setUserProfile(?Entity\UserProfile $userProfile): self {
    // set the owning side of the relation if necessary
    if ($userProfile->getUser() !== $this) {
      $userProfile->setUser($this);
    }

    $this->userProfile = $userProfile;

    return $this;
  }
}