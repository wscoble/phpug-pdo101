<?php

require_once 'create_table.php'; // create the table and provide $db (not really needed when we require insert_tasks.php)
require_once 'insert_tasks.php'; // insert some data into the database

$stmt_delete = $db->prepare("DELETE FROM items WHERE id=:id");
$stmt_delete->bindParam(':id', $record_id);

$record_id = 1;

$stmt_delete->execute();

$stmt_count_records = $db->prepare("SELECT count(*) FROM items WHERE id=:id");
$stmt_count_records->bindParam(':id', $record_id);

$stmt_count_records->execute();

echo "Found ".$stmt_count_records->fetch()[0]." records" . PHP_EOL;