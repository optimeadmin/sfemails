<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Listener;

use InvalidArgumentException;
use Optime\Email\Bundle\Repository\EmailLogRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;
use function str_replace;
use function str_starts_with;

/**
 * @author Manuel Aguirre
 */
#[AutoconfigureTag('kernel.event_listener')]
class RoutingExceptionListener
{
    private const PATH = '/email/show/';

    public function __construct(private readonly EmailLogRepository $repository)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NotFoundHttpException) {
            return;
        }

        $request = $event->getRequest();
        $url = $request->getRequestUri();

        if (!str_starts_with($url, self::PATH)) {
            return;
        }

        try {
            $uuidAsString = str_replace(self::PATH, '', $url);
            $uuid = Uuid::fromString($uuidAsString);
        } catch (InvalidArgumentException) {
            throw new NotFoundHttpException("Invalid email uuid '$uuidAsString'");
        }

        if (!$emailLog = $this->repository->byUuid($uuid)) {
            throw new NotFoundHttpException("Email not found");
        }

        $event->setResponse(new Response($emailLog->getContent()));
        $event->allowCustomResponseCode();
    }
}