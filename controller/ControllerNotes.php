<?php

// Activez l'affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'model/User.php';
require_once 'model/TextNote.php';
require_once 'model/Note.php';
require_once 'model/CheckListNote.php';
require_once 'model/CheckListNoteItem.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/NoteShare.php';

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
        // Obtenir l'utilisateur actuel ou rediriger si non connecté
        $user = $this->get_user_or_redirect();

        // Calculer le poids le plus élevé pour les notes de cet utilisateur
        $highestWeight = Note::get_highest_weight_by_owner($user->get_id()) + 1;


        $errors = [];
        $items = [];
        $validFields = [];
        $title = '';


        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer le titre du formulaire, ou lui attribuer une valeur par défaut
            $title = $_POST['title'] ?? 'Titre par défaut';
            if (empty($title)) {
                $errors['title'] = "Le titre est requis.";
            } else if (strlen($title) < 3 || strlen($title) > 25) {
                $errors['title'] = "Le titre doit contenir entre 3 et 25 caractères.";
            }

            for ($i = 1; $i <= 5; $i++) {

                if (!empty($_POST["item$i"])) {
                    $items[] = trim($_POST["item$i"]);
                }
                $unique_items = array_unique($items);
                if (count($items) !== count($unique_items)) {
                    foreach ($items as $index => $item) {
                        if (count(array_filter($items, fn ($i) => $i === $item)) > 1) {
                            // Ajouter une erreur spécifique à l'élément dupliqué
                            $errors["item" . ($index + 1)] = "Item" . ($index + 1) . " must be unique.";
                        }
                    }
                }
            }

            // Vérifier s'il y a des doublons dans les éléments
            if (count($items) !== count(array_unique($items))) {
                // Ajouter une erreur si des doublons sont trouvés
                $errors[] = "Les éléments doivent être uniques.";
            }

            // Si aucune erreur n'est détectée
            if (empty($errors)) {
                // Créer une nouvelle note de checklist
                $checklistNote = new CheckListNote(
                    title: $title,
                    owner: $user->get_id(),
                    pinned: false,
                    archived: false,
                    weight: $highestWeight
                );

                // Sauvegarder la note et récupérer son identifiant
                $checklistNote->persist();

                $checklistNoteId = $checklistNote->get__id();


                // Si l'ID de la note est récupéré avec succès
                if ($checklistNoteId !== null) {
                    // Sauvegarder chaque élément de la checklist
                    foreach ($items as $itemContent) {
                        $item = new CheckListNoteItem(
                            checklist_note_id: $checklistNoteId,
                            content: $itemContent,
                            checked: false
                        );
                        $item->persistAdd();
                    }


                    $this->redirect("notes/show_note/" . $checklistNoteId);
                    return;
                } else {

                    error_log("Erreur : L'ID de CheckListNote est null.");
                    $errors[] = "Une erreur est survenue lors de l'enregistrement de la note.";
                }
            }
        }

        $validFields = [
            'title' => !empty($title) && empty($errors['title']),
            // Répétez pour chaque champ d'élément
            'item1' => !empty($items[0]) && empty($errors['item1']),
            'item2' => !empty($items[1]) && empty($errors['item2']),
            'item3' => !empty($items[2]) && empty($errors['item3']),
            'item4' => !empty($items[3]) && empty($errors['item4']),
            'item5' => !empty($items[4]) && empty($errors['item5']),

        ];

        // Préparer les données pour la vue
        $data = [
            "user" => $user,
            "errors" => $errors,
            "validFields" => $validFields,
            "title" => $title,
            "items" => $items

        ];


        // Afficher la vue avec les données et les erreurs
        (new View("addchecklistnote"))->show($data);
    }




    public function show_addchecklistnote(): void
    {
        $user = $this->get_user_or_redirect();
        require 'view/view_addchecklistnote.php';
    }



    public function add_textnote(): void
    {
        $user = $this->get_user_or_redirect();

        $errors = []; // Initialisation d'un tableau d'erreurs

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $highestWeight = Note::get_highest_weight_by_owner($user->get_id());

            $title = $_POST['title'] ?? 'Nouveau Titre';
            $text = $_POST['text'] ?? 'Nouveau Texte';
            $owner = $user->get_id();
            $pinned = false;
            $archived = false;
            $weight = $highestWeight + 1; // Augmenter le poids le plus élevé de 1.

            // Validation du titre
            if (empty($title)) {
                $errors['title'] = "Le titre est requis.";
            } elseif (strlen($title) < 3 || strlen($title) > 25) {
                $errors['title'] = "Le titre doit contenir entre 3 et 25 caractères.";
            }



            // Vérifier s'il y a des erreurs avant de créer la note
            if (empty($errors)) {
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
                if ($note->get__id() !== null) {
                    $this->redirect("notes/show_note/" . $note->get__id());
                }
            } else {
                (new View("addtextnote"))->show(['user' => $user, 'errors' => $errors]);
            }
        }
    }



    public function save_edited_note(): void
    {
        $user = $this->get_user_or_redirect();
        $errors = []; // Initialisation d'un tableau d'erreurs
        $note = null; // Initialisation de la variable note

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noteId = $_POST['id'] ?? null;
            if ($noteId) {
                $note = TextNote::get_note_by_id((int)$noteId);
            }

            if (!$note || !$note instanceof TextNote || $note->owner != $user->get_id()) {
                // La note n'existe pas ou l'utilisateur n'est pas autorisé à la modifier
                $errors['note'] = "La note spécifiée n'existe pas ou vous n'avez pas les droits pour la modifier.";
                // Assurez-vous de passer aussi $note à la vue, même si elle est null, pour éviter des erreurs inattendues
                (new View("edit_note"))->show(['user' => $user, 'note' => $note, 'errors' => $errors]);
                return;
            }

            $title = $_POST['title'] ?? '';
            $content = $_POST['text'] ?? '';


            // Mise à jour de la note
            $note->title = $title;
            $note->content = $content;
            $note->edited_at = new DateTime();
            $note->persistAdd(); // Assurez-vous que cette méthode effectue bien une mise à jour

            $this->redirect("notes/show_note/" . $note->id);
        }
    }
 
            

    public function edit_note()
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_GET['param1'] ?? null;
        
        $errors = []; // Initialisation d'un tableau d'erreurs

 $note = Note::get_note_by_id((int)$noteId);
 if (!$note) {
    // La note n'existe pas
    $errors['note'] = "La note spécifiée n'existe pas.";
    (new View("error"))->show(["errors" => $errors]);
    return; // Arrête l'exécution de la méthode ici
} elseif ($note->owner != $user->get_id()) {
    // L'utilisateur n'est pas autorisé à éditer cette note
    $errors['note'] = "Vous n'avez pas les droits pour modifier cette note.";
    (new View("error"))->show(["errors" => $errors]);
    return; // Arrête l'exécution de la méthode ici
}

