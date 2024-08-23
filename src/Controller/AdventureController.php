<?php

namespace App\Controller;

use App\Adventure\AbandonedTrainStation;
use App\Adventure\AdventureGame;
use App\Adventure\EndRoom;
use App\Adventure\Player;
use App\Adventure\Room;
use App\Adventure\Item;
use App\Adventure\MountainVillage;
use App\Adventure\MountainHouse;
use App\Adventure\TrainTracks;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdventureController extends AbstractController
{
    #[Route("/proj", name: "adventure")]
    public function home(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    #[Route("/proj/about", name: "advabout")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    #[Route("/proj/start", name: "adv_init", methods: ['POST'])]
    public function adventureInit(
        sessionInterface $session,
        Request $request
    ): Response {
        /** @var string|null $cheats */
        $cheats = $request->request->get('cheats');
        $itemKey = new Item("Key", "None", "A key, what door does it unlock?",1);
        $itemLamp = new Item("Lamp", "None", "An old lamp, but it still works", 2);
        $trainStation = new AbandonedTrainStation([$itemLamp]);
        $tracks = new TrainTracks([$itemKey], true, $itemLamp);
        $mountainVillage = new MountainVillage();
        $topHouse = new MountainHouse(null, true, $itemKey);
        $endRoom = new EndRoom();
        $player = new Player();
        $adventureGame = new AdventureGame([$trainStation, $tracks, $mountainVillage, $topHouse], $endRoom, $player, $cheats);
        $session->set("adventure", $adventureGame);

        $data = [
            'adventureGame' => $adventureGame,
        ];

        return $this->render('proj/adventure.html.twig', $data);
    }

    #[Route("/proj/play", name: "adv_play", methods: ['POST', 'GET'])]
    public function adventurePlay(
        sessionInterface $session,
        Request $request
    ): Response {
        /** @var string $action */
        $action = $request->request->get('hidden');
        /** @var string $itemToUse */
        $itemToUse = $request->request->get('item');
        /** @var AdventureGame */
        $adventureGame = $session->get("adventure");

        if ($action != null) {
            $adventureGame->useAction($action);
        }

        if ($itemToUse != null) {
            $adventureGame->useItem($itemToUse);
        }

        $adventureGame->checkGameEnd();

        $data = [
            'adventureGame' => $adventureGame,
            'action' => $action
        ];

        return $this->render('proj/adventure.html.twig', $data);
    }
}
