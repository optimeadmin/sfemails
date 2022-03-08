<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

/**
 * @author Manuel Aguirre
 */
interface EmailRecipientInterface
{
    public function getEmail(): string;

    public function getName(): string;

    public function getRecipientId(): string;
}