// Traiter la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? ''; 
    $content = $_POST['text'] ?? ''; 

    if (empty($title)) {
        $errors['title'] = "Le titre est requis.";
    } elseif (strlen($title) < 3 || strlen($title) > 25) {
        $errors['title'] = "Le titre doit contenir entre 3 et 25 caractères.";
    }

    if (empty($errors)) {
      (new View("edit_Note"))->show(["note" => $note]);
      return;
    }
}

// Affichez la vue d'édition avec la note et les erreurs (qui seront vides si la requête n'est pas POST ou si aucune erreur n'a été détectée)
(new View("edit_note"))->show(['user' => $user, 'note' => $note, 'errors' => $errors]);
return;
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
        unset($_SESSION['checklist_items']);
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



    public function editchecklistnote()
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_GET['param1'] ?? null;
        $errors = $_SESSION['errors'] ?? []; // Récupérer les erreurs de la session
        unset($_SESSION['errors']);
        $validFields = [];

        if ($noteId) {
            $note = CheckListNote::get_note_by_id((int)$noteId);
            $isEditor = NoteShare::isUserEditor($user->id, $noteId); // Méthode hypothétique


            if ($note && $note instanceof CheckListNote && ($note->owner == $user->get_id() || $isEditor)) {
                $editedItems = isset($_SESSION['checklist_items'][$noteId]) ? $_SESSION['checklist_items'][$noteId] : [];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $title = $_POST['title'] ?? '';
                    $validFields['title'] = true;

                    if (empty($title)) {
                        $errors['title'] = "Le titre est requis.";
                        $validFields['title'] = false;
                    } elseif (strlen($title) < 5 || strlen($title) > 25) {
                        $errors['title'] = "Le titre doit contenir entre 5 et 25 caractères.";
                        $validFields['title'] = false;
                    }

                    $newEditedItems = [];
                    $i = 1;
                    while (isset($_POST["item$i"])) {
                        $newEditedItems[] = trim($_POST["item$i"]);
                        $i++;
                    }
                    $_SESSION['checklist_items'][$noteId] = $newEditedItems;
                    $editedItems = $newEditedItems;
                }

                $data = [
                    'note' => $note,
                    'editedItems' => $editedItems,
                    'errors' => $errors,
                    'validFields' => $validFields
                ];
                (new View("editchecklistnote"))->show($data);
            }
        }
    }

    public function add_checklist_item(): void
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_POST['note_id'] ?? null;
        $newItemContent = $_POST['new_item'] ?? '';

        $note = CheckListNote::get_note_by_id($noteId);
        $isEditor = NoteShare::isUserEditor($user->id, $noteId);

        if (!$note || !$note instanceof CheckListNote || ($note->owner != $user->get_id() && !$isEditor)) {

            $_SESSION['errors']['note'] = "Vous n'êtes pas autorisé à modifier cette note ou elle n'existe pas.";
            $this->redirect("error_page");
            return;
        }

        // Ajouter le nouvel élément si le contenu n'est pas vide
        if (!empty($newItemContent)) {
            if (!isset($_SESSION['checklist_items'][$noteId])) {
                $_SESSION['checklist_items'][$noteId] = [];
            }
            $_SESSION['checklist_items'][$noteId][] = $newItemContent;
        }

        
        // Redirection vers la page d'édition de la checklist
        $this->redirect("notes", "editchecklistnote", $noteId);
    }

    public function delete_checklist_item(): void
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_POST['note_id'] ?? null;
        $itemId = $_POST['item_id'] ?? null;

        // Vérification de la validité de la note et des permissions de l'utilisateur
        $note = CheckListNote::get_note_by_id($noteId);
        $isEditor = NoteShare::isUserEditor($user->id, $noteId);

        if (!$note || !$note instanceof CheckListNote || ($note->owner != $user->get_id() && !$isEditor)) {
            $_SESSION['errors']['note'] = "Vous n'êtes pas autorisé à modifier cette note ou elle n'existe pas.";
            $this->redirect("error_page");
            return;
        }

        // Suppression de l'élément de la checklist
        if ($itemId !== null) {
            $item = CheckListNoteItem::get_item_by_id($itemId);
            if ($item && $item->checklist_note_id == $noteId) {
                $item->delete(); // Cette méthode doit être implémentée dans CheckListNoteItem pour supprimer l'élément de la DB
                $note->edited_at = new DateTime();
                $note->persist();
            } else {
                $_SESSION['errors']['item'] = "L'élément spécifié n'existe pas ou ne peut pas être supprimé.";
            }
        }
        

        $this->redirect("notes", "editchecklistnote", $noteId);
    }

    public function delete_temporary_item(): void
{
    $noteId = $_POST['note_id'];
    $tempItemId = $_POST['temp_item_id'];

    // Supprime l'élément de la session
    if (isset($_SESSION['checklist_items'][$noteId][$tempItemId])) {
        unset($_SESSION['checklist_items'][$noteId][$tempItemId]);
    }

    $this->redirect("notes", "editchecklistnote", $noteId);
}
    public function save_edited_checklistnote(): void
    {
        $user = $this->get_user_or_redirect();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $noteId = $_POST['id'] ?? null;
            $title = $_POST['title'] ?? '';
            $items = $_POST['items'] ?? [];
            $errors = [];
            if (empty($title)) {
                $errors['title'] = "Le titre est requis.";
            } elseif (strlen($title) < 5 || strlen($title) > 25) {
                $errors['title'] = "Le titre doit contenir entre 5 et 25 caractères.";
            }

            if (!empty($errors)) {
                // S'il y a des erreurs, réutiliser la méthode editchecklistnote
                $_SESSION['errors'] = $errors; // Stocker les erreurs dans la session
                $this->redirect("notes", "editchecklistnote", $noteId);
                return;
            }
            if ($noteId) {
                $note = CheckListNote::get_note_by_id($noteId);
                $isEditor = NoteShare::isUserEditor($user->id, $noteId);

                if ($note && $note->owner == $user->get_id() || $isEditor) {
                    $note->title = $title;
                    $note->edited_at = new DateTime();
                    $note->persist();

                    foreach ($items as $itemData) {
                        $itemId = $itemData['id'] ?? null;
                        $itemContent = $itemData['content'] ?? '';
                        $itemChecked = isset($itemData['checked']) && $itemData['checked'] == 'on';

                        if ($itemId) {
                            $item = CheckListNoteItem::get_item_by_id($itemId);
                            if ($item && $item->checklist_note_id == $noteId) {
                                $item->content = $itemContent;
                                $item->checked = $itemChecked;
                                $item->persist();
                            }
                        }
                    }

                    // Traitement des nouveaux items
                    if (isset($_SESSION['checklist_items'][$noteId])) {
                        foreach ($_SESSION['checklist_items'][$noteId] as $itemContent) {
                            if (!empty($itemContent)) {
                                $newItem = new CheckListNoteItem($noteId, $itemContent, false);
                                $newItem->persistAdd();
                            }
                        }
                    }

                    $this->redirect("notes/show_note/" . $noteId);
                } else {
                }
            } else {
                // Redirection ou affichage d'un message d'erreur si noteId n'est pas défini
            }
        }
    }




    public function check_or_uncheck_item()
    {
        $user = $this->get_user_or_redirect();
        $itemId = $_POST['item_id'] ?? null;
        $noteId = $_POST['note_id'] ?? null;
    
        $note = CheckListNote::get_note_by_id((int)$noteId);
    
        // Vérifiez si l'utilisateur actuel est le propriétaire ou un éditeur de la note.
        $isOwner = $note->owner == $user->id;
        $isEditor = NoteShare::isUserEditor($user->id, $noteId);
    
        if ($itemId && ($isOwner || $isEditor)) {
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
        $noteId = $_POST['noteId'] ?? null; // correction du get à $_POST
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

    //  for archived notes, same as index but with archived notes
    public function archives(): void
    {
        $user = $this->get_user_or_redirect();
        $notes = Note::get_notes_by_owner($user->get_id());
        $verif = Note::getSharedNotesByUser($user->get_id());
        (new View("archives"))->show(["user" => $user, "notes" => $notes, "sharedNotes" => $verif]);
    }

    public function confirm_delete()
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_POST['note_id'] ?? $_GET['param1'] ?? null;

        $note = Note::get_note_by_id($noteId);
        require('view/confirmation_delete.php');
    }

    public function delete_note(): void
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_POST['note_id'] ?? $_GET['param1'] ?? null;

        // Vérifier si l'ID de la note est fourni
        if (!$noteId) {
            $this->redirect("notes/archives");
            return;
        }

        // Récupérer la note à supprimer
        $note = Note::get_note_by_id($noteId);


        // Supprimer la note
        $note->delete(); // Cette méthode doit être implémentée dans votre classe Note

        // Redirection vers la page des archives après la suppression réussie
        $this->redirect("notes/archives");
    }


    public function shared()
    {
        $shareOne = null;
        $userShare = $_GET['param1'] ?? null;
        if ($userShare) {
            $shareOne = User::get_user_by_id($userShare);
        }

        $user = $this->get_user_or_redirect()->get_id();
        $verif = Note::getSharedNotesByUser($user);
        $sharedAsEditor = NoteShare::getSharedNotesByRolesEdit($userShare, $user);
        $sharedAsReader = NoteShare::getSharedNotesByRolesRead($userShare, $user);
        (new View("shared_notes"))->show(["sharedAsEditor" => $sharedAsEditor, "sharedAsReader" => $sharedAsReader, "sharedNotes" => $verif, "userShare" => $shareOne]);
    }


    public function share()
    {
        $user = $this->get_user_or_redirect();
        $noteId = $_GET['param1'] ?? null;
        $resultsOfSharedUsers = [];
        $permission = [];
        $listOfNoteShare = [];

        if ($noteId) {
            $note = Note::get_note_by_id((int)$noteId);
            $resultsOfSharedUsers = $note->getUsersWhoSharedWith();
            foreach ($resultsOfSharedUsers as $data) {
                $permission[] = $note->isSharedWithPermission($data->get_id());
            }
            for ($i = 0; $i < count($resultsOfSharedUsers); $i++) {
                // $listOfNoteShare = new NoteShare($noteId, $resultsOfSharedUsers[$i], $permission[$i]);
            }
        }

        $allUsers = User::getAllUsersExceptCurrent($user->id);

        // Filtrer les utilisateurs déjà partagés
        $usersToShareWith = array_filter($allUsers, function ($user) use ($resultsOfSharedUsers) {
            foreach ($resultsOfSharedUsers as $sharedUser) {
                if ($sharedUser->get_id() == $user->id) {
                    return false;
                }
            }
            return true;
        });

        (new View("shares"))->show(["user" => $user, "usersToShareWith" => $usersToShareWith, "resultsOfSharedUsers" => $resultsOfSharedUsers, "permission" => $permission, "note" => $note]);
    }



    public function addShare(): void
    {
        $noteId = isset($_POST['note_id']) ? (int)$_POST['note_id'] : null;
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
        $permission = isset($_POST['permission']) ? $_POST['permission'] : null;

        $isUserDefault = ($userId === -1);
        $isPermissionDefault = ($permission === 'option1');

        if ($noteId != null && $userId != null && $permission != null && !$isUserDefault && !$isPermissionDefault) {
            $editor = $permission == "editor";
            $noteShare = new NoteShare($noteId, $userId, $editor);
            $noteShare->addShare();
        }
        $this->redirect("notes", "share", $noteId);
    }
    public function deleteShare()
    {
        $user = $this->get_user_or_redirect(); // Assurez-vous que l'utilisateur est connecté
        $noteId = $_POST['note_id'] ?? null; // Récupérez l'ID de la note depuis le POST
        $userId = $_POST['user_id'] ?? null; // Récupérez l'ID de l'utilisateur avec qui la note est partagée
        $editor = $_POST['editor'] ?? null; // Récupérez si l'utilisateur est éditeur ou non

        // Vérifiez que toutes les données nécessaires sont présentes
        if ($noteId && $userId && $editor !== null) {
            $noteShare = new NoteShare($noteId, $userId, $editor); // Créez une instance de NoteShare
            $noteShare->deleteShare(); // Appelez la méthode deleteShare
            // Définir un message de succès dans la session
            $_SESSION['success'] = "Le partage a été supprimé.";
            // Rediriger vers la page de partage
            $this->redirect("notes", "share", $noteId);
        } else {
            $_SESSION['error'] = "Les informations nécessaires pour la suppression n'ont pas été fournies";
            // Redirigez vers la page de partage avec un message d'erreur
            $this->redirect("notes", "share", $noteId);
        }
    }
    public function togglePermission()
    {
        $user = $this->get_user_or_redirect(); // Assurez-vous que l'utilisateur est connecté
        $noteId = $_POST['note_id'] ?? null; // Récupérez l'ID de la note depuis le POST
        $userId = $_POST['user_id'] ?? null; // Récupérez l'ID de l'utilisateur avec qui la note est partagée
        $editor = $_POST['editor'] ?? null; // Récupérez si l'utilisateur est éditeur ou non

        // Vérifiez que toutes les données nécessaires sont présentes
        if ($noteId && $userId && $editor !== null) {
            $noteShare = new NoteShare($noteId, $userId, $editor); // Créez une instance de NoteShare
            $noteShare->changePermission(); // Appelez la méthode deleteShare
            // Définir un message de succès dans la session
            $_SESSION['success'] = "La permission a été modifiée.";
            // Rediriger vers la page de partage
            $this->redirect("notes", "share", $noteId);
        } else {
            $_SESSION['error'] = "Les informations nécessaires pour changer la permission n'ont pas été fournies.";;
            // Redirigez vers la page de partage avec un message d'erreur
            $this->redirect("notes", "share", $noteId);
        }
    }
    
    
}