<?php
require_once 'model/User.php';
require_once 'model/TextNote.php';
require_once 'model/Note.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerNotes extends Controller {
    public function index() : void {
        $user = $this->get_user_or_redirect();
        $notes = TextNote::get_notes_by_owner($user->get_id());
        (new View("notes"))->show(["user" => $user, "notes" => $notes]);
    }
}
