<?php

require_once 'create_table.php'; // create the table and provide $db (not really needed when we require insert_tasks.php)
require_once 'insert_tasks.php'; // insert some data into the database

$stmt_update_finish = $db->prepare("UPDATE items SET finish_in_days=:days WHERE id=:id");
$stmt_update_finish->bindParam(':id', $record_id);
$stmt_update_finish->bindParam(':days', $days);

$record_id = 1;
$days = 3;

$stmt_update_finish->execute();


$stmt = $db->prepare("SELECT * FROM items WHERE id=:id");
$stmt->bindParam(':id', $record_id);

$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $key => $item) {
    foreach ($item as $field => $value) {
        echo "$field has $value" . PHP_EOL;
    }
    echo PHP_EOL;
}