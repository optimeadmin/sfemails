<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email;

use Optime\Email\Bundle\Entity\EmailAppInterface;
use Optime\Email\Bundle\Repository\EmailAppRepository;
use Optime\Email\Bundle\Repository\EmailMasterRepository;
use Optime\Email\Bundle\Repository\EmailTemplateRepository;
use Optime\Email\Bundle\Service\Email\App\DefaultEmailAppResolverInterface;
use Optime\Email\Bundle\Service\Email\App\EmailAppResolverInterface;

/**
 * @author Manuel Aguirre
 */
class MailerFactory
{
    public function __construct(
        private Mailer $mailer,
        private MailerIntentUtils $mailerIntentUtils,
        private EmailMasterRepository $masterRepository,
        private EmailTemplateRepository $templateRepository,
        private DefaultEmailAppResolverInterface $defaultEmailAppResolver,
    ) {
    }

    public function create(
        string $emailCode,
        EmailAppInterface|EmailAppResolverInterface $app = null,
    ): MailerIntent {
        $config = $this->masterRepository->byCode($emailCode);

        if (!$config) {
            return $this->createMailerIntent(
                new TemplateData($emailCode, null, null, null)
            );
        }

        if (null === $app) {
            // revisamos a ver si hay una app por efecto
            $app = $this->defaultEmailAppResolver->getDefaultIfApply($config);

            if (null === $app) {
                return $this->createMailerIntent(
                    new TemplateData($emailCode, $config, null, null)
                );
            }
        }

        if ($app instanceof EmailAppResolverInterface) {
            $app = $app->resolve($config);

            if (!$app) {
                return $this->createMailerIntent(
                    new TemplateData($emailCode, $config, null, null, true)
                );
            }
        }

        return $this->createMailerIntent(new TemplateData(
            $emailCode,
            $config,
            $this->templateRepository->byConfigAndApp($config, $app),
            $app
        ));
    }

    private function createMailerIntent(TemplateData $templateData): MailerIntent
    {
        return new MailerIntent(
            $this->mailer,
            $this->mailerIntentUtils,
            $templateData,
        );
    }
}