<?php
namespace Pixiekat\AlicantoConsult\Services;
use Doctrine\ORM\EntityManagerInterface;
use Pixiekat\AlicantoConsult\Entity;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserManager {

  const CACHE_TTL = 3600;
  const CACHE_BETA = 1.0;

  /**
   * The current user.
   *
   * @var Entity\User $group
   */
  protected Entity\User $group;

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
   * Add a user.
   */
  public function add(): ?Entity\User {
    if (!$user = $this->getUser()) {
      throw new \Exception('No user set');
    }

    try {
      $this->entityManager->persist($user);
      $this->entityManager->flush();
      $this->cache->invalidateTags(['user']);
      $this->logger->info('User added successfully', ['user' => $user->getId()]);
      return $user;
    } catch (\Exception $e) {
      $this->logger->error('Failed to add user: ' . $e->getMessage());
    }
    return false;
  }

  /**
   * Get all users.
   */
  public function getAll(string $sort = 'id', string $direction = 'asc'): array {

    $users = $this->cache->get('user__all', function (ItemInterface $item) use ($sort, $direction): ?array {
      $this->logger->debug('user__all cache miss: refreshing');
      $expiresAt = (new \DateTime())->setTimeZone(new \DateTimeZone('America/New_York'))->setTimestamp(strtotime('+1 hour'));
      $item->tag(['user']);

      $users = [];
      try {
        $users = $this->entityManager->getRepository(Entity\User::class)->findBy([], [$sort => $direction]);
      }
      catch (\Exception $exception) {
        $item->expiresAt((new \DateTime())->setTimeZone(new \DateTimeZone('America/New_York'))->setTimestamp(strtotime('now')));
      }
      return $users;
    }, self::CACHE_BETA);

    return $users;
  }

  /**
   * Gets the users display name.
   */
  public function getDisplayName(): string {
    if (!$user = $this->getUser()) {
      throw new \Exception('No user set');
    }

    return $user->getUserProfile()->getDisplayName() ?? '';
  }

  /**
   * Gets the current user.
   */
  public function getUser(): Entity\User {
    return $this->user;
  }

  /**
   * Sets the current user.
   */
  public function setUser(Entity\User $user): static {
    $this->user = $user;

    return $this;
  }

  /**
   * Checks if this user is active or not.
   * 
   * @return bool
   */
  public function isActive(): bool {
    if (!$user = $this->getUser()) {
      throw new \Exception('No user set');
    }

    return $user->isActive() ?? false;
  }

  /**
   * Update a user.
   */
  public function update(): ?Entity\User {
    if (!$user = $this->getUser()) {
      throw new \Exception('No user set');
    }
    try {
      d($user);
      $this->entityManager->persist($user);
      //$this->entityManager->flush();
      $this->cache->invalidateTags(['user']);
      $this->logger->info('User updated successfully', ['user' => $user->getId()]);
      return $user;
    } catch (\Exception $e) {
      dump($e->getMessage());
      $this->logger->error('Failed to update user: ' . $e->getMessage());
    }
    return false;
  }

  /**
   * Delete a user.
   */
  public function delete(): bool {
    if (!$user = $this->getUser()) {
      throw new \Exception('No user set');
    }

    try {
      $this->entityManager->remove($user);
      $this->entityManager->flush();
      $this->cache->invalidateTags(['user']);
      $this->logger->info('User deleted successfully', ['user' => $user->getId()]);
      return true;
    } catch (\Exception $e) {
      $this->logger->error('Failed to delete user: ' . $e->getMessage());
    }
    return false;
  }
}