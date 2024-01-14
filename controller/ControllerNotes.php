<?php
error_reporting(E_ALL);

ini_set('display_errors', 1);

require_once 'model/User.php';
require_once 'model/TextNote.php';
require_once 'model/Note.php';
require_once 'model/CheckListNote.php';
require_once 'model/CheckListNoteItem.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/NoteShares.php';

class ControllerNotes extends Controller
{
    public function index(): void
    {
        $user = $this->get_user_or_redirect();
        $allNotes = Note::get_notes_by_owner($user->get_id());
        $verif = Note::getSharedNotesByUser($user->get_id());

        // Separate pinned and other notes
        $pinnedNotes = array_filter($allNotes, function ($note) {
            return $note->isPinned();
        });

        $otherNotes = array_filter($allNotes, function ($note) {
            return !$note->isPinned();
        });

        // Pass separated notes to the view
        (new View("notes"))->show([
            "user" => $user,
            "pinnedNotes" => $pinnedNotes,
            "otherNotes" => $otherNotes,

            "sharedNotes" => $verif,
        ]);
    }

    public function moveNoteRight()
    {
        $noteId = $_POST['noteId'] ?? null;
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                $note->moveNotesRight(); // No need to check the return value
            }
        }
        $this->redirect("notes");
    }

    public function moveNoteLeft()
    {
        $noteId = $_POST['noteId'] ?? null;
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                $note->moveNotesLeft(); // No need to check the return value
            }
        }
        $this->redirect("notes");
    }

    public function add_checklistnote(): void
    {
        $user = $this->get_user_or_redirect();
        $title = $_POST['title'] ?? null;
        $items = $_POST['items'] ?? null;
        $items = explode("\n", $items);
        $items = array_filter($items, function ($item) {
            return !empty($item);
        });
        $items = array_map(function ($item) {
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
    public function add_textnote(): void
    {
        $user = $this->get_user_or_redirect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $highestWeight = Note::get_highest_weight_by_owner($user->get_id());

            $title = $_POST['title'] ?? 'Nouveau Titre';
            $text = $_POST['text'] ?? 'Nouveau Texte';
            $owner = $user->get_id();
            $pinned = false;
            $archived = false;
            $weight = $highestWeight + 1; // Augmenter le poids le plus élevé de 1.

            // Création de la nouvelle note.
            $note = new TextNote(
                title: $title,
                owner: $owner,
                pinned: $pinned,
                archived: $archived,
                weight: $weight,
                content: $text
            );

            // Persiste la note dans la base de données.
            $note->persistAdd();

            // Rediriger vers la page de la note si la note a été créée avec succès.
            if ($note->get_id() !== null) {
                $this->redirect("notes/show_note/" . $note->get_id());
            }
        }
    }



    public function save_edited_note(): void
    {
        $user = $this->get_user_or_redirect();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noteId = $_POST['id'] ?? null;
            $title = $_POST['title'] ?? '';
            $content = $_POST['text'] ?? '';  // Assurez-vous que cela correspond au nom du champ dans votre formulaire

            $note = Note::get_note_by_id((int)$noteId);
            if ($note && $note->owner == $user->get_id()) {

                $note->title = $title;
                $note->content = $content;


                $note->persist();

                $this->redirect("notes/show_note/" . $note->id);
            } else {
                // Gestion des erreurs

            }
        }
    }

    public function show_addtextnote(): void
    {
        $user = $this->get_user_or_redirect();
        require 'view/view_addtextnote.php';
    }
    public function show_note(): void
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_GET['param1'] ?? null;

        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                if ($note instanceof TextNote) {
                    (new View("open_textnote"))->show(["user" => $user, "note" => $note]);
                } elseif ($note instanceof CheckListNote) {
                    $items = $note->getItems(); // Fetch items using the method
                    (new View("open_checklist_note"))->show(["user" => $user, "note" => $note, "items" => $items]);
                } else {
                    // Handle other note types if necessary
                }
                return;
            }
        }
        $this->redirect("notes");
    }

    public function edit_note()
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_GET['param1'] ?? null;

        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note && $note->owner == $user->get_id()) {
                (new View("edit_Note"))->show(["note" => $note]);
            } else {
            }
        }
    }






    public function check_or_uncheck_item()
    {
        $itemId = $_POST['item_id'] ?? null; // Correct the variable name here
        $noteId = $_POST['note_id'] ?? null;

        if ($itemId) {
            $item = CheckListNoteItem::get_item_by_id((int)$itemId);
            if ($item) {
                $item->toggleChecked();
                $item->persist();
            }
        }
        $this->redirect("notes/show_note/" . $noteId);
    }

    public function pin_or_unpin_note()
    {
        $noteId = $_GET['param1'] ?? null;
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                $note->togglePinned();
                $note->persist();
            }
        }
        $this->redirect("notes/show_note/" . $noteId);
    }

    public function archive_note()
    {
        $noteId = $_GET['param1'] ?? null;
        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            if ($note) {
                $note->toggleArchived();
                $note->persist();
            }
        }
        $this->redirect("notes/show_note/" . $noteId);
    }

    // Controller for archived notes, same as index but with archived notes
    public function archives(): void
    {
        $user = $this->get_user_or_redirect();
        $notes = Note::get_notes_by_owner($user->get_id());
        (new View("archives"))->show(["user" => $user, "notes" => $notes]);
    }

    public function shared()
    {
        $sharedAsEditor = NoteShares::getSharedNotesByRolesEdit(2, $this->get_user_or_redirect()->get_id());
        $sharedAsReader = NoteShares::getSharedNotesByRolesRead(2, $this->get_user_or_redirect()->get_id());
        (new View("shared_notes"))->show(["sharedAsEditor" => $sharedAsEditor, "sharedAsReader" => $sharedAsReader]);
    }
}
