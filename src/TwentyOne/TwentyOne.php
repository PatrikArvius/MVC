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
    /** @var bool $allStand */
    private $allStand = false;

    private string $winner = "";
    private int $playerHandValue = 0;
    private int $altPlayerHandValue = 0;
    private int $dealerHandValue = 0;
    private int $altDealerHandValue = 0;

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

    /** @return int|null */
    public function getSpecificHandValue(string $hand): ?int
    {
        $val = null;
        switch ($hand) {
            case "player":
                $val = $this->playerHandValue;
                break;
            case "altPlayer":
                $val = $this->altPlayerHandValue;
                break;
            case "dealer":
                $val = $this->dealerHandValue;
                break;
            case "altDealer":
                $val = $this->altDealerHandValue;
                break;
        }
        return $val;
    }

    public function getPlayer(): ?object
    {
        return $this->player;
    }

    public function getDealer(): ?object
    {
        return $this->dealer;
    }

    public function compareHands(): void
    {
        $playerVal = 0;
        $dealerVal = 0;

        $playerVal = $this->playerHandValue;

        //Sets the best valid player hand value
        if ($this->altPlayerHandValue < 21 && ($this->altPlayerHandValue > $this->playerHandValue)) {
            $playerVal = $this->altPlayerHandValue;
        }

        $dealerVal = $this->dealerHandValue;

        //Sets the best valid dealer hand value
        if ($this->altDealerHandValue <= 21 && ($this->altDealerHandValue > $this->dealerHandValue)) {
            $dealerVal = $this->altDealerHandValue;
        }

        if ($dealerVal <= 21 && ($dealerVal >= $playerVal)) {
            $this->winner = "dealer";
            return;
        }

        $this->winner = "player";
    }

    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
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

        $this->allStand = true;
        $this->compareHands();
    }

    public function getAllStanding(): bool
    {
        return $this->allStand;
    }

    public function checkPlayer(): void
    {
        if ($this->playerHandValue === 21 || $this->altPlayerHandValue === 21) {
            $this->setWinner("player");
        }
        if ($this->playerHandValue > 21 && $this->altPlayerHandValue > 21) {
            $this->setWinner("dealer");
        }
    }

    public function checkDealer(): void
    {
        if ($this->dealerHandValue === 21 || $this->altDealerHandValue === 21) {
            $this->setWinner("dealer");
        }
        if ($this->dealerHandValue > 21 && $this->altDealerHandValue > 21) {
            $this->setWinner("player");
        }
    }

    public function checkGameEnd(): void
    {
        if ($this->playerIsActive) {
            $this->checkPlayer();
            return;
        }
        $this->checkDealer();
    }

    public function simmulateOpponent(): void
    {
        if ($this->dealerHandValue <= 16 || $this->altDealerHandValue <= 16) {
            $this->playRound();
        }
        $this->stand();
    }

    public function playRound(): void
    {
        $this->drawCard();
        $this->setHandValues();
        $this->checkGameEnd();

        if (!$this->playerIsActive && $this->winner === "") {
            $this->simmulateOpponent();
        }
    }
}
