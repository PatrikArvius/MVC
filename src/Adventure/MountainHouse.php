<?php

namespace App\Adventure;

class MountainHouse extends Room
{
    protected string $name = "House on top of the mountain";
    protected string $image = "houseonhill.png";
    protected string $description = "After a long trek up the mountain you finally arrive at a small house. At the house a man dressed in what can only be called wizards clothes stands looking around with a look of slight 
    confusion on his face. Man - 'Oh hello there stranger, are you perhaps looking to use this here beam? Tell you what, I'll help you use it safely if only you could help me in return, 
    you see... I've lost the key to my house and as you can undoubtedly tell the water for my tea is more than ready!";

    /**
     * @param array<int, Item> $items
     */
    public function __construct(array|null $items = null, bool|null $requiresItem = null, Item|null $requiredItem = null)
    {
        $this->items = [];

        if ($items != null) {
            $this->items = $items;
        }

        if ($requiresItem != false) {
            $this->requiresItem = $requiresItem;
            $this->requiredItem = $requiredItem;
            $this->locked = true;
        }
    }
}
