<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Util\Entity\Traits\DatesTrait;

#[ORM\Table('emails_bundle_email_owner')]
#[ORM\Entity]
#[ORM\MappedSuperclass]
class EmailOwner
{
    use DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}