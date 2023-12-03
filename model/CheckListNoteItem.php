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
        $sql = 'UPDATE checklist_note_items SET content = :content, checked = :checked 
                WHERE id = :id';
        self::execute($sql, [
            'id' => $this->id,
            'content' => $this->content,
            'checked' => $this->checked,
        ]);
    }

    // Additional CheckListNoteItem-specific methods...
}
