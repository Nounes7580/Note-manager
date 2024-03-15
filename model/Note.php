<?php

require_once "framework/Model.php";

abstract class Note extends Model
{
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

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function togglePinned(): void
    {
        $this->pinned = !$this->pinned;
        $this->edited_at = new DateTime(); // Update edited_at timestamp


    }

    public function toggleArchived(): void
    {
        $this->archived = !$this->archived;
        $this->edited_at = new DateTime(); // Update edited_at timestamp

    }

    public static function createFromRow($row): ?Note
    {
        if ($row === false) {
            return null;
        }

        // Assuming $row['type'] can be 'text' or 'checklist' to differentiate the note types
        $created_at = isset($row['created_at']) ? new DateTime($row['created_at']) : new DateTime();
        $edited_at = isset($row['edited_at']) && $row['edited_at'] ? new DateTime($row['edited_at']) : null;

        if (isset($row['checklist_id'])) {
            // Return a CheckListNote instance
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
            // Return a TextNote instance
            $content = $row['content'] ?? '';
            return new TextNote(
                title: $row['title'],
                owner: $row['owner'],
                pinned: $row['pinned'],
                archived: $row['archived'],
                weight: $row['weight'],
                content: $content,
                id: $row['id'],
                created_at: $created_at,
                edited_at: $edited_at
            );
        }
    }
    public function get__id(): int
    {
        return $this->id;
    }

    public function save(): void
    {
        try {
        } catch (PDOException $e) {
            // Gérez les erreurs de la base de données ici (par exemple, enregistrez l'erreur)
            error_log('PDOException dans save : ' . $e->getMessage());
        }
    }

    public function updateNoteWeight(Note $note)
    {
        $sql = 'UPDATE notes SET weight = :weight WHERE id = :id';
        $stmt = self::execute($sql, ['weight' => $note->getWeight(), 'id' => $note->get__id()]);
        error_log("Updated rows: " . $stmt->rowCount());  // Log the number of updated rows
    }
    public function moveNotesRight(): bool
    {
        $nextNote = $this->getNextNote();
        if ($nextNote) {
            $this->swapNotes($this, $nextNote);
            return true;
        }
        return false;
    }



    public function moveNotesLeft(): bool
    {
        $previousNote = $this->getPreviousNote();
        if ($previousNote) {
            $this->swapNotes($this, $previousNote);
            return true;
        }
        return false;
    }


    public function getNextNote(): ?Note
    {
        try {
            $sql = 'SELECT * FROM notes WHERE weight > :weight AND owner = :owner AND pinned = :pinned AND archived = :archived ORDER BY weight ASC LIMIT 1';
            $stmt = self::execute($sql, [
                'weight' => $this->weight,
                'owner' => $this->owner,
                'pinned' => $this->pinned,
                'archived' => $this->archived
            ]);
            $row = $stmt->fetch();
            return $row ? Note::createFromRow($row) : null;
        } catch (PDOException $e) {
            error_log('PDOException in getNextNote: ' . $e->getMessage());
            return null;
        }
    }

    public function getPreviousNote(): ?Note
    {
        try {
            $sql = 'SELECT * FROM notes WHERE weight < :weight AND owner = :owner AND pinned = :pinned AND archived = :archived ORDER BY weight DESC LIMIT 1';
            $stmt = self::execute($sql, [
                'weight' => $this->weight,
                'owner' => $this->owner,
                'pinned' => $this->pinned,
                'archived' => $this->archived
            ]);
            $row = $stmt->fetch();
            return $row ? Note::createFromRow($row) : null;
        } catch (PDOException $e) {
            error_log('PDOException in getPreviousNote: ' . $e->getMessage());
            return null;
        }
    }


    private function swapNotes(Note $note1, Note $note2): void
    {
        // Swap the weights of the two notes
        $tempWeight = $note1->weight;
        $note1->weight = $note2->weight;
        $note2->weight = $tempWeight;

        // Update the timestamps
        $note1->edited_at = new DateTime();
        $note2->edited_at = new DateTime();

        // Persist changes to the database
        $note1->persist();
        $note2->persist();
    }



