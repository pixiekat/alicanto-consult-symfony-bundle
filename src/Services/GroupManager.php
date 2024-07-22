<?php
namespace Pixiekat\AlicantoConsult\Services;
use Doctrine\ORM\EntityManagerInterface;
use Pixiekat\AlicantoConsult\Entity;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class GroupManager {

  const CACHE_TTL = 3600;
  const CACHE_BETA = 1.0;

  /**
   * The current group.
   *
   * @var Entity\Group $group
   */
  protected Entity\Group $group;

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
  public function add(): ?Entity\Group {
    if (!$group = $this->getGroup()) {
      throw new \Exception('No group set');
    }

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
   * Get all groups.
   */
  public function getAll(string $sort = 'name', string $direction = 'asc'): array {

    $groups = $this->cache->get('groups__all', function (ItemInterface $item) use ($sort, $direction): ?array {
      $this->logger->debug('groups__all cache miss: refreshing');
      $expiresAt = (new \DateTime())->setTimeZone(new \DateTimeZone('America/New_York'))->setTimestamp(strtotime('+1 hour'));
      $item->tag(['group']);

      $groups = [];
      try {
        $groups = $this->entityManager->getRepository(Entity\Group::class)->findBy([], [$sort => $direction]);
      }
      catch (\Exception $exception) {
        $item->expiresAt((new \DateTime())->setTimeZone(new \DateTimeZone('America/New_York'))->setTimestamp(strtotime('now')));
      }
      return $groups;
    }, self::CACHE_BETA);

    return $groups;
  }

  /**
   * Gets the current group.
   */
  public function getGroup(): Entity\Group {
    return $this->group;
  }

  /**
   * Sets the current group.
   */
  public function setGroup(Entity\Group $group): static {
    $this->group = $group;

    return $this;
  }
  /**
   * Update a group.
   */
  public function update(): ?Entity\Group {
    if (!$group = $this->getGroup()) {
      throw new \Exception('No group set');
    }
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
  public function delete(): bool {
    if (!$group = $this->getGroup()) {
      throw new \Exception('No group set');
    }

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