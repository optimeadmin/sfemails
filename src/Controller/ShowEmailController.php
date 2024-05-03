<?php
/**
 * @author Manuel Aguirre
 */

namespace Optime\Email\Bundle\Controller;

use Optime\Email\Bundle\Entity\EmailLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Manuel Aguirre
 */
class ShowEmailController extends AbstractController
{
    #[Route("/email/show/{uuid}", name: "optime_emails_show")]
    public function show(EmailLog $emailLog): Response
    {
        return new Response($emailLog->getContent());
    }
}