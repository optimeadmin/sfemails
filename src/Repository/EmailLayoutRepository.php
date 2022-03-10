<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailLayout;

/**
 * @author Manuel Aguirre
 */
class EmailLayoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailLayout::class);
    }

    public function save(EmailLayout $layout):void
    {
        $this->getEntityManager()->persist($layout);
        $this->getEntityManager()->flush();
    }
}