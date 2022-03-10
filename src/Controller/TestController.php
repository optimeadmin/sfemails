<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Optime\Email\Bundle\Entity\EmailTemplate;
use Optime\Email\Bundle\Service\Email\MailerFactory;
use Optime\Email\Bundle\Service\Email\Recipient\EmailRecipient;
use Optime\Email\Bundle\Service\Template\VariablesExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use function array_reduce;
use function base64_decode;
use function base64_encode;
use function explode;
use function json_decode;
use function json_encode;
use function str_replace;
use const PHP_EOL;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/test")]
class TestController extends AbstractController
{
    public function __construct(
        private VariablesExtractor $variablesExtractor,
        private MailerFactory $mailerFactory
    ) {
    }

    #[Route("/{uuid}/send", name: "optime_emails_send_test")]
    public function index(EmailTemplate $template, Request $request): Response
    {
        if ($request->query->has('form')) {
            $data = json_decode(base64_decode($request->query->get('form')), true);
        } else {
            $vars = $this->variablesExtractor->extract($template);
            $vars = array_reduce($vars, fn($vars, $item) => $vars . $item . ': ' . PHP_EOL, '');
            $data = ['vars' => $vars];
        }

        $form = $this->createFormBuilder($data)
            ->add('vars', TextareaType::class, [
                'attr' => [
                    'rows' => 10,
                ],
                'constraints' => new NotBlank(),
            ])
            ->add('recipient', EmailType::class, [
                'constraints' => new NotBlank(),
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $vars = $this->getVars($form['vars']->getData());

            $success = $this->mailerFactory->create(
                $template->getConfig()->getCode(),
                $template->getApp(),
            )->send($vars, EmailRecipient::fromEmail($form['recipient']->getData()));

            if ($success) {
                $this->addFlash('success', 'Email Send!!!');
            } else {
                $this->addFlash('warning', 'Email Send Failed!!!');
            }

            return $this->redirectToRoute('optime_emails_send_test', [
                'uuid' => $template->getUuid(),
                'form' => base64_encode(json_encode($form->getData())),
            ]);
        }

        return $this->render('@OptimeEmail/preview/test_send.html.twig', [
            'form' => $form->createView(),
            'template' => $template,
        ]);
    }

    private function getVars(string $vars): array
    {
        $data = [];

        foreach (explode(PHP_EOL, $vars) as $var) {
            $row = explode(':', $var, 2);
            $data[str_replace(['{', '}', ' '], '', $row[0] ?? '')] = trim($row[1] ?? '');
        }

        return $data;
    }
}