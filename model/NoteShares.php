<?php

require_once "framework/Model.php";

class NoteShares extends Model
{
    public int $user;
    public int $note;
    public bool $editor;
    public ?int $id;

    public function __construct(
        int $user,
        int $note,
        bool $editor = false,
        ?int $id = null
    ) {
        $this->user = $user;
        $this->note = $note;
        $this->editor = $editor;
        $this->id = $id;
    }
    // Getters
    public function getUser(): int
    {
        return $this->user;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getEditor(): bool
    {
        return $this->editor;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // Setters
    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    public function setNote(int $note): void
    {
        $this->note = $note;
    }

    public function setEditor(bool $editor): void
    {
        $this->editor = $editor;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public static function getSharedNotesByRolesRead (int $idOwner , int $idUser):array {
        $query = self::execute("SELECT DISTINCT note_shares.note
                                from note_shares
                                join notes on notes.id = note_shares.note 
                                join users on users.id = notes.owner
                                where editor = 0 
                                and note_shares.user =:iduser
                                and users.id = :idowner" , ["iduser"=>$idUser, "idowner" => $idOwner]);
        
        $data = $query->fetchAll();
        $result = [];
        foreach($data as $row){
            $result[] = Note::get_note_by_id($row["note"]);
        }
        return $result;
    }

    public static function getSharedNotesByRolesEdit (int $idOwner , int $idUser):array {
        $query = self::execute("SELECT DISTINCT note_shares.note
                                from note_shares
                                join notes on notes.id = note_shares.note 
                                join users on users.id = notes.owner
                                where editor = 1
                                and note_shares.user =:iduser
                                and users.id = :idowner" , ["iduser"=>$idUser, "idowner" => $idOwner]);
        
        $data = $query->fetchAll();
        $result = [];
        foreach($data as $row){
            $result[] = Note::get_note_by_id($row["note"]);
        }
        return $result;
    }
}
