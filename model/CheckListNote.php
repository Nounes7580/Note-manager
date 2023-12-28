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

    public function persist(): CheckListNote {
        parent::persist(); // First, call parent's persist method
        // Now handle the saving of CheckListNote specific fields
        return $this;
    }

    public function persistCheckListNote(): CheckListNote {
        parent::persist(); // First, call parent's persist method
        // Now handle the saving of CheckListNote specific fields
        try {
            $sql = 'INSERT INTO checklist_notes (note) VALUES (:note)';
            $stmt = self::execute($sql, ['note' => $this->id]);
            error_log("CheckListNote saved with ID: " . $this->id); // Confirmation que la note a été enregistrée
        } catch (PDOException $e) {
            error_log('PDOException in save: ' . $e->getMessage());
        }
        return $this;
    }

    

    // Additional CheckListNote-specific methods...
}
