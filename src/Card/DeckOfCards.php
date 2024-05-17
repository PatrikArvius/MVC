<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

class DeckOfCards
{
    /** @var array<int, Card> $deck */
    private array $deck = [];
    private int $cardsPerSuit = 13;
    private bool $sorted = false;
    private bool $shuffled = false;
    /** @var array<int<0, max>, string> $suits */
    private array $suits = [
        'spades',
        'hearts',
        'diamonds',
        'clubs'
    ];

    public function __construct(string $cardType)
    {
        for ($i = 1; $i <= $this->cardsPerSuit; $i++) {
            $this->add($cardType, $i);
        }
    }

    public function add(string $cardType, int $value): void
    {
        foreach ($this->suits as $suit) {
            if ($cardType === "graphic") {
                $this->deck[] = new CardGraphic($suit, $value);
                continue;
            }
            $this->deck[] = new Card($suit, $value);
        }

    }

    /** @return array<int, Card> */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /** @return array<int<0, max>, array{value: int, suit: string}> */
    public function getSuitAndValue(): array
    {
        $values = [];

        foreach ($this->deck as $card) {
            $value = $card->getValue();
            $suit = $card->getSuit();
            $values[] = ['value' => $value, 'suit' => $suit];
        }

        return $values;
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    /** @return array<int<0, max>, int> */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    /** @return array<int<0, max>, string> */
    public function getString(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    /** @return array<int<0, max>, string> */
    public function getStringSorted(): array
    {
        $this->sortDeck();
        return $this->getString();
    }

    public function sortDeck(): void
    {
        if (!$this->sorted) {
            $sortedArray = [];
            $spades = [];
            $hearts = [];
            $diamonds = [];
            $clubs = [];

            //Sort cards into suit arrays
            foreach ($this->deck as $card) {

                switch ($card->getSuit()) {
                    case "spades":
                        $spades[] = $card;
                        break;
                    case "hearts":
                        $hearts[] = $card;
                        break;
                    case "diamonds":
                        $diamonds[] = $card;
                        break;
                    case "clubs":
                        $clubs[] = $card;
                        break;
                }
            }

            // Sort the suit arrays by value
            $spades = $this->sortByValue($spades);
            $hearts = $this->sortByValue($hearts);
            $diamonds = $this->sortByValue($diamonds);
            $clubs = $this->sortByValue($clubs);

            // Merge the sorted arrays into one suit and value sorted array
            $sortedArray = array_merge($spades, $hearts, $diamonds, $clubs);

            // Set object deck array as the sorted deck array
            $this->deck = $sortedArray;
            $this->sorted = true;
            $this->shuffled = false;
        }
    }

    /**
     * @param array<int<0, max>, Card> $suitedDeck
     * @return array<int<0, max>, Card> $sortedArray
    */
    public function sortByValue(array $suitedDeck): array
    {
        $suitedDeck = $suitedDeck;
        $sortedArray = [];

        // Looping over cardsPerSuit ensures not missing a card value
        // if a card has been drawn from the deck
        for ($i = 1; $i <= $this->cardsPerSuit; $i++) {
            foreach ($suitedDeck as $card) {
                $val = $card->getValue();
                if ($val === $i) {
                    array_push($sortedArray, $card);
                }
            }
        }

        return $sortedArray;
    }

    public function shuffleDeck(): void
    {
        if (!$this->shuffled) {
            $deck = $this->deck;
            shuffle($deck);
            $this->deck = $deck;
            $this->shuffled = true;
            $this->sorted = false;
        }
    }

    /** @return array<int<0, max>, Card> $cards */
    public function drawCard(int $num = 1): array
    {
        $cards = [];

        if ($this->getNumberCards() >= $num) {
            for ($i = 1; $i <= $num; $i++) {
                $card = array_pop($this->deck);
                array_push($cards, $card);
            }

        }

        return $cards;
    }
}
