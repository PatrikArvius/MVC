<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/session", name: "session_content")]
    public function sessionContent(
        SessionInterface $session
    ): Response
    {
        $data = ['session' => $session->all()];

        return $this->render('card/session.html.twig', $data);
    }

    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(
        SessionInterface $session
    ): Response
    {
        $session->clear();
        
        $this->addFlash(
            'notice',
            'Session has been deleted'
        );

        return $this->redirectToRoute('session_content');
    }

    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        $deck = new DeckOfCards("graphic");

        $cards = $deck->getStringSorted();
        //$cards = $deck->getString();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/card_home.html.twig', $data);
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {
        $deck = new DeckOfCards("graphic");

        $cards = $deck->getString();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/card_home.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function shuffle(): Response
    {
        $deck = new DeckOfCards("graphic");

        $cards = $deck->getString();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/card_home.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function draw(): Response
    {
        $deck = new DeckOfCards("graphic");

        $cards = $deck->getString();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/card_home.html.twig', $data);
    }

    #[Route("/card/deck/draw/:number", name: "card_deck_draw_num")]
    public function drawNum(): Response
    {
        $deck = new DeckOfCards("graphic");

        $cards = $deck->getString();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/card_home.html.twig', $data);
    }
}
