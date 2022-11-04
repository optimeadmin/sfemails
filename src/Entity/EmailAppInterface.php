<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Entity;

/**
 * @author Manuel Aguirre
 */
interface EmailAppInterface
{
    public function __toString(): string;

    public function getFromEmail(): string;

    public function getFromName(): string;
}