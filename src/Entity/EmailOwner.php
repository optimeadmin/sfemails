<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('emails_bundle_email_owner')]
#[ORM\Entity]
#[ORM\MappedSuperclass]
class EmailOwner
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}