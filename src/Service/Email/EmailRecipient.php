<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

/**
 * @author Manuel Aguirre
 */
class EmailRecipient implements EmailRecipientInterface
{
    public function __construct(
        private string $email,
        private string $name,
        private ?string $id = null,
    ) {
        if (null === $this->id) {
            $this->id = $this->email;
        }
    }

    public static function fromEmail(string $email): self
    {
        return new self(
            $email,
            $email,
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRecipientId(): string
    {
        return $this->id;
    }
}