<?php

namespace App\Controller;

use App\Dice\Dice;
use App\Dice\DiceGraphic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiceGameController extends AbstractController
{
    #[Route("/game/pig", name: "pig_start")]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
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
}
