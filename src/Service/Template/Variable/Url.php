<?php
/**
 * @author Manuel Aguirre
 */

declare(strict_types=1);

namespace Optime\Email\Bundle\Service\Template\Variable;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Manuel Aguirre
 */
class Url
{
    public function __construct(private readonly string $path, private readonly array $parameters = [])
    {
    }

    public function generate(UrlGeneratorInterface $urlGenerator): string
    {
        return $urlGenerator->generate(
            $this->path,
            $this->parameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}