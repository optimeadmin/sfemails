<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Dto;

use Optime\Email\Bundle\Constraints\TwigContent;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Optime\Util\Entity\Embedded\Date;
use Optime\Util\Translation\TranslatableContent;
use Optime\Util\Translation\Validation\TranslatableConstraint;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * @author Manuel Aguirre
 */
class EmailTemplateDto
{
    public ?Uuid $uuid = null;
    #[NotBlank]
    public int|string|null $appId = null;

    public ?string $appTitle = null;

    #[NotBlank]
    public ?Uuid $configUuid = null;
    public ?string $configCode = null;

    public ?Uuid $layoutUuid = null;
    public ?string $layoutTitle = null;

    #[NotBlank]
    #[TranslatableConstraint([new NotBlank(), new TwigContent()], '')]
    public ?TranslatableContent $subject = null;

    #[NotBlank]
    #[TranslatableConstraint([new NotBlank(), new TwigContent()], '')]
    public ?TranslatableContent $content = null;

    #[NotNull]
    public ?bool $active = null;

    public ?Date $dates = null;

    public static function fromEntity(EmailTemplate $template, EmailAppProvider $appProvider): self
    {
        $dto = new self();
        $dto->uuid = $template->getUuid();
        $dto->appId = $appProvider->getIndexByApp($template->getApp());
        $dto->appTitle = (string)$template->getApp();
        $dto->layoutUuid = $template->getCustomLayout()?->getUuid();
        $dto->layoutTitle = $template->getLayout()->getDescription();

        $dto->configUuid = $template->getConfig()->getUuid();
        $dto->configCode = $template->getConfig()->getCode();

        $dto->subject = TranslatableContent::pending($template, 'subject');
        $dto->content = TranslatableContent::pending($template, 'content');

        $dto->active = $template->isActive();
        $dto->dates = $template->getDates();

        return $dto;
    }
}