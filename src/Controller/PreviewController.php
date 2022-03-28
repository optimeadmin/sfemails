<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Optime\Email\Bundle\Entity\EmailLayout;
use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Template\TemplateRenderer;
use Optime\Email\Bundle\Service\Template\VariablesExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\Error;
use Twig\Error\RuntimeError;
use function dd;
use function preg_match;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/preview")]
class PreviewController extends AbstractController
{
    public function __construct(
        private TemplateRenderer $renderer,
    ) {
    }

    #[Route("/layout/{uuid}/", name: "optime_emails_layout_preview")]
    public function layout(EmailLayout $layout): Response
    {
        return new Response($this->renderer->renderLayout($layout));
    }

    #[Route("/template/{uuid}/", name: "optime_emails_template_preview")]
    public function template(EmailTemplate $template): Response
    {
        try {
            return $this->render('@OptimeEmail/preview/preview.html.twig', [
                'content' => $this->renderer->render($template),
            ]);
        } catch (RuntimeError $exception) {
            return $this->redirectToRoute('optime_emails_template_with_params_preview', [
                'uuid' => $template->getUuid(),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    #[Route("/template-with-params/{uuid}/", name: "optime_emails_template_with_params_preview")]
    public function templateWithParams(
        EmailTemplate $template,
        Request $request,
        VariablesExtractor $variablesExtractor,
    ): Response {
        $error = $request->query->get('error');
        $vars = $variablesExtractor->extractAsYaml($template, $this->extractVarsFromErrorIfApply($error));

        if ($request->isMethod('post')) {
            if ($request->request->has('variables')) {
                $vars = $request->request->get('variables');

                try {
                    return $this->render('@OptimeEmail/preview/preview.html.twig', [
                        'content' => $this->renderer->render(
                            $template,
                            $variablesExtractor->buildVarsFromYaml($vars)
                        ),
                    ]);
                } catch (Error $exception) {
                    $error = $exception->getMessage();
                }
            }
        }

        return $this->render('@OptimeEmail/preview/preview_with_params.html.twig', [
            'template' => $template,
            'vars' => $vars,
            'error' => $error,
        ]);
    }

    private function extractVarsFromErrorIfApply(?string $error): array
    {
        if (preg_match('/"Parameter "(.+)" for route/', $error, $match)) {
            return isset($match[1]) ? [$match[1] => ''] : [];
        }

        return [];
    }
}