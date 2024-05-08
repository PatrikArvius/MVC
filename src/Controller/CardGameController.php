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

    #[Route("/card", name: "card_start", methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('card/card_home.html.twig');
    }

    #[Route("/card", name: "card_post", methods: ['POST'])]
    public function deckInit(
        SessionInterface $session
    ): Response {
        // Creates a deckofcards object based on submitted form in card_start
        // Saves it to the session
        $deck = new DeckOfCards("graphic");
        $session->set('DeckOfCards', $deck);

        $this->addFlash(
            'notice',
            'A deck of cards was created and added to session'
        );

        return $this->redirectToRoute('card_start');
    }

    #[Route("/card/deck", name: "card_deck", methods: ['GET'])]
    public function deck(
        sessionInterface $session
    ): Response {
        $deck = $session->get('DeckOfCards');

        $cards = $deck->getStringSorted();
        $data = [
            'cards' => $cards
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ['GET'])]
    public function shuffle(
        sessionInterface $session
    ): Response {
        $deck = $session->get('DeckOfCards');
        $deck->shuffleDeck();
        $cards = $deck->getString();

        $data = [
            'cards' => $cards
        ];


        return $this->render('card/shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function draw(): Response
    {

        return $this->render('card/card_home.html.twig');
    }

    #[Route("/card/deck/draw/:number", name: "card_deck_draw_num")]
    public function drawNum(): Response
    {

        return $this->render('card/card_home.html.twig');
    }
}
