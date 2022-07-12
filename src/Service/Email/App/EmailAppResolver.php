<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Email\App;

use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Entity\EmailMaster;
use function call_user_func;

/**
 * @author Manuel Aguirre
 */
class EmailAppResolver implements EmailAppResolverInterface
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function resolve(EmailMaster $emailMaster): ?EmailAppInterface
    {
        return call_user_func($this->callback, $emailMaster);
    }
}