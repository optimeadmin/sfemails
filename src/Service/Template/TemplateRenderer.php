<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Template;

use Doctrine\ORM\EntityManagerInterface;
use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Util\Translation\TranslationsAwareInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\TemplateWrapper;
use function is_array;

/**
 * @author Manuel Aguirre
 */
class TemplateRenderer
{
    public function __construct(
        private Environment $twig,
        private ArrayLoader $twigLoader,
        private TranslatorInterface $translator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function renderContent(string $content, array $variables = []): string
    {
        $templateName = 'template';
        $this->twigLoader->setTemplate($templateName, $content);

        return $this->doRender($templateName, $variables);
    }

    public function renderLayout(EmailLayout $layout, array $variables = []): string
    {
        return $this->renderContent($layout, $variables);
    }

    public function render(EmailTemplate $template, array $variables = [], bool $withLayout = true): string
    {
        $entities = [$template, $template->getLayout()];
        $this->refreshEntityForEmail($variables, $entities);

        $content = $this->renderContent($template, $variables);

        if ($withLayout) {
            $content = $this->renderLayout(
                $template->getLayout(),
                [...$variables, 'content' => $content],
            );
        }

        $this->restoreEntityLocale($variables, $entities);

        return $content;
    }

    private function doRender(string|TemplateWrapper $template, array $vars): string
    {
        $originalLocale = $this->setLocaleIfApply($vars['_locale'] ?? null);
        $content = $this->twig->render($template, $vars);
        $this->restoreLocaleIfApply($originalLocale);

        return $content;
    }

    private function setLocaleIfApply(?string $locale): string
    {
        $originalLocale = $this->translator->getLocale();
        if (null == $locale || $locale === $originalLocale) {
            return $originalLocale;
        }

        if ($this->translator instanceof LocaleAwareInterface) {
            $this->translator->setLocale($locale);
        }

        return $originalLocale;
    }

    private function getLocale(array $templateVars): string
    {
        return $templateVars['_locale'] ?? $this->translator->getLocale();
    }

    private function refreshEntityForEmail(array $templateVars, TranslationsAwareInterface|array $entity): void
    {
        $locale = $this->getLocale($templateVars);

        if ($locale !== $this->translator->getLocale()) {
            if (!is_array($entity)) {
                $entity = [$entity];
            }

            foreach ($entity as $item) {
                $item->setCurrentContentsLocale($locale);
                $this->entityManager->refresh($item);
            }
        }
    }

    private function restoreEntityLocale(array $templateVars, TranslationsAwareInterface|array $entity): void
    {
        $locale = $this->getLocale($templateVars);

        if ($locale !== $this->translator->getLocale()) {
            if (!is_array($entity)) {
                $entity = [$entity];
            }

            foreach ($entity as $item) {
                $item->setCurrentContentsLocale($this->translator->getLocale());
                $this->entityManager->refresh($item);
            }
        }
    }

    private function restoreLocaleIfApply(string $locale): void
    {
        if ($this->translator instanceof LocaleAwareInterface) {
            $this->translator->setLocale($locale);
        }
    }
}