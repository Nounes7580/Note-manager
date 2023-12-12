<?php
require_once "Note.php";

class CheckListNote extends Note {
    // Assuming no additional properties beyond those in Note

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
        // Call the parent constructor to set the common properties.
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
    }

    public function getItems(): array {
        $items = [];
        try {
            $sql = 'SELECT * FROM checklist_note_items WHERE checklist_note = :id';
            $stmt = self::execute($sql, ['id' => $this->id]);
            while ($row = $stmt->fetch()) {
                $items[] = new CheckListNoteItem(
                    checklist_note_id: $row['checklist_note'],
                    content: $row['content'],
                    checked: $row['checked'],
                    id: $row['id']
                );
            }
        } catch (PDOException $e) {
            // Log error message
            error_log('PDOException in getItems: ' . $e->getMessage());
        }
        return $items;
    }

    // Implement the save method as required by the abstract parent class.

    public function persist (): CheckListNote {
        if ($this->id) {
            self::execute(
                'UPDATE notes SET title = :title, owner = :owner, pinned = :pinned, archived = :archived, weight = :weight, edited_at = :edited_at WHERE id = :id',
                [
                    'title' => $this->title,
                    'owner' => $this->owner,
                    'pinned' => $this->pinned,
                    'archived' => $this->archived,
                    'weight' => $this->weight,
                    'edited_at' => $this->edited_at->format('Y-m-d H:i:s'),
                    'id' => $this->id
                ]
            );
        } else {
            self::execute(
                'INSERT INTO notes (title, owner, pinned, archived, weight) VALUES (:title, :owner, :pinned, :archived, :weight)',
                [
                    'title' => $this->title,
                    'owner' => $this->owner,
                    'pinned' => $this->pinned,
                    'archived' => $this->archived,
                    'weight' => $this->weight
                ]
            );
            $this->id = self::lastInsertId();
        }
        return $this;
    }
    

    // Additional CheckListNote-specific methods...
}
