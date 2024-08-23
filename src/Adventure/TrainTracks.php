<?php

namespace App\Adventure;

class TrainTracks extends Room
{
    protected string $name = "Train tracks";
    protected string $image = "pixelmountain2.png";
    protected string $description = "You've entered the abandoned train station and end up at what was once a great hall of train tracks. Though there was a lightsource outside, 
    in this hall it is completely dark!";

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

    /** @return array<int, string> */
    public function exploreDescription(): array
    {
        $exits = $this->getAvailableConnectionsAsString();
        $expText = "You fumble around in the darkness and feel something small and cool to the touch. You can spot the silhouette of a locomotive but you require more light in order 
        to operate it.";

        if (count($this->items) > 0) {
            $item = $this->items[0]->getDescription();
            $expandedText = "You notice: $item";

            return [$expText, $expandedText, $exits];
        }
        return [$expText, $exits];
    }
}
