<?php

require_once 'model/User.php';
require_once 'model/TextNote.php';
require_once 'model/Note.php';
require_once 'model/CheckListNote.php';
require_once 'model/CheckListNoteItem.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/NoteShare.php';

class ControllerSession1_2 extends Controller {
    public function index() : void {
        $allUsers = User::getAllUsers();
        $selectedUser1 = isset($_GET['param1']) ? (int)$_GET['param1'] : null;
        $selectedUser2 = isset($_GET['param2']) ? (int)$_GET['param2'] : null;
        $notesUser1 = Note::get_notes_by_owner($selectedUser1);
        $notesUser2 = Note::get_notes_by_owner($selectedUser2);

        (new View("session1_2"))->show([
            "allUsers" => $allUsers,
            "selectedUser1" => $selectedUser1,
            "selectedUser2" => $selectedUser2,
            "notesUser1" => $notesUser1,
            "notesUser2" => $notesUser2

        ]);
    }

    public function show()
    {
        $sourceUser = $_POST['source_user'];
        $targetUser = $_POST['target_user'];

        if ($sourceUser && $targetUser && $sourceUser !== '0' && $targetUser !== '0') {
            $this->redirect("session1_2", "index", $sourceUser, $targetUser);
        } elseif ($sourceUser && $sourceUser !== '0') {
            $this->redirect("session1_2", "index", $sourceUser);
        } else {
            $this->redirect("session1_2", "index");
        }
    }
}
