<?php
declare(strict_types=1);

namespace SpotifyTest\Domain\Foundation\Entity;

abstract class Entity implements EntityInterface
{
    /**
     * @var EntityIdInterface
     */
    protected $entity_id;

    /**
     * @var array
     */
    protected $entity_data;

    public function __construct(EntityIdInterface $id, $data = [])
    {
        $this->setEntityId($id);
        $this->setEntityData($data);
    }

    public function getEntityId() : EntityIdInterface
    {
        return $this->entity_id;
    }

    public function getId()
    {
        return $this->entity_id->getId();
    }

    protected function setEntityId(EntityIdInterface $entity_id)
    {
        $this->entity_id = $entity_id;

        return $this;
    }

    protected function setEntityData(array $data)
    {
        $this->entity_data = $data;
    }

    protected function getDataAttribute(string $attribute, $default_value = null)
    {
        return !empty($this->entity_data[$attribute]) ? $this->entity_data[$attribute] : $default_value;
    }

    public function toArray() : array {
        return [
            'id'=> $this->getId(),
            'data' => $this->data,
        ];
    }

}