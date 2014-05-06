<?php

require_once 'create_table.php'; // create the table and provide $db (not really needed when we require insert_tasks.php)
require_once 'insert_tasks.php'; // insert some data into the database

$name_part = '%First%';

$stmt_fetch_by_name = $db->prepare("SELECT * FROM items WHERE name LIKE :name_part");
$stmt_fetch_by_name->bindValue(':name_part', $name_part);

$stmt_fetch_by_name->execute();

$items = $stmt_fetch_by_name->fetchAll(PDO::FETCH_ASSOC);

foreach($items as $index => $items_array) {
    foreach($items_array as $field => $value){
        echo "$field has $value" . PHP_EOL;
    }
    echo PHP_EOL;
}