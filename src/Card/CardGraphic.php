<?php

namespace App\Card;

/**
 * This wil suppress UnusedPrivateField
 * 
 * @SuppressWarnings(PHPMD.UnusedPrivateField)
 */
class CardGraphic extends Card
{
    /** @var array<int, string> $spades */
    private array $spades = [
        '🂡',
        '🂢',
        '🂣',
        '🂤',
        '🂥',
        '🂦',
        '🂧',
        '🂨',
        '🂩',
        '🂪',
        '🂫',
        '🂭',
        '🂮',
    ];

    /** @var array<int, string> $hearts */
    private array $hearts = [
        '🂱',
        '🂲',
        '🂳',
        '🂴',
        '🂵',
        '🂶',
        '🂷',
        '🂸',
        '🂹',
        '🂺',
        '🂻',
        '🂽',
        '🂾',
    ];

    /** @var array<int, string> $diamonds */
    private array $diamonds = [
        '🃁',
        '🃂',
        '🃃',
        '🃄',
        '🃅',
        '🃆',
        '🃇',
        '🃈',
        '🃉',
        '🃊',
        '🃋',
        '🃍',
        '🃎',
    ];

    /** @var array<int, string> $clubs */
    private array $clubs = [
        '🃑',
        '🃒',
        '🃓',
        '🃔',
        '🃕',
        '🃖',
        '🃗',
        '🃘',
        '🃙',
        '🃚',
        '🃛',
        '🃝',
        '🃞',
    ];

    public function __construct(string $suit, int $num)
    {
        parent::__construct($suit, $num);
    }

    public function getAsString(): string
    {
        return $this->{$this->suit}[$this->value - 1];
    }
}
