<?php require_once 'model/Note.php';

if (isset($_POST['note_id'])) {
    $noteId = $_POST['note_id'];
    $note = Note::get_note_by_id($noteId);

    // Récupérer le poids maximum des notes épinglées pour définir le poids correct de la note à épingler.
    $maxPinnedWeight = Note::get_max_weight_pinned();
    $note->pin($maxPinnedWeight); // Mettre à jour le poids de la note en épinglant
    $note->save(); // Sauvegarder les modifications dans la base de données

    echo json_encode(array('success' => true));
}
?>