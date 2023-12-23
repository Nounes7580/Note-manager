<?php
// Assuming $note->created_at is a DateTime object
$now = new DateTime();
$interval = $now->diff($note->created_at);

// Function to format the interval into a human-readable format
function formatTimeDifference($dateTime) {
    if ($dateTime === null) {
        return null;
    }
    $now = new DateTime();
    $interval = $now->diff($dateTime);

    if ($interval->y > 0) {
        return $interval->y . " year" . ($interval->y > 1 ? "s" : "");
    } elseif ($interval->m > 0) {
        return $interval->m . " month" . ($interval->m > 1 ? "s" : "");
    } elseif ($interval->d > 0) {
        return $interval->d . " day" . ($interval->d > 1 ? "s" : "");
    } elseif ($interval->h > 0) {
        return $interval->h . " hour" . ($interval->h > 1 ? "s" : "");
    } elseif ($interval->i > 0) {
        return $interval->i . " minute" . ($interval->i > 1 ? "s" : "");
    } else {
        return $interval->s . " second" . ($interval->s > 1 ? "s" : "");
    }
}


// Display the time difference in italic
$createdAtMessage = "<i>Created " . formatTimeDifference($note->created_at) . " ago. </i>";
$editedAtMessage = $note->edited_at ? "<i>Edited " . formatTimeDifference($note->edited_at) . " ago</i>" : "";

echo "<p>$createdAtMessage $editedAtMessage</p>";

?>