<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Optime\Email\Bundle\Entity\EmailLog;
use Optime\Email\Bundle\Form\Type\EmailLogFilterFormType;
use Optime\Email\Bundle\Repository\EmailLogRepository;
use Optime\Email\Bundle\Service\Email\MailerLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
#[Route("/config/logs")]
class EmailLogController extends AbstractController
{
    #[Route("/", name: "optime_emails_log_list")]
    public function index(
        EmailLogRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        $form = $this->createForm(EmailLogFilterFormType::class);
        $form->handleRequest($request);

        $items = $paginator->paginate(
            $repository->queryAll($form->getData()),
            $request->query->getInt('page', 1),
            $request->query->getInt('perPage', 50),
        );

        return $this->render('@OptimeEmail/email_log/index.html.twig', [
            'items' => $items,
            'form_filter' => $form->createView(),
        ]);
    }

    #[Route("/show/{uuid}/", name: "optime_emails_log_show")]
    public function create(EmailLog $emailLog): Response
    {
        return new Response($emailLog->getContent());
    }

    #[Route("/send/{uuid}/", name: "optime_emails_log_resend")]
    public function resend(Request $request, EmailLog $emailLog, MailerLog $mailer): Response
    {
        if ($mailer->resend($emailLog)) {
            $this->addFlash('success', "Success Resend '{$emailLog->getUuid()}'!");
        } else {
            $this->addFlash('danger', "Failed Resend '{$emailLog->getUuid()}'!");
        }

        return $this->redirect($request->headers->get(
            'referer',
            $this->generateUrl('optime_emails_log_list')
        ));
    }
}