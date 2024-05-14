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
        /** @var DeckOfCards */
        $deck = $session->get('DeckOfCards');

        $cards = $deck->getStringSorted();
        $data = [
            'cards' => $cards
        ];

        $session->set('DeckOfCards', $deck);

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle", methods: ['GET'])]
    public function shuffle(
        sessionInterface $session
    ): Response {
        /** @var DeckOfCards */
        $deck = $session->get('DeckOfCards');
        $deck->shuffleDeck();
        $cards = $deck->getString();

        $data = [
            'cards' => $cards
        ];

        $session->set('DeckOfCards', $deck);

        return $this->render('card/shuffle.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw", methods: ['GET'])]
    public function draw(
        sessionInterface $session
    ): Response {
        /** @var DeckOfCards */
        $deck = $session->get('DeckOfCards');
        $drawn = $deck->drawCard();
        $drawnAsString = [];

        foreach ($drawn as $card) {

            $drawnAsString[] = $card->getAsString();
        }

        $cardsLeft = $deck->getNumberCards();

        $data = [
            'numCards' => $cardsLeft,
            'cards' => $drawnAsString
        ];

        $session->set('DeckOfCards', $deck);

        return $this->render('card/draw.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw_post", methods: ['POST'])]
    public function drawPost(): Response
    {
        // Redirects to card/deck/draw for drawing another card.

        return $this->redirectToRoute('card_deck_draw');
    }

    #[Route("/card/deck/draw/{numCards<\d+>}", name: "card_deck_draw_num", methods: ['GET'])]
    public function drawNum(
        int $numCards,
        sessionInterface $session
    ): Response {
        /** @var DeckOfCards */
        $deck = $session->get('DeckOfCards');
        $drawnAsString = [];
        $maxCardDraw = $deck->getNumberCards();

        if ($numCards > 0 && $numCards <= $maxCardDraw) {
            $drawn = $deck->drawCard($numCards);

            foreach ($drawn as $card) {

                $drawnAsString[] = $card->getAsString();
            }
        }

        $cardsLeft = $deck->getNumberCards();

        if ($numCards > $maxCardDraw) {
            $this->addFlash(
                'warning',
                "You can't draw more cards than what's available in the deck!"
            );
        }

        $data = [
            'numCards' => $cardsLeft,
            'cards' => $drawnAsString
        ];

        $session->set('DeckOfCards', $deck);

        return $this->render('card/draw_num.html.twig', $data);
    }

    #[Route("/card/deck/restart", name: "card_deck_restart", methods: ['POST'])]
    public function drawNumPost(
        sessionInterface $session
    ): Response {
        $session->clear();
        $deck = new DeckOfCards("graphic");
        $deck->shuffleDeck();
        $session->set('DeckOfCards', $deck);

        return $this->redirectToRoute('card_deck_draw_num', ['numCards' => 0]);
    }

    #[Route("/card_post_middleware", name: "card_post_middleware", methods: ['POST'])]
    public function handlePost(
        Request $request
    ): Response {
        $num = $request->request->get('number');
        $nextPage = $this->generateUrl('card_deck_draw_num', ['numCards' => $num]);

        return $this->redirect($nextPage);
    }
}
