<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'model/User.php';
require_once 'model/TextNote.php';
require_once 'model/Note.php';
require_once 'model/CheckListNote.php';
require_once 'model/CheckListNoteItem.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerNotes extends Controller {
    public function index(): void {
        $user = $this->get_user_or_redirect();
        $allNotes = Note::get_notes_by_owner($user->get_id());

        // Separate pinned and other notes
        $pinnedNotes = array_filter($allNotes, function($note) {
            return $note->isPinned();
        });

        $otherNotes = array_filter($allNotes, function($note) {
            return !$note->isPinned();
        });

        // Pass separated notes to the view
        (new View("notes"))->show([
            "user" => $user,
            "pinnedNotes" => $pinnedNotes,
            "otherNotes" => $otherNotes
        ]);
    }  
    
    public function moveNoteRight() {
        $noteId = $_POST['noteId'] ?? null;
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                $note->moveNotesRight(); // No need to check the return value
            }
        }
        $this->redirect("notes");
    }
    
    public function moveNoteLeft() {
        $noteId = $_POST['noteId'] ?? null;
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                $note->moveNotesLeft(); // No need to check the return value
            }
        }
        $this->redirect("notes");
    }
    
    public function add_checklistnote(): void {
        $user = $this->get_user_or_redirect();
        $title = $_POST['title'] ?? null;
        $items = $_POST['items'] ?? null;
        $items = explode("\n", $items);
        $items = array_filter($items, function($item) {
            return !empty($item);
        });
        $items = array_map(function($item) {
            return trim($item);
        }, $items);
        $note = new CheckListNote();
        $note->set_title($title);
        $note->set_owner_id($user->get_id());
        $note->save();
        foreach ($items as $item) {
            $noteItem = new CheckListNoteItem();
            $noteItem->set_text($item);
            $noteItem->set_note_id($note->get_id());
            $noteItem->save();
        }
        $this->redirect("notes");
    }

    public function add_textnote(): void {
        $user = $this->get_user_or_redirect();
        $title = $_POST['title'] ?? null;
        $text = $_POST['text'] ?? null;
        $note = new TextNote();
        $note->set_title($title);
        $note->set_text($text);
        $note->set_owner_id($user->get_id());
        $note->save();
        $this->redirect("notes");
    }

    public function show_note(): void {
    
        $user = $this->get_user_or_redirect();
    
        // Retrieve 'param1' from the URL, which should contain the note ID
        $noteId = isset($_GET['param1']) && $_GET['param1'] !== '' ? $_GET['param1'] : null;
    
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                if ($note instanceof TextNote) {
                    (new View("open_textnote"))->show(["user" => $user, "note" => $note]);
                } else {
                    (new View("note"))->show(["user" => $user, "note" => $note]);
                }
                return;
            }
        }
        $this->redirect("notes");
    }
    

    
    // Controller for archived notes, same as index but with archived notes
    public function archives(): void {
        $user = $this->get_user_or_redirect();
        $notes = Note::get_notes_by_owner($user->get_id());
        (new View("archives"))->show(["user" => $user, "notes" => $notes]);
    }
}
