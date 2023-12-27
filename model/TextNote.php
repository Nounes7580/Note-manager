<?php
require_once "Note.php";

class TextNote extends Note {
    public string $content;
    public static $db;

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

    public function get_id(): ?int {
        return $this->id;
    }

    public function save(): void {
        parent::save(); // Appel de la méthode save() de la classe parente
    
        error_log("Saving TextNote"); // Pour le débogage
    
        try {
            $sql = 'INSERT INTO text_notes (note, content) VALUES (:note, :content)';
            $stmt = self::execute($sql, ['note' => $this->id, 'content' => $this->content]);
            error_log("TextNote saved with ID: " . $this->id); // Confirmation que la note a été enregistrée
        } catch (PDOException $e) {
            error_log('PDOException in save: ' . $e->getMessage());
        }
    }



//persist method as required by the abstract parent class. in order to save the content of the note
public function persist(): TextNote {
    // Assurez-vous d'appeler la méthode persist() du parent si nécessaire
    parent::persist();

    try {
        // La requête ne devrait inclure que la colonne 'content'
        $sql = 'INSERT INTO text_notes (content) VALUES (:content)';
        // Exécutez la requête avec seulement la variable 'content' liée
        self::execute($sql, ['content' => $this->content]);

        // Si l'ID n'est pas déjà défini, récupérez-le de l'objet de connexion à la base de données
        if (!$this->id && self::$db) {
            $this->id = self::$db->lastInsertId();
        }
    } catch (PDOException $e) {
        // Log l'erreur pour le débogage
        error_log('PDOException in persist: ' . $e->getMessage());
    }

    // Retournez l'instance de l'objet pour permettre le chaînage des méthodes si nécessaire
    return $this;
}
    
}
