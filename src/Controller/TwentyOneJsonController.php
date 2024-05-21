<?php

namespace App\Controller;

use App\TwentyOne\TwentyOne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneJsonController extends AbstractController
{
    #[Route("/api/twentyone", name: "api_twentyone", methods: ['GET'])]
    public function twentyoneJson(
        SessionInterface $session
    ): Response {
        /** @var ?TwentyOne */
        $twentyOne = $session->get('TwentyOne');

        if ($twentyOne) {
            $playerVal = $twentyOne->getSpecificHandValue('player');
            $playerAltVal = $twentyOne->getSpecificHandValue('altPlayer');
            $dealerVal = $twentyOne->getSpecificHandValue('dealer');
            $dealerAltVal = $twentyOne->getSpecificHandValue('altDealer');

            $data = [
                'playerHandValue' => $playerVal,
                'playerAltHandValue' => $playerAltVal,
                'dealerHandValue' => $dealerVal,
                'dealerAltHandValue' => $dealerAltVal,
            ];

            return new JsonResponse($data);
        }

        return $this->redirectToRoute('api');
    }
}
