<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Repository;

use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Optime\Email\Bundle\Entity\EmailLog;
use function array_map;
use function count;
use function is_string;
use function strlen;
use function trim;

/**
 * @author Manuel Aguirre
 */
class EmailLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailLog::class);
    }

    public function queryAll(array $filters = null): QueryBuilder
    {
        $query = $this->createQueryBuilder('l')
            ->join('l.template', 't')
            ->orderBy('l.id', 'DESC');

        $addFilterIfApply = static function (string $key, string $field = null) use ($query, $filters) {
            $field ??= $key;
            if (0 !== strlen(trim($filters[$key] ?? '')) && is_string($filters[$key])) {
                $query
                    ->andWhere("l.{$field} LIKE :{$field}")
                    ->setParameter($field, '%' . $filters[$key] . '%');
            }
        };

        // Si viene log_id, ignoramos los demás filtros
        if (0 === strlen(trim($filters['log_id'] ?? ''))) {
            if (0 !== count($filters['config'] ?? [])) {
                $query
                    ->andWhere('t.config IN (:config)')
                    ->setParameter('config', $filters['config']);
            }
            if (0 !== count($filters['status'] ?? [])) {
                $query
                    ->andWhere('l.status IN (:status)')
                    ->setParameter('status', array_map(fn($v) => $v->value, $filters['status']));
            }
            if (0 !== count($filters['app'] ?? [])) {
                $query
                    ->andWhere('t.app IN (:apps)')
                    ->setParameter('apps', $filters['app']);
            }
            if (($filters['send_at'] ?? null) instanceof DateTimeInterface) {
                $query
                    ->andWhere('l.dates.createdAt BETWEEN :date_start AND :date_end')
                    ->setParameter('date_start', $filters['send_at']->format('Y-m-d 00:00:00'))
                    ->setParameter('date_end', $filters['send_at']->format('Y-m-d 23:59:59'));
            }

            $addFilterIfApply('recipient');
            $addFilterIfApply('subject');
        } else {
            $addFilterIfApply('log_id', 'uuid');
        }

        return $query;
    }
}