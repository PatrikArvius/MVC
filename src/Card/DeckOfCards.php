<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

class DeckOfCards
{
    private $deck = [];
    private int $cardsPerSuit = 13;
    private array $suits = [
        'spades',
        'hearts',
        'diamonds',
        'clubs'
    ];

    public function __construct($cardType)
    {
        for ($i = 1; $i <= $this->cardsPerSuit; $i++) {
            $this->add($cardType, $i);
        }
    }

    public function add($cardType, $value): void
    {
        foreach ($this->suits as $suit) {
            if ($cardType === "graphic") {
                $this->deck[] = new CardGraphic($suit, $value);
             }
              else {
                 $this->deck[] = new Card($suit, $value);
              }
        }
        
    }

    public function getNumberCards(): int
    {
        return count($this->deck);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    public function getString(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getAsString();
        }

        return $values;
    }

    public function getStringSorted(): array 
    {
        $values = [];
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
        $values = array_merge($spades, $hearts, $diamonds, $clubs);

        //Get string representation of each card from the sorted array
        $strValues = [];
        foreach ($values as $card) {

            $strValues[] = $card->getAsString();
        }
        return $strValues;
    }

    public function sortByValue(array $suitedDeck) {

        $suitedDeck = $suitedDeck;
        $sortedArray = [];

        foreach ($suitedDeck as $card) {
            $val = $card->getValue();
            $sortedArray[$val - 1] = $card;
        }

        return $sortedArray;
    }
    
}
