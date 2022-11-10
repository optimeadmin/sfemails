<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template;

use Stringable;
use function strip_tags;

/**
 * @author Manuel Aguirre
 */
class RenderedTemplate implements Stringable
{
    public function __construct(
        private readonly string $subject,
        private readonly string $content,
        private readonly string $onlyTemplateContent,
    ) {
    }

    public function __toString(): string
    {
        return $this->getContent();
    }

    public function getSubject(): string
    {
        return strip_tags($this->subject);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getOnlyTemplateContent(): string
    {
        return $this->onlyTemplateContent;
    }

    public function rawContent(): string
    {
        return strip_tags($this->getOnlyTemplateContent());
    }
}