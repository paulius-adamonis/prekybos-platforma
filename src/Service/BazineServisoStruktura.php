<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class BazineServisoStruktura
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->getRepository();
    }

    /**
     * @param string|null $entryClass
     * @return ObjectRepository
     */
    protected function getRepository($entryClass = null)
    {
        if ($entryClass != null) {
            return $this->entityManager->getRepository($entryClass);
        }
        return $this->entityManager->getRepository($this->getEntityClass());
    }

    /**
     * @param object $entity
     */
    protected function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    /**
     * void
     */
    protected function flush()
    {
        $this->entityManager->flush();
    }

    /**
     * @param null|object $objectName
     */
    protected function clear($objectName = null)
    {
        $this->entityManager->clear($objectName);
    }

    /**
     * @return string
     */
    abstract public function getEntityClass();
}