<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table('emails_bundle_email_template')]
#[ORM\Entity]
class EmailTemplate
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_owner_id', nullable: false)]
    private EmailOwner $owner;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_master_id', nullable: false)]
    private EmailOwner $config;

    #[ORM\Column(type: 'text')]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column]
    private bool $active;
}