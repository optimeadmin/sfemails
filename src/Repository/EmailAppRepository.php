<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailApp;

/**
 * @author Manuel Aguirre
 */
class EmailAppRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailApp::class);
    }

    public function findDefaultIfApply(): ?EmailApp
    {
        $apps = $this->findAll();

        if (1 === $this->count($apps)) {
            return $apps[0];
        }

        return null;
    }
}