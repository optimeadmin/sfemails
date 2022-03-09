<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use function Symfony\Component\String\u;

/**
 * @author Manuel Aguirre
 */
enum EmailLogStatus: string
{
    case pending = 'pending';
    case send = 'send';
    case error = 'error';
    case no_template = 'no_template';

    public function toString(): string
    {
        return u($this->value)->replace('_', ' ')->title();
    }

    public function isSend(): bool
    {
        return $this == self::send;
    }
}