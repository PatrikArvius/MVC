<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /** @var array<int, Card> $hand */
    private array $hand = [];

    public function add(Card $card): void
    {
        $this->hand[] = $card;
    }

    public function getNumberCards(): int
    {
        return count($this->hand);
    }

    /** @return array<int, int|null> */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    /** @return array<int, string|null> */
    public function getString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->getAsString();
        }
        return $values;
    }
}
