<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Model\Album;

use SpotifyTest\Domain\Foundation\Entity\Entity;
use SpotifyTest\Domain\Foundation\Entity\EntityIdInterface;
use SpotifyTest\Domain\Model\EntityType;

class Album extends Entity
{
    public function __construct(EntityIdInterface $id, $data = [])
    {
        parent::__construct($id, $data);
    }

    public function getType(): string
    {
        return EntityType::ALBUM;
    }

    public function toArray(): array
    {
        $cover_image = isset($this->entity_data['images'][0]) ? $this->entity_data['images'][0] : [];

        return [
            "name" => $this->getDataAttribute('name'),
            "released" => $this->getDataAttribute('release_date'),
            "tracks" => $this->getDataAttribute('total_tracks'),
            "cover" => [
                "height" => !empty($cover_image['height']) ? $cover_image['height'] : null,
                "width" => !empty($cover_image['width']) ? $cover_image['width'] : null,
                "url" => !empty($cover_image['url']) ? $cover_image['url'] : null,
            ]
        ];
    }
}