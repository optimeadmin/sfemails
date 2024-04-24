<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Controller\Api;

use Optime\Email\Bundle\Service\Email\App\EmailAppProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Manuel Aguirre
 */
#[Route('/api/email-apps')]
class EmailAppController extends AbstractController
{
    public function __construct(
        private readonly EmailAppProvider $appProvider,
    ) {
    }

    #[Route('', methods: 'get')]
    public function getAll(): JsonResponse
    {
        $items = $this->appProvider->getRepository()->findAll();

        $mappedData = [];

        foreach ($items as $index => $app) {
            $mappedData[$index] = ['title' => (string)$app, 'id' => $index];
        }

        return $this->json($mappedData, context: [ObjectNormalizer::class]);
    }
}