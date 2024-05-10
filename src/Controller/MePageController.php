<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MePageController extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/lucky", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }

    #[Route("/api", name: "api")]
    public function apiRoutes(
        sessionInterface $session
    ): Response {
        $deck = $session->get('DeckOfCards');
        $numCards = 0;
        if ($deck) {
            $numCards = $deck->getNumberCards();
        }

        $routes = [
            'gives you your daily quote' => '/api/quote',
            'returns JSON with card deck sorted by suit and values' => '/api/deck',
            'returns JSON of shuffled deck' => '/api/deck/shuffle',
            'returns JSON of drawn card' => '/api/deck/draw',
            'returns JSON of a number of drawn cards' => '/api/deck/draw/:number',
        ];

        $data = [
            'routes' => $routes,
            'numCards' => $numCards
        ];

        return $this->render('api.html.twig', $data);
    }

    #[Route("/api/quote", name: "quote")]
    public function jsonQuote(): JsonResponse
    {
        $quotes = [
            "We demand rigidly defined areas of doubt and uncertainty!" => "Douglas Adams, The Hitchhiker’s Guide to the Galaxy ",
            "Things are only impossible until they're not" => "Captain Jean-Lic Picard, USS Enterprise",
            "One repays a teacher badly if one always remains nothing but a pupil" => "Friedrich Nietzsche, Thus Spoke Zarathustra"
        ];

        $quote = array_rand($quotes, 1);
        $author = $quotes[$quote];

        $response = [
            'quote' => $quote,
            'author' => $author,
            'date' => date("Y/m/d"),
            'timestamp' => date("H:i:s")
        ];

        return new JsonResponse($response);
    }
}
