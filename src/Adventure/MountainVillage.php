<?php

namespace App\Adventure;

class MountainVillage extends Room
{
    protected string $name = "Mountain Village";
    protected string $image = "pixelmountain2.png";
    protected string $description = "You arrive at what appears to be a completely ordinary house but... who can ignore that backdrop! There seems to be a familiar beam of light at 
    the top of the mountain, one that is not too unlike that beam you so recklessly stepped into which got you into this entire mess in the first place. Perhaps there is someone in the 
    house that could tell you more or if fate has it, help you get home?";

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
        }
    }
}
