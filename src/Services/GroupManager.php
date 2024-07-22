<?php
namespace Pixiekat\AlicantoConsult\Services;
use Doctrine\ORM\EntityManagerInterface;
use Pixiekat\AlicantoConsult\Entity;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class GroupManager {

  /**
   * The constructor.
   */
  public function __construct(
    protected EntityManagerInterface $entityManager,
    protected TagAwareCacheInterface $cache,
    protected RequestStack $requestStack,
    protected Logger $logger,
  ) { }

  /**
   * Add a group.
   */
  public function add(Entity\Group $group): ?Entity\Group {
    try {
      $this->entityManager->persist($group);
      $this->entityManager->flush();
      $this->cache->invalidateTags(['group']);
      $this->logger->info('Group added successfully', ['group' => $group->getId(), 'name' => $group->getName()]);
      return $group;
    } catch (\Exception $e) {
      $this->logger->error('Failed to add group: ' . $e->getMessage());
    }
    return false;
  }

  /**
   * Update a group.
   */
  public function update(Entity\Group $group): ?Entity\Group {
    try {
      $this->entityManager->persist($group);
      $this->entityManager->flush();
      $this->cache->invalidateTags(['group']);
      $this->logger->info('Group updated successfully', ['group' => $group->getId(), 'name' => $group->getName()]);
      return $group;
    } catch (\Exception $e) {
      $this->logger->error('Failed to update group: ' . $e->getMessage());
    }
    return false;
  }

  /**
   * Delete a group.
   */
  public function delete(Entity\Group $group): bool {
    try {
      $this->entityManager->remove($group);
      $this->entityManager->flush();
      $this->cache->invalidateTags(['group']);
      $this->logger->info('Group deleted successfully', ['group' => $group->getId(), 'name' => $group->getName()]);
      return true;
    } catch (\Exception $e) {
      $this->logger->error('Failed to delete group: ' . $e->getMessage());
    }
    return false;
  }
}