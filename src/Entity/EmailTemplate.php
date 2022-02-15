<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Translatable;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;

#[ORM\Table('emails_bundle_email_template')]
#[ORM\Entity]
class EmailTemplate
{
    use ExternalUuidTrait, DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_owner_id', nullable: false)]
    private EmailOwner $owner;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_master_id', nullable: false)]
    private EmailOwner $config;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    private string $subject;

    #[ORM\Column(type: 'text')]
    #[Translatable]
    private string $content;

    #[ORM\Column]
    private bool $active;
}