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
        
        // Set the property specific to TextNote.
        $this->content = $content;
    }

    // function isPinned and not archived pour ne pas montrer  les notes archivés meme si pinned
    public function isPinned() : bool {
        return $this->pinned && !$this->archived;
    }


    public function isArchived() : bool {
        return $this->archived;
    }

    // Implement the save method as required by the abstract parent class.
    public function save() {
        if (is_null($this->id)) {
            // If $id is null, this is a new TextNote, so we insert it
            $sql = 'INSERT INTO notes (title, owner, pinned, archived, weight, created_at, edited_at) 
                    VALUES (:title, :owner, :pinned, :archived, :weight, :created_at, :edited_at)';
            // Execute the query in the parent class
            $stmt = self::execute($sql, [
                'title' => $this->title,
                'owner' => $this->owner,
                'pinned' => $this->pinned,
                'archived' => $this->archived,
                'weight' => $this->weight,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'edited_at' => $this->edited_at->format('Y-m-d H:i:s')
            ]);
            $this->id = self::connect()->lastInsertId();

            $sql = 'INSERT INTO text_notes (id, content) VALUES (:id, :content)';
            self::execute($sql, ['id' => $this->id, 'content' => $this->content]);
        } else {
            // $id is not null, so we update the existing TextNote
            $this->update();
        }
    }

    public function update() {
        // Update the note details in the notes table
        $sql = 'UPDATE notes SET title = :title, owner = :owner, pinned = :pinned, archived = :archived, 
                weight = :weight, edited_at = :edited_at WHERE id = :id';
        self::execute($sql, [
            'id' => $this->id,
            'title' => $this->title,
            'owner' => $this->owner,
            'pinned' => $this->pinned,
            'archived' => $this->archived,
            'weight' => $this->weight,
            'edited_at' => $this->edited_at->format('Y-m-d H:i:s')
        ]);

        // Update the content in the text_notes table
        $sql = 'UPDATE text_notes SET content = :content WHERE id = :id';
        self::execute($sql, ['id' => $this->id, 'content' => $this->content]);
    }

    public static function get_notes_by_owner(int $owner) : array {
        $sql = 'SELECT n.*, tn.content FROM notes n LEFT JOIN text_notes tn ON n.id = tn.id WHERE n.owner = :owner';
        $stmt = self::execute($sql, ['owner' => $owner]);
        $notes = [];
        while ($row = $stmt->fetch()) {
            $createdAt = $row['created_at'] ? new DateTime($row['created_at']) : null;
            $editedAt = $row['edited_at'] ? new DateTime($row['edited_at']) : null;
            $notes[] = new TextNote(
                title: $row['title'],
                owner: $row['owner'],
                pinned: $row['pinned'],
                archived: $row['archived'],
                weight: $row['weight'],
                content: $row['content'] ?? '', // Use an empty string if 'content' is null
                id: $row['id'],
                created_at: $createdAt,
                edited_at: $editedAt
            );
        }
        return $notes;
    }


    // You may add additional methods that are specific to a TextNote here.
}
