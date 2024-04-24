<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Exception\ConfigNotFoundException;
use Symfony\Component\Uid\Uuid;

/**
 * @author Manuel Aguirre
 */
class EmailMasterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailMaster::class);
    }

    public function byCode(string $code): ?EmailMaster
    {
        return $this->findOneBy([
            'code' => $code,
        ]);
    }

    public function byUuid(Uuid $uuid): EmailMaster
    {
        $config = $this->findOneBy(['uuid' => $uuid]);

        if (!$config) {
            throw new ConfigNotFoundException("No existe una config con uuid => '{$uuid}'");
        }

        return $config;
    }
}