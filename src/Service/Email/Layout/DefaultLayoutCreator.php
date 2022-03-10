<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Service\Email\Layout;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Repository\EmailLayoutRepository;
use Optime\Util\Translation\Translation;

/**
 * @author Manuel Aguirre
 */
class DefaultLayoutCreator
{
    public function __construct(
        private EmailLayoutRepository $repository,
        private Translation $translation,
    ) {
    }

    public function createIfApply(): void
    {
        if (0 !== $this->repository->count([])) {
            return;
        }

        $layout = new EmailLayout();
        $layout->setDescription('Default Layout');

        $content = <<<HTML
<html>
<head></head>
<body>
{{ content }}
</main>
</html>
HTML;

        $contentTrans = $this->translation->fromString($content);
        $layout->setContent((string)$contentTrans);

        $persister = $this->translation->preparePersist($layout);
        $persister->persist('content', $contentTrans);

        $this->repository->save($layout);
    }
}