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
    public function save() {
        // Save the base Note properties first
        if (is_null($this->id)) {
            // Insert into the notes table and set the ID
            parent::save();  // Assuming the parent save handles the insertion to the notes table.
            // Now insert the specific CheckListNote record, using the ID from the notes table.
            $sql = 'INSERT INTO checklist_notes (id) VALUES (:id)';
            self::execute($sql, ['id' => $this->id]);
        } else {
            // If the note already exists, just call update
            $this->update();
        }
    }

    public function update() {
        // Update the base Note properties
        parent::update();  // Assuming the parent update handles the update to the notes table.
        // If there are additional properties to update in the checklist_notes table, handle them here
        // If not, no additional SQL query is needed since the checklist_notes table only holds the foreign key to notes table.
    }


    // Additional CheckListNote-specific methods...
}
