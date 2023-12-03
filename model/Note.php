<?php

require_once "framework/Model.php";

abstract class Note extends Model {
    public string $title;
    public int $owner;
    public DateTime $created_at;
    public DateTime $edited_at;
    public bool $pinned;
    public bool $archived;
    public float $weight;
    public ?int $id;

    public function __construct(
        string $title,
        int $owner,
        bool $pinned,
        bool $archived,
        float $weight,
        ?int $id = null,
        ?DateTime $created_at = null,
        ?DateTime $edited_at = null
    ) {
        $this->title = $this->validateTitle($title);
        // Assume $owner is validated elsewhere, such as in the controller, to ensure it matches a valid user.
        $this->owner = $owner;
        $this->pinned = $pinned;
        $this->archived = $archived;
        $this->weight = $this->validateWeight($weight);
        $this->id = $id;
        $this->created_at = $created_at ?: new DateTime();
        $this->edited_at = $edited_at ?: new DateTime();
    }

    private function validateTitle(string $title): string {
        if (strlen($title) < 3 || strlen($title) > 25) {
            throw new InvalidArgumentException('Title must be between 3 and 25 characters long.');
        }
        return $title;
    }

    private function validateWeight(float $weight): float {
        if ($weight <= 0) {
            throw new InvalidArgumentException('Weight must be strictly positive.');
        }
        return $weight;
    }

}

