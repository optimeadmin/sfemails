<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;


use Optime\Email\Bundle\Entity\EmailApp;
use Optime\Email\Bundle\Entity\EmailMaster;
use Optime\Email\Bundle\Entity\EmailTemplate;

/**
 * @author Manuel Aguirre
 */
class TemplateData
{
    public function __construct(
        private string $emailCode,
        private ?EmailMaster $config,
        private ?EmailTemplate $template,
        private ?EmailApp $app,
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

    public function getApp(): ?EmailApp
    {
        return $this->app;
    }

    public function hasTemplate(): bool
    {
        return null !== $this->getConfig() && null !== $this->getTemplate();
    }
}