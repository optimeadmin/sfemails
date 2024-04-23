<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template;

use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Util\Translation\Translation;

/**
 * @author Manuel Aguirre
 */
class LocalizedTemplateProvider
{
    private array $loadedTemplates = [];

    public function __construct(
        private readonly Translation $translation,
    ) {
    }

    public function get(EmailTemplate $template, string $locale): LocalizedTemplate
    {
        if (isset($this->loadedTemplates[$template->getId()][$locale])) {
            return $this->loadedTemplates[$template->getId()][$locale];
        }

        $this->translation->refreshInDefaultLocale($template);
        $this->translation->refreshInDefaultLocale($template->getLayout());

        $subject = $this->translation->loadContent($template, 'subject');
        $content = $this->translation->loadContent($template, 'content');
        $layout = $this->translation->loadContent($template->getLayout(), 'content');

        foreach ($subject->getValues() as $subjectLocale => $subjectValue) {
            $this->loadedTemplates[$template->getId()][$subjectLocale] = new LocalizedTemplate(
                $subjectValue,
                $content->byLocale($subjectLocale) ?? '',
                $layout->byLocale($subjectLocale) ?? '',
                $subjectLocale,
            );
        }

        return $this->loadedTemplates[$template->getId()][$locale] ??= new LocalizedTemplate(
            '-- [no value ' . $locale . '] --',
            '-- [no value ' . $locale . '] --',
            '-- [no value ' . $locale . '] --',
            $locale
        );
    }
}