<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DiceGameController extends AbstractController
{
    #[Route("/game/pig", name: "pig_start", methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
    }

    #[Route("/game/pig", name: "pig_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        // Deal with the submitted form
        $numDice = $request->request->get('num_dices');

        $hand = new DiceHand();
        for ($i = 1; $i <= $numDice; $i++) {
            $hand->add(new DiceGraphic());
        }
        $hand->roll();

        $session->set("pig_dicehand", $hand);
        $session->set("pig_dices", $numDice);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        // Logic to play the game
        $diceHand = $session->get("pig_dicehand");

        $data = [
            "pigDices" => $session->get("pig_dices"),
            "pigRound" => $session->get("pig_round"),
            "pigTotal" => $session->get("pig_total"),
            "diceValues" => $diceHand->getString()
        ];

        return $this->render('pig/play.html.twig', $data);
    }

    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
    public function roll(
        SessionInterface $session
    ): Response {
        // Logic to roll the dice
        $hand = $session->get("pig_dicehand");
        $hand->roll();

        $roundTotal = $session->get("pig_round");
        $currentRound = 0;
        $values = $hand->getValues();

        foreach ($values as $value) {
            if ($value === 1) {
                $currentRound = 0;
                $roundTotal = 0;

                $this->addFlash(
                    'warning',
                    'You got a 1 and you lost the round points!'
                );

                break;
            }
            $currentRound += $value;
        }

        $session->set("pig_round", $roundTotal + $currentRound);

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
    public function save(
        SessionInterface $session
    ): Response {
        // Logic to save the round
        $roundTotal = $session->get("pig_round");
        $gameTotal = $session->get("pig_total");

        $session->set("pig_round", 0);
        $session->set("pig_total", $roundTotal + $gameTotal);

        $this->addFlash(
            'notice',
            'Your round was saved to the total!'
        );

        return $this->redirectToRoute('pig_play');
    }

    #[Route("/game/pig/test/roll/{num_dice<\d+>}", name: "test_roll_dice")]

    public function testRollDices(int $num_dice): Response
    {
        if ($num_dice && $num_dice > 99) {
            throw new \Exception("Please roll between 1 and 99 dices");
        }

        $diceRolls = [];

        if ($num_dice) {
            for ($i = 1; $i <= $num_dice; $i++) {
                //$die = new Dice();
                $die = new DiceGraphic();
                $die->roll();
                $diceRolls[] = $die->getAsString();
            }
        }

        $data = [
            "num_dices" => count($diceRolls),
            "diceRolls" => $diceRolls
        ];

        return $this->render('pig/test/roll.html.twig', $data);
    }

    #[Route("/game/pig/test/dicehand/{num<\d+>}", name: "test_dicehand")]
    public function testDiceHand(int $num): Response
    {
        if ($num > 99) {
            throw new \Exception("Can not roll more than 99 dices!");
        }

        $hand = new DiceHand();
        for ($i = 1; $i <= $num; $i++) {
            if ($i % 2 === 1) {
                $hand->add(new DiceGraphic());
            } else {
                $hand->add(new Dice());
            }
        }

        $hand->roll();

        $data = [
            "num_dices" => $hand->getNumberDices(),
            "diceRoll" => $hand->getString(),
        ];

        return $this->render('pig/test/dicehand.html.twig', $data);
    }
}
