<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;


use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Entity\EmailTemplate;

/**
 * @author Manuel Aguirre
 */
class TemplateData
{
    public function __construct(
        private readonly string $emailCode,
        private readonly ?EmailMaster $config,
        private readonly ?EmailTemplate $template,
        private readonly ?EmailAppInterface $app,
        private readonly bool $appFromResolver = false,
    ) {
    }

    public function getEmailCode(): string
    {
        return $this->emailCode;
    }

    public function getConfig(): ?EmailMaster
    {
        return $this->config;
    }

    public function getTemplate(): ?EmailTemplate
    {
        return $this->template;
    }

    public function getApp(): ?EmailAppInterface
    {
        return $this->app;
    }

    public function isAppFromResolver(): bool
    {
        return $this->appFromResolver;
    }

    public function hasTemplate(): bool
    {
        return null !== $this->getConfig() && null !== $this->getTemplate();
    }
}