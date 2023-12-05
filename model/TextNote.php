<?php
require_once "Note.php";

class TextNote extends Note {
    public string $content;

    public function __construct(
        string $title,
        int $owner,
        bool $pinned,
        bool $archived,
        float $weight,
        string $content,
        ?int $id = null,
        ?DateTime $created_at = null,
        ?DateTime $edited_at = null
    ) {
        parent::__construct(
            title: $title,
            owner: $owner,
            pinned: $pinned,
            archived: $archived,
            weight: $weight,
            id: $id,
            created_at: $created_at,
            edited_at: $edited_at
        );

        $this->content = $content;
    }

    // function isPinned and not archived pour ne pas montrer  les notes archivés meme si pinned
    public function isPinned() : bool {
        return $this->pinned && !$this->archived;
    }


    public function isArchived() : bool {
        return $this->archived;
    }



    


    // You may add additional methods that are specific to a TextNote here.
}
