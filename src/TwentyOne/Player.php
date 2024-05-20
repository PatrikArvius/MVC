<?php

namespace App\TwentyOne;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;

class Player
{
    /** @var CardHand $hand */
    private CardHand $hand;

    public function __construct(CardHand $hand)
    {
        $this->hand = $hand;
    }

    /** @return array{'value': int, 'altValue': int} $handValues */
    public function getHandValues(): array
    {
        $values = $this->hand->getValues();
        $handValue = 0;
        $altHandValue = 0;
        $numAces = 0;

        foreach ($values as $value) {
            if ($numAces === 0 && $value === 1) {
                $numAces += 1;
                $handValue += $value;
                $altHandValue += 14;
                continue;
            }

            $handValue += $value;
            $altHandValue += $value;
        }

        $handValues = ['value' => $handValue, 'altValue' => $altHandValue];
        return $handValues;
    }

    /** @return array<int<0, max>, string|null> */
    public function getString(): ?array
    {
        $values = $this->hand->getString();

        return $values;
    }

    public function add(Card $card): void
    {
        $this->hand->add($card);
    }
}
