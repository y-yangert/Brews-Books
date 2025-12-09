<?php

namespace App\EventSubscriber;

use App\Entity\ActivityLog;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

#[AsDoctrineListener(event: Events::postPersist)]
#[AsDoctrineListener(event: Events::postUpdate)]
final class ActivityLogSubscriber
{
	public function __construct(private readonly EntityManagerInterface $entityManager)
	{
	}

	public function postPersist(PostPersistEventArgs $args): void
	{
		$this->logChange($args->getObject(), 'created');
	}

	public function postUpdate(PostUpdateEventArgs $args): void
	{
		$this->logChange($args->getObject(), 'updated');
	}

	private function logChange(object $entity, string $action): void
	{
		if ($entity instanceof ActivityLog) {
			return; // avoid logging logs
		}

		$class = $entity::class;
		if (!str_starts_with($class, 'App\\Entity\\')) {
			return;
		}

		if (!method_exists($entity, 'getId')) {
			return;
		}
		$entityId = $entity->getId();
		if ($entityId === null) {
			return;
		}

		$log = (new ActivityLog())
			->setEntityName((new \ReflectionClass($entity))->getShortName())
			->setEntityId((int) $entityId)
			->setAction($action)
			->setOccurredAt(new \DateTimeImmutable('now'));

		$this->entityManager->persist($log);
		$this->entityManager->flush();
	}
}