<?php

require_once 'model/User.php';
require_once 'model/TextNote.php';
require_once 'model/Note.php';
require_once 'model/CheckListNote.php';
require_once 'model/CheckListNoteItem.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/NoteShare.php';


class ControllerSession1 extends Controller {
    public function index() : void {


        $user = $this->get_user_or_redirect();
        $allUsers = User::getAllUsers();
        $selectedUser = isset($_GET['param1']) ? (int)$_GET['param1'] : null; //conversion du param 1 en int
        if($selectedUser >0){                                                 // si pas d'utilisateur selectionné 
            $selectedUserID = User::get_user_by_id($selectedUser);            // recup l'u
            $selectedName = $selectedUserID ->full_name;
            $notesOfUser = CheckListNote::get_notes_by_owner($selectedUser);
        }
        else{
            $selectedName = "No user";
            $notesOfUser = [];
        }

        (new View("session1"))->show([
            "user" => $user,
            "allUsers" => $allUsers,
            "selectedName" => $selectedName,
            "selectedUser" => $selectedUser,
            "notesOfUser" => $notesOfUser,
        ]);
    }

    public function show()
    {
        $selectedUser = $_POST['selected_user'];

         $this->redirect("Session1", "index", $selectedUser);

    }

    public function toggleItemsByNotes() {
        $selectedNotes = $_POST['notes'] ?? [];
        $selectedUser = $_POST['selected_user'] ?? null; // Retrieve the selected user parameter
        
        foreach ($selectedNotes as $noteId) { //pour chaque note selectionnée en tant que note id
            $note = CheckListNote::get_note_by_id($noteId); // on dis que une note = on reprend une checklistnote depuis son id
            if ($note) {                                    // si on a une note
                $items = $note->getItems();                 // on recupere les items d'une note
                foreach ($items as $item) {                 // pour chaque item récupéré
                    $item->toggleChecked();                 // on check
                    $item->update();                        // on update
                }                                            
                $note->persist();                           // on persist
            }
        }

        // on redirige la page vers le bon user si on a un user selectionné
        if ($selectedUser) {
            $this->redirect("Session1", "index", $selectedUser);
        } else {
            $this->redirect("Session1", "index");
        }
    }

    

}