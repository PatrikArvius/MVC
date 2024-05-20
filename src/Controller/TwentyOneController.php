<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\TwentyOne\Player;
use App\TwentyOne\Dealer;
use App\TwentyOne\TwentyOne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneController extends AbstractController
{
    #[Route("/twentyone", name: "twentyone", methods: ['GET'])]
    public function twentyone(
    ): Response {
        return $this->render('twentyone/game.html.twig');
    }

    #[Route("/twentyone/doc", name: "twentyone_doc")]
    public function twentyOneDoc(
    ): Response {
        return $this->render('twentyone/gamedocs.html.twig');
    }

    #[Route("/twentyone/play", name: "twentyone_play", methods: ['POST'])]
    public function gamePlay(
        SessionInterface $session
    ): Response {
        /** @var TwentyOne */
        $twentyOne = $session->get('TwentyOne');
        if (!$twentyOne->getWinner()) {
            $twentyOne->playRound();
        }

        /** @var Player */
        $player = $twentyOne->getPlayer();
        /** @var Dealer */
        $dealer = $twentyOne->getDealer();

        $playerVal = $twentyOne->getSpecificHandValue('player');
        $playerAltVal = $twentyOne->getSpecificHandValue('altPlayer');
        $winner = $twentyOne->getWinner();
        $standing = $twentyOne->getAllStanding();
        $playerHand = $player->getString();
        $dealerHand = $dealer->getString();

        $data = [
            'playerVal' => $playerVal,
            'playerAltVal' => $playerAltVal,
            'winner' => $winner,
            'standing' => $standing,
            'playerHand' => $playerHand,
            'dealerHand' => $dealerHand
        ];
        return $this->render('twentyone/gameplay.html.twig', $data);
    }

    #[Route("/twentyone", name: "twentyone_post", methods: ['POST'])]
    public function twentyoneInit(
        SessionInterface $session
    ): Response {
        $session->clear();
        $deck = new DeckOfCards("graphic");
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $session->set('TwentyOne', $twentyOne);

        return $this->render('twentyone/gameplay.html.twig');
    }

    #[Route("/twentyone/play", name: "twentyone_game_post", methods: ['POST'])]
    public function twentyoneGame(
    ): Response {
        return $this->redirectToRoute('twentyone_play');
    }
}
