<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Optime\Email\Bundle\Repository\EmailAppRepository;
use Optime\Util\Entity\Traits\DatesTrait;

#[ORM\Table('emails_bundle_email_app')]
#[ORM\Entity(repositoryClass: EmailAppRepository::class)]
#[ORM\MappedSuperclass]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "inheritance_discriminator", type: "string")]
#[ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")]
class EmailApp
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

    public function __toString(): string
    {
        return $this->getId();
    }
}