    private function validateTitle(string $title): string
    {
        if (strlen($title) < 3 || strlen($title) > 25) {
            throw new InvalidArgumentException('Title must be between 3 and 25 characters long.');
        }
        return $title;
    }

    private function validateWeight(float $weight): float
    {
        if ($weight <= 0) {
            throw new InvalidArgumentException('Weight must be strictly positive.');
        }
        return $weight;
    }
    public function isPinned(): bool
    {
        return $this->pinned && !$this->archived;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public static function updateEditedAt(int $noteId) {
        $editedAt = new DateTime();
        $sql = 'UPDATE notes SET edited_at = :edited_at WHERE id = :id';
        self::execute($sql, [
            'id' => $noteId,
            'edited_at' => $editedAt->format('Y-m-d H:i:s'),
        ]);
    }
    

    public static function get_note_by_id(int $id): ?Note
    {
        try {
            $sql = 'SELECT n.*, tn.content AS text_content, cn.id AS checklist_id
            FROM notes n
            LEFT JOIN text_notes tn ON n.id = tn.id
            LEFT JOIN checklist_notes cn ON n.id = cn.id
            WHERE n.id = :id';

            $stmt = self::execute($sql, ['id' => $id]);
            $row = $stmt->fetch();
            if ($row === false) {
                return null;
            }

            // Initialisation de $created_at
            $created_at = isset($row['created_at']) ? new DateTime($row['created_at']) : new DateTime();

            // Initialisation de $edited_at
            $edited_at = isset($row['edited_at']) && $row['edited_at'] ? new DateTime($row['edited_at']) : null;

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
                $content = $row['text_content'] ?? '';
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

    public static function get_highest_weight_by_owner(int $owner_id): float
    {
        try {
            $sql = 'SELECT MAX(weight) AS max_weight FROM notes WHERE owner = :owner_id';
            $stmt = self::execute($sql, ['owner_id' => $owner_id]);
            $row = $stmt->fetch();
            return $row['max_weight'] ?? 0.0;
        } catch (PDOException $e) {
            error_log('PDOException in get_highest_weight_by_owner: ' . $e->getMessage());
            return 0.0;
        }
    }

    public function validate(): array
    {
        $errors = [];
        // Add validation logic here. For example:
        if (strlen($this->title) < 3 || strlen($this->title) > 25) {
            $errors[] = "Title must be between 3 and 25 characters long.";
        }
        // Add more validation as needed...

        return $errors;
    }

    public function persist(): Note
    {
        if ($this->id) {
            // Convert boolean values to integers
            $pinnedInt = $this->pinned ? 1 : 0;
            $archivedInt = $this->archived ? 1 : 0;


            $stmt = self::execute(
                "UPDATE notes SET title = :title, owner = :owner, pinned = :pinned, archived = :archived, weight = :weight, edited_at = :edited_at WHERE id = :id",
                [
                    "id" => $this->id,
                    "title" => $this->title,
                    "owner" => $this->owner,
                    "pinned" => $pinnedInt,
                    "archived" => $archivedInt,
                    "weight" => $this->weight,
                    "edited_at" => $this->edited_at ? $this->edited_at->format('Y-m-d H:i:s') : null
                ]
            );
            error_log("Updated rows: " . $stmt->rowCount());  // Log the number of updated rows
        } else {
            self::execute("INSERT INTO notes (title, owner, pinned, archived, weight, created_at) VALUES (:title, :owner, :pinned, :archived, :weight, :created_at)", [
                "title" => $this->title,
                "owner" => $this->owner,
                "pinned" => $this->pinned ? 1 : 0,
                "archived" => $this->archived ? 1 : 0,
                "weight" => $this->weight,
                "created_at" => $this->created_at->format('Y-m-d H:i:s')
            ]);
            $this->id = self::execute("SELECT LAST_INSERT_ID()", [])->fetchColumn();
        }
        return $this;
    }
    public function persistAdd(): Note
    {
        if ($this->id) {
            // Convert boolean values to integers
            $pinnedInt = $this->pinned ? 1 : 0;
            $archivedInt = $this->archived ? 1 : 0;


            $stmt = self::execute(
                "UPDATE notes SET title = :title, owner = :owner, pinned = :pinned, archived = :archived, weight = :weight, edited_at = :edited_at WHERE id = :id",
                [
                    "id" => $this->id,
                    "title" => $this->title,
                    "owner" => $this->owner,
                    "pinned" => $pinnedInt,
                    "archived" => $archivedInt,
                    "weight" => $this->weight,
                    "edited_at" => $this->edited_at->format('Y-m-d H:i:s')
                ]
            );
            error_log("Updated rows: " . $stmt->rowCount());  // Log the number of updated rows
        } else {
            self::execute("INSERT INTO notes (title, owner, pinned, archived, weight, created_at) VALUES (:title, :owner, :pinned, :archived, :weight, :created_at)", [
                "title" => $this->title,
                "owner" => $this->owner,
                "pinned" => $this->pinned ? 1 : 0,
                "archived" => $this->archived ? 1 : 0,
                "weight" => $this->weight,
                "created_at" => $this->created_at->format('Y-m-d H:i:s')
            ]);
            $this->id = self::execute("SELECT LAST_INSERT_ID()", [])->fetchColumn();
        }
        return $this;
    }



    public static function get_notes_by_owner(int $owner_id): array
    {
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
    public function delete(): void
    {
        try {
            if ($this->id === null) {
                throw new Exception("L'ID de la note est requis pour la suppression.");
            }

            // Supprimer les éléments de checklist associés à la note
            $sql = 'DELETE FROM checklist_note_items WHERE checklist_note = :id';
            self::execute($sql, ['id' => $this->id]);

            // Supprimer la note de checklist (si elle existe)
            $sql = 'DELETE FROM checklist_notes WHERE id = :id';
            self::execute($sql, ['id' => $this->id]);

            // Supprimer d'autres enregistrements liés (text_notes, note_shares, etc.)
            $sql = 'DELETE FROM text_notes WHERE id = :id';
            self::execute($sql, ['id' => $this->id]);

            $sql = 'DELETE FROM note_shares WHERE note = :id';
            self::execute($sql, ['id' => $this->id]);

            // Enfin, supprimer la note principale
            $sql = 'DELETE FROM notes WHERE id = :id';
            self::execute($sql, ['id' => $this->id]);

            $this->id = null;
        } catch (PDOException $e) {
            error_log('PDOException dans delete: ' . $e->getMessage());
            throw $e;
        }
    }
    public static function getSharedNotesDetails($owner_id, $user_id): ?array
    {
        $query = self::execute(
            "
        SELECT n.id AS note_id
        FROM notes n
        INNER JOIN note_shares ns1 ON n.id = ns1.note
        INNER JOIN note_shares ns2 ON ns1.note = ns2.note
        WHERE n.owner = :owner_id AND ns2.user = :user_id",
            ["owner_id" => $owner_id, "user_id" => $user_id]
        );

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        return !empty($results) ? $results : null;
    }
    public static function getSharedNotesByUser(int $currentUserId): array
    {
        $users = [];
        $query = self::execute("SELECT DISTINCT users.id, users.mail,
                                users.hashed_password , users.full_name, users.role
                                from note_shares
                                join notes on notes.id = note_shares.note 
                                join users on users.id = notes.owner
                                where note_shares.user = :id", ["id" => $currentUserId]);
        $data = $query->fetchAll();
        foreach ($data as $row) {
            $users[] = new User($row["mail"], $row["hashed_password"], $row["full_name"], $row["role"],  $row["id"]);
        }
        return $users;
    }

    public  function getUsersWhoSharedWith(): array
    {
        $query = self::execute("SELECT user FROM note_shares WHERE note = :id", ["id" => $this->id]);
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $row) {
            $result[] = User::get_user_by_id($row["user"]);
        }
        usort($result, function ($a, $b) {
            return strcmp($a->full_name, $b->full_name);
        });
        return $result;
    }
    public function isSharedWithPermission(int $userId): bool
    {
        $query = self::execute("SELECT editor FROM note_shares WHERE note = :noteId AND user = :userId", ["noteId" => $this->id, "userId" => $userId]);
        $data = $query->fetch();

        return $data[0];
    }
}