<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Util\Entity\Traits\DatesTrait;
use Optime\Util\Entity\Traits\ExternalUuidTrait;

#[ORM\Table('emails_bundle_email_log')]
#[ORM\Entity]
abstract class EmailLog
{
    use ExternalUuidTrait, DatesTrait;

    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private readonly ?int $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'email_template_id', nullable: false)]
    private EmailTemplate $template;

    #[ORM\Column(type: 'text')]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column]
    private array $recipients;
}