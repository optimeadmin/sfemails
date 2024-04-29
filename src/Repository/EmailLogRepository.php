<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Optime\Util\Doctrine\Query\Filter;
use Symfony\Component\Uid\Uuid;
use function array_map;

/**
 * @author Manuel Aguirre
 */
class EmailLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly EmailAppProvider $appProvider)
    {
        parent::__construct($registry, EmailLog::class);
    }

    public function queryAll(array $filters = null): QueryBuilder
    {
        $query = $this->createQueryBuilder('l')
            ->select('l, t, app')
            ->leftJoin('l.template', 't')
            ->leftJoin('t.app', 'app')
            ->orderBy('l.id', 'DESC');

        Filter::build($query, $filters['logId'] ?? null)->like('l.uuid')->where('id');
        Filter::build($query, $filters['subject'] ?? null)->like('l.subject');
        Filter::build($query, $filters['recipients'] ?? null)->where('l.recipient');
        Filter::build($query, $filters['statuses'] ?? null)->where('l.status');
        Filter::build($query, $filters['configs'] ?? null)->join('t.config', 'config')->where('config.uuid');

        $apps = (array)($filters['apps'] ?? []);

        if (count($apps) > 0) {
            $resolvedApps = array_map(fn($index) => $this->appProvider->getByIndex($index), $apps);
            Filter::build($query, $resolvedApps)->where('app');
        }

//            if (($filters['send_at'] ?? null) instanceof DateTimeInterface) {
//                $query
//                    ->andWhere('l.dates.createdAt BETWEEN :date_start AND :date_end')
//                    ->setParameter('date_start', $filters['send_at']->format('Y-m-d 00:00:00'))
//                    ->setParameter('date_end', $filters['send_at']->format('Y-m-d 23:59:59'));
//            }

        return $query;
    }

    public function byUuid(Uuid $uuid): ?EmailLog
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }
}