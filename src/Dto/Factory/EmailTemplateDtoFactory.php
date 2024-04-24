<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto\Factory;

use Optime\Email\Bundle\Dto\EmailTemplateDto;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use function array_map;

/**
 * @author Manuel Aguirre
 */
class EmailTemplateDtoFactory
{
    public function __construct(private readonly EmailAppProvider $appProvider)
    {
    }

    public function create(EmailTemplate $template): EmailTemplateDto
    {
        return EmailTemplateDto::fromEntity($template, $this->appProvider);
    }

    public function fromItems(array $items): array
    {
        return array_map($this->create(...), $items);
    }
}