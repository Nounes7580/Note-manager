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

    public function get_id() : int {
        return $this->id;
    }



//persist method as required by the abstract parent class. in order to save the content of the note
    public function persist(): TextNote {
        parent::persist(); // First, call parent's persist method
        // Now handle the saving of TextNote specific fields
        try {
            $sql = 'INSERT INTO text_notes (note, content) VALUES (:note, :content)';
            self::execute($sql, ['note' => $this->id, 'content' => $this->content]);
        } catch (PDOException $e) {
            // Log error message
            error_log('PDOException in persist: ' . $e->getMessage());
        }
        return $this;
    }
    
}
