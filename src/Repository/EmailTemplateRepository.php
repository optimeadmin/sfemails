<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailTemplate;

/**
 * @author Manuel Aguirre
 */
class EmailTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailTemplate::class);
    }
}