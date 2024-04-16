require_once 'model/Note.php';
<?php
if (isset($_POST['note_id'])) {
    $noteId = $_POST['note_id'];
    $note = Note::get_note_by_id($noteId);
    $maxPinnedWeight = Note::get_max_weight_pinned(); // Implémentez cette méthode
    $note->pin($maxPinnedWeight); // Implémentez cette méthode dans la classe Note
    $note->save();
    echo json_encode(array('success' => true));
}
?>