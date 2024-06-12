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
    /** @var array<int, string> $suits */
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

    /**
     * Method that adds a card of specified type based on string parameter with specified integer value
     * 
     * @param string $cardType
     * @param int $value
     */
    public function add(string $cardType, int $value): void
    {
        // Adds a card for each suit of specified type and with specified value
        foreach ($this->suits as $suit) {
            if ($cardType === "graphic") {
                $this->deck[] = new CardGraphic($suit, $value);
                continue;
            }
            //Defaults to non-graphic card if string parameter doesnt match the if case
            $this->deck[] = new Card($suit, $value);
        }

    }

    /** @return array<int, Card> */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /** @return array<int, array{value: int, suit: string}> */
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

    /**
     * Gets the number of card objects in the deck array
     * 
     * @return int
     */
    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    /** @return array<int, int|null> */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    /** @return array<int, string> */
    public function getString(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    /** @return array<int, string> */
    public function getStringSorted(): array
    {
        $this->sortDeck();
        return $this->getString();
    }

    /**
     * Method that sorts the deck of cards, first by putting the cards into arrays based on suit 
     * and then sorting each suited array by the card value
     */
    public function sortDeck(): void
    {
        if (!$this->sorted) {
            $sortedArray = $this->getSortedBySuit();

            // Sort the suit arrays by value
            $spades = $this->sortByValue($sortedArray[0]);
            $hearts = $this->sortByValue($sortedArray[1]);
            $diamonds = $this->sortByValue($sortedArray[2]);
            $clubs = $this->sortByValue($sortedArray[3]);

            // Merge the sorted arrays into one suit and value sorted array
            $sortedArray = array_merge($spades, $hearts, $diamonds, $clubs);

            // Set object deck array as the sorted deck array
            $this->deck = $sortedArray;
            $this->sorted = true;
            $this->shuffled = false;
        }
    }

    /**
     * Method that sorts cards based on suits and returns an array of suited arrays
     * 
     * @return array<int, array<int, Card>>
     */
    public function getSortedBySuit(): array
    {
        $suitSortedArray = [];
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
        array_push($suitSortedArray, $spades, $hearts, $diamonds, $clubs);
        return $suitSortedArray;
    }

    /**
     * Method that sorts an array of card objects by their values
     * 
     * @param array<int, Card> $suitedDeck
     * @return array<int, Card> $sortedArray
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

    /**
     * Method that checks if the deck has already been shuffled and if not then it shuffles the deck
     * then it sets variables to refelect the shuffled state of the deck
     */
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

    /** @return array<int, Card> $cards */
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
