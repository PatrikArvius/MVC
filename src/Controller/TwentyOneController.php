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

    #[Route("/twentyone/play", name: "twentyone_play")]
    public function gamePlay(
    ): Response {
        return $this->render('twentyone/gameplay.html.twig');
    }

    #[Route("/twentyone", name: "twentyone_post", methods: ['POST'])]
    public function twentyoneInit(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards("graphic");
        $player = new Player(new CardHand());
        $dealer = new Dealer(new CardHand());
        $twentyOne = new TwentyOne($player, $dealer, $deck);
        $session->set('TwentyOne', $twentyOne);

        return $this->redirectToRoute('twentyone_play');
    }
}
