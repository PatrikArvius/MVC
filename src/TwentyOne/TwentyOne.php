<?php

namespace App\TwentyOne;

use App\Card\DeckOfCards;
use App\TwentyOne\Player;
use App\TwentyOne\Dealer;

// This class handles most of the logic in order to play the game 21
class TwentyOne
{
    /** @var Player $player */
    private Player $player;
    /** @var DeckofCards $deck */
    private DeckOfCards $deck;
    /** @var Dealer $dealer */
    private Dealer $dealer;
    /** @var bool $playerIsActive */
    private $playerIsActive = true;

    private string $winner = "";
    private int $playerHandValue;
    private int $altPlayerHandValue;
    private int $dealerHandValue;
    private int $altDealerHandValue;

    public function __construct(Player $player, Dealer $dealer, DeckOfCards $deck)
    {
        $this->player = $player;
        $this->dealer = $dealer;
        $this->deck = $deck;
        $this->deck->shuffleDeck();
    }

    public function setHandValues(): void
    {
        if ($this->playerIsActive) {
            $playerValues = $this->player->getHandValues();
            $this->playerHandValue = $playerValues['value'];
            $this->altPlayerHandValue = $playerValues['altValue'];
        }

        $dealerValues = $this->dealer->getHandValues();
        $this->dealerHandValue = $dealerValues['value'];
        $this->altDealerHandValue = $dealerValues['altValue'];
    }

    public function compareHands(): void
    {
        $playerVal = 0;
        $dealerVal = 0;

        $playerVal = $this->playerHandValue;

        //Sets the best valid player hand value
        if ($this->altPlayerHandValue < 21 && $this->altPlayerHandValue > $this->playerHandValue) {
            $playerVal = $this->altPlayerHandValue;
        }

        $dealerVal = $this->dealerHandValue;

        //Sets the best valid dealer hand value
        if ($this->altDealerHandValue < 21 && $this->altDealerHandValue > $this->dealerHandValue) {
            $dealerVal = $this->altDealerHandValue;
        }

        if ($dealerVal >= $playerVal) {
            $this->winner = "dealer";
        }

        $this->winner = "player";
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function drawCard(): void
    {
        if ($this->playerIsActive) {
            $card = $this->deck->drawCard();
            $this->player->add($card[0]);
            return;
        }

        $card = $this->deck->drawCard();
        $this->dealer->add($card[0]);
    }

    public function stand(): void
    {
        if ($this->playerIsActive) {
            $this->playerIsActive = false;
            return;
        }

        $this->compareHands();
    }
}
