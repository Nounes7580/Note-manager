<?php
// Ce script est appelé par l'AJAX post pour mettre à jour l'ordre des notes
if (isset($_POST['order'])) {
    $allOrders = $_POST['order'];
    foreach ($allOrders as $categoryOrder) {
        foreach ($categoryOrder as $position => $noteId) {
            // Mettre à jour l'ordre de la note dans la base de données
            $sql = "UPDATE notes SET weight = :weight WHERE id = :id";
            // Préparez et exécutez la requête avec $position et $noteId
            // ...
        }
    }
    echo "Order has been updated";
}
?>
