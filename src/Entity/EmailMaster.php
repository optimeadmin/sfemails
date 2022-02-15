<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('emails_bundle_email_master')]
#[ORM\Entity]
#[ORM\UniqueConstraint('email_master_description', ['description'])]
class EmailMaster
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\Column]
    private string $code;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private string $layout;

    #[ORM\Column]
    private bool $editable;

    #[ORM\Column]
    private string $target;
}