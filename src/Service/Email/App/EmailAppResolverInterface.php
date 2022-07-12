<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Email\App;

use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Entity\EmailMaster;

/**
 * @author Manuel Aguirre
 */
interface EmailAppResolverInterface
{
    public function resolve(EmailMaster $emailMaster): ?EmailAppInterface;
}