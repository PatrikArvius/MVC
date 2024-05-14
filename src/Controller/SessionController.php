<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route("/session", name: "session_content")]
    public function sessionContent(
        SessionInterface $session
    ): Response {
        $data = ['session' => $session->all()];

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(
        SessionInterface $session
    ): Response {
        $session->clear();

        $this->addFlash(
            'notice',
            'Session has been deleted'
        );

        return $this->redirectToRoute('session_content');
    }
}
