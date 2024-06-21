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
    // Méthode pour afficher la vue principale avec la liste des utilisateurs et leurs notes
    public function index() : void {
        $allUsers = User::getAllUsers(); // Récupère tous les utilisateurs
        $selectedUser1 = isset($_GET['param1']) ? (int)$_GET['param1'] : null; // Récupère le premier utilisateur sélectionné depuis l'URL
        $selectedUser2 = isset($_GET['param2']) ? (int)$_GET['param2'] : null; // Récupère le deuxième utilisateur sélectionné depuis l'URL
        $notesUser1 = $selectedUser1 ? Note::get_notes_by_owner($selectedUser1) : []; // Récupère les notes du premier utilisateur sélectionné
        $notesUser2 = $selectedUser2 ? Note::get_notes_by_owner($selectedUser2) : []; // Récupère les notes du deuxième utilisateur sélectionné

        // Affiche la vue avec les données récupérées
        (new View("session1_2"))->show([
            "allUsers" => $allUsers,
            "selectedUser1" => $selectedUser1,
            "selectedUser2" => $selectedUser2,
            "notesUser1" => $notesUser1,
            "notesUser2" => $notesUser2
        ]);
    }

    // Méthode pour gérer la soumission du formulaire de sélection des utilisateurs
    public function show() {
        $sourceUser = $_POST['source_user']; // Récupère l'utilisateur source depuis le formulaire
        $targetUser = $_POST['target_user']; // Récupère l'utilisateur cible depuis le formulaire

        // Redirige vers l'index avec les paramètres appropriés
        if ($sourceUser && $targetUser && $sourceUser !== '0' && $targetUser !== '0') {
            $this->redirect("session1_2", "index", $sourceUser, $targetUser);
        } elseif ($sourceUser && $sourceUser !== '0') {
            $this->redirect("session1_2", "index", $sourceUser);
        } else {
            $this->redirect("session1_2", "index");
        }
    }

    // Méthode pour gérer le transfert de notes entre utilisateurs
    public function transfer() {
        $noteId = $_POST['note_id']; // Récupère l'ID de la note à transférer depuis le formulaire
        $targetUser = $_POST['target_user']; // Récupère l'utilisateur cible depuis le formulaire

        if ($noteId && $targetUser) {
            $note = Note::get_note_by_id($noteId); // Récupère la note par son ID
            if ($note) {
                $note->owner = $targetUser; // Met à jour le propriétaire de la note
                $originalTitle = $note->title; // Sauvegarde le titre original
                $newTitle = $originalTitle;
                $suffix = 1;
                
                // Assure que le titre de la note est unique pour l'utilisateur cible
                while (!Note::isTitleUnique($newTitle, $targetUser)) {
                    $newTitle = $originalTitle . ' (' . $suffix . ')';
                    $suffix++;
                }

                $note->title = $newTitle; // Met à jour le titre de la note
                $note->weight = Note::get_highest_weight_by_owner($targetUser) + 1; // Assure que le poids est unique pour l'utilisateur cible
                $note->persist(); // Sauvegarde les modifications de la note
            }
        }
        $this->redirect("session1_2", "index", $_POST['source_user'], $targetUser); // Redirige vers la page principale avec les utilisateurs sélectionnés
    }
}
