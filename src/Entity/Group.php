<?php
declare(strict_types=1);
namespace Pixiekat\AlicantoConsult\Entity;

use Pixiekat\AlicantoConsult\Entity;
use Pixiekat\AlicantoConsult\Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: Repository\GroupRepository::class)]
#[ORM\Table(name: "consult_groups")]
class Group {
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: "integer")]
  private $id;

  #[ORM\Column(type: "string", length: 255)]
  #[Assert\NotBlank(message: "group.name.not_blank")]
  private $name;

  #[ORM\Column(type: "string", length: 255, nullable: true)]
  private $description;

  #[ORM\Column(type: "string", length: 255, nullable: true)]
  #[Assert\Email(message: "group.group_email.email")]
  private $groupEmail;

  #[Assert\Type(type: Entity\GroupPreferences::class)]
  #[Assert\Valid]
  #[ORM\OneToOne(mappedBy: 'group', cascade: ['persist', 'remove'])]
  private ?Entity\GroupPreferences $groupPreferences = null;

  #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groupMemberships')]
  #[ORM\JoinTable(name: "consult_group_members")]
  #[ORM\JoinColumn(name: "group_id", referencedColumnName: "id")]
  #[ORM\InverseJoinColumn(name: "user_id", referencedColumnName: "id")]
  private Collection $groupMembers;

  public function __construct() {
    $this->groupMembers = new ArrayCollection();
  }

  public function addGroupMember(User $groupMember): static {
    if (!$this->groupMembers->contains($groupMember)) {
      $this->groupMembers->add($groupMember);
    }

    return $this;
  }

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

  public function getGroupMembers(): Collection {
    return $this->groupMembers;
  }

  public function getGroupPreferences(): ?Entity\GroupPreferences {
    return $this->groupPreferences;
  }

  public function removeGroupMember(User $groupMember): static {
    $this->groupMembers->removeElement($groupMember);

    return $this;
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

  public function setGroupPreferences(?Entity\GroupPreferences $groupPreferences): self {
    // set the owning side of the relation if necessary
    if ($groupPreferences->getGroup() !== $this) {
      $groupPreferences->setGroup($this);
    }

    $this->groupPreferences = $groupPreferences;

    return $this;
  }
}