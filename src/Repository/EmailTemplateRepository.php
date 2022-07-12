<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Entity\EmailMaster;
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

    public function byConfigAndApp(EmailMaster $config, EmailAppInterface $app): ?EmailTemplate
    {
        return $this->findOneBy([
            'config' => $config,
            'app' => $app,
            'active' => true,
        ]);
    }
}