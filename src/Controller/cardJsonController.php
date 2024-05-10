<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class cardJsonController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function deckJson(
        SessionInterface $session
    ): JsonResponse {
        $deck = $session->get('DeckOfCards');
        $deck->sortDeck();
        $cards = $deck->getSuitAndValue();

        $session->set('DeckOfCards', $deck);

        $data = [
            'cards' => $cards
        ];

        return new JsonResponse($data);
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['POST', 'GET'])]
    public function shuffleDeckJson(
        SessionInterface $session
    ): JsonResponse {
        $deck = $session->get('DeckOfCards');
        $deck->shuffleDeck();
        $cards = $deck->getSuitAndValue();

        $session->set('DeckOfCards', $deck);

        $data = [
            'cards' => $cards
        ];

        return new JsonResponse($data);
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['POST', 'GET'])]
    public function drawDeckJson(
        SessionInterface $session
    ): JsonResponse {
        $deck = $session->get('DeckOfCards');
        $drawn = $deck->drawCard();
        $value = $drawn[0]->getValue();
        $suit = $drawn[0]->getSuit();
        $card[] = ['value' => $value, 'suit' => $suit];

        $cardsLeft = $deck->getNumberCards();

        $data = [
            'cardsLeftInDeck' => $cardsLeft,
            'card' => $card
        ];

        $session->set('DeckOfCards', $deck);

        return new JsonResponse($data);
    }

    #[Route("/api/deck/draw/{num_cards<\d+>}", name: "api_deck_draw_num", methods: ['POST', 'GET'])]
    public function drawMultipleDeckJson(
        int $num_cards,
        SessionInterface $session
    ): JsonResponse {
        $deck = $session->get('DeckOfCards');
        $cards = [];
        $maxCardDraw = $deck->getNumberCards();

        if ($num_cards > 0 && $num_cards <= $maxCardDraw) {
            $drawn = $deck->drawCard($num_cards);

            foreach ($drawn as $card) {
                $value = $card->getValue();
                $suit = $card->getSuit();
                $cards[] = ['value' => $value, 'suit' => $suit];
            }
        }

        $cardsLeft = $deck->getNumberCards();
        $session->set('DeckOfCards', $deck);

        $data = [
            'cards' => $cards,
            'cardsLeftInDeck' => $cardsLeft
        ];

        return new JsonResponse($data);
    }

    #[Route("/api/card/deck/restart", name: "api_deck_restart", methods: ['POST'])]
    public function drawNumPost(
        sessionInterface $session
    ): Response {
        $session->clear();
        $deck = new DeckOfCards("graphic");
        $deck->shuffleDeck();
        $session->set('DeckOfCards', $deck);

        return $this->redirectToRoute('api');
    }

    #[Route("/api_card_post_middleware", name: "api_card_post_middleware", methods: ['POST'])]
    public function handlePost(
        Request $request
    ): Response {
        $num = $request->request->get('number');
        $nextPage = $this->generateUrl('api_deck_draw_num', ['num_cards' => $num]);

        return $this->redirect($nextPage);
    }
}
