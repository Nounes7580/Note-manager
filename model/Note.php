<?php

require_once "framework/Model.php";

abstract class Note extends Model {
    public string $title;
    public int $owner;
    public DateTime $created_at;
    public ?DateTime $edited_at; // Make it nullable
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
        ?DateTime $edited_at = null // Allow $edited_at to be null
    ) {
        $this->title = $title;
        $this->owner = $owner;
        $this->pinned = $pinned;
        $this->archived = $archived;
        $this->weight = $weight;
        $this->id = $id;
        $this->created_at = $created_at ?: new DateTime(); // Assign current time if null
        $this->edited_at = $edited_at; // Allow null
    }
    

    public function moveNotesRight(): bool {
        $this->weight += 1;
        $this->edited_at = new DateTime();
        $this->persist();
        return true;  // Assuming persist always succeeds, adjust based on your error handling
    }
    
    public function moveNotesLeft(): bool {
        $this->weight -= 1;
        $this->edited_at = new DateTime();
        $this->persist();
        return true;  // Assuming persist always succeeds, adjust based on your error handling
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
    public function isPinned(): bool {
        return $this->pinned && !$this->archived;
    }

    public function isArchived(): bool {
        return $this->archived;
    }


    public static function get_note_by_id(int $id): ?Note {
        try {
            $sql = 'SELECT * FROM notes WHERE id = :id';
            $stmt = self::execute($sql, ['id' => $id]);
            $row = $stmt->fetch();
            if ($row === false) {
                return null;
            }
        
            $created_at = new DateTime($row['created_at']);
            $edited_at = new DateTime($row['edited_at']);
        
            // Check if the note is a checklist note
            if (isset($row['checklist_id'])) {
                return new CheckListNote(
                    title: $row['title'],
                    owner: $row['owner'],
                    pinned: $row['pinned'],
                    archived: $row['archived'],
                    weight: $row['weight'],
                    id: $row['id'],
                    created_at: $created_at,
                    edited_at: $edited_at
                );
            } else {
                $content = $row['content'] ?? '';
                return new TextNote(
                    title: $row['title'],
                    owner: $row['owner'],
                    pinned: $row['pinned'],
                    archived: $row['archived'],
                    weight: $row['weight'],
                    content: $content, // Make sure this is named as well
                    id: $row['id'],
                    created_at: $created_at,
                    edited_at: $edited_at
                );
            }
            
        } catch (PDOException $e) {
            error_log('PDOException in get_note_by_id: ' . $e->getMessage());
            return null;
        }
    }

    public function validate(): array {
        $errors = [];
        // Add validation logic here. For example:
        if (strlen($this->title) < 3 || strlen($this->title) > 25) {
            $errors[] = "Title must be between 3 and 25 characters long.";
        }
        // Add more validation as needed...

        return $errors;
    }

    public function persist(): Note {
        if ($this->id) {
            $stmt = self::execute("UPDATE notes SET title = :title, owner = :owner, pinned = :pinned, archived = :archived, weight = :weight, edited_at = :edited_at WHERE id = :id",
                ["id" => $this->id, "title" => $this->title, "owner" => $this->owner, "pinned" => $this->pinned, "archived" => $this->archived, "weight" => $this->weight, "edited_at" => $this->edited_at->format('Y-m-d H:i:s')]);
            error_log("Updated rows: " . $stmt->rowCount());  // Log the number of updated rows
        } else {
            // Handle insert scenario if needed
        }
        return $this;
    }
    



    public static function get_notes_by_owner(int $owner_id): array {
        $notes = [];
        try {
            // The SQL query now joins the notes table with the text_notes and checklist_notes tables.
            $sql = 'SELECT n.*, t.content AS text_content, c.id AS checklist_id 
                    FROM notes n 
                    LEFT JOIN text_notes t ON n.id = t.id 
                    LEFT JOIN checklist_notes c ON n.id = c.id 
                    WHERE n.owner = :owner_id
                    ORDER BY n.weight';

            $stmt = self::execute($sql, ['owner_id' => $owner_id]);
            while ($row = $stmt->fetch()) {
                $created_at = $row['created_at'] !== null ? new DateTime($row['created_at']) : null;
                $edited_at = $row['edited_at'] !== null ? new DateTime($row['edited_at']) : null;
    
                // Determine if it's a text note or a checklist note based on the presence of content or checklist_id.
                // Create a TextNote even if the content is null.
                if (isset($row['text_content']) || $row['checklist_id'] === null) {
                    $content = isset($row['text_content']) ? $row['text_content'] : ''; // Use an empty string if content is null
                    $notes[] = new TextNote(
                        title: $row['title'],
                        owner: $row['owner'],
                        pinned: $row['pinned'],
                        archived: $row['archived'],
                        weight: $row['weight'],
                        content: $content, // Pass the content, which may be an empty string
                        id: $row['id'],
                        created_at: $created_at,
                        edited_at: $edited_at
                    );
                } 
                // Separate condition for checklist notes to make the distinction clear.
                if ($row['checklist_id'] !== null) {
                    $notes[] = new CheckListNote(
                        title: $row['title'],
                        owner: $row['owner'],
                        pinned: $row['pinned'],
                        archived: $row['archived'],
                        weight: $row['weight'],
                        id: $row['id'],
                        created_at: $created_at,
                        edited_at: $edited_at
                    );
                }
            }
        } catch (PDOException $e) {
            error_log('PDOException in get_notes_by_owner: ' . $e->getMessage());
        }
        return $notes;
    }
    
    
}
