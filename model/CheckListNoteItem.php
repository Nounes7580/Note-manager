<?php
require_once "framework/Model.php";

class CheckListNoteItem extends Model {
    public int $checklist_note_id;
    public string $content;
    public bool $checked;
    public ?int $id;

    public function __construct(
        int $checklist_note_id,
        string $content,
        bool $checked,
        ?int $id = null
    ) {
        $this->checklist_note_id = $checklist_note_id;
        $this->content = $this->validateContent($content);
        $this->checked = $checked;
        $this->id = $id;
    }

    private function validateContent(string $content): string {
        if (strlen($content) < 1 || strlen($content) > 60) {
            throw new InvalidArgumentException('Content must be between 1 and 60 characters long.');
        }
        return $content;
    }
    public function toggleChecked() {
        $this->checked = !$this->checked;
    }

    public static  function get_item_by_id(int $id): ?CheckListNoteItem {
        $sql = 'SELECT * FROM checklist_note_items WHERE id = :id';
        $stmt = self::execute($sql, ['id' => $id]);
        if ($stmt && $row = $stmt->fetch()) {
            return new CheckListNoteItem(
                checklist_note_id: $row['checklist_note'],
                content: $row['content'],
                checked: (bool)$row['checked'], // Cast to boolean
                id: $row['id']
            );
        }
        return null;
    }
    

    public function save() {
        $sql = 'INSERT INTO checklist_note_items (checklist_note, content, checked) 
                VALUES (:checklist_note_id, :content, :checked)';
        self::execute($sql, [
            'checklist_note_id' => $this->checklist_note_id,
            'content' => $this->content,
            'checked' => $this->checked,
        ]);
        $this->id = self::connect()->lastInsertId();
    }

    public function update() {
        $sql = 'UPDATE checklist_note_items SET content = :content, checked = :checked WHERE id = :id';
        self::execute($sql, [
            'id' => $this->id,
            'content' => $this->content,
            'checked' => $this->checked ? 1 : 0, // Convert to integer
        ]);
    }
        
    public function persist() {
        // Convert boolean value to integer
        $checkedInt = $this->checked ? 1 : 0;

        if ($this->id) {
            // Update existing checklist note item
            $sql = 'UPDATE checklist_note_items SET checklist_note = :checklist_note_id, content = :content, checked = :checked WHERE id = :id';
            $stmt = self::execute($sql, [
                'id' => $this->id,
                'checklist_note_id' => $this->checklist_note_id,
                'content' => $this->content,
                'checked' => $checkedInt
            ]);
            error_log("Updated checklist note item rows: " . $stmt->rowCount());
        } else {
            // Insert new checklist note item
            $sql = 'INSERT INTO checklist_note_items (checklist_note, content, checked) VALUES (:checklist_note_id, :content, :checked)';
            $stmt = self::execute($sql, [
                'checklist_note_id' => $this->checklist_note_id,
                'content' => $this->content,
                'checked' => $checkedInt
            ]);
            $this->id = self::connect()->lastInsertId(); // Set the ID of the new checklist note item
            error_log("Inserted new checklist note item with ID: " . $this->id);
        }
    }


    // Additional CheckListNoteItem-specific methods...
}
