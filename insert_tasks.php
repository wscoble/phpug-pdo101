<?php

require_once 'create_table.php'; // create the table and provide $db

$sql_name = "INSERT INTO items (name) values (:name)";
$sql_name_and_days = "INSERT INTO items (name, finish_in_days) values (:name, :days)";

$stmt_name = $db->prepare($sql_name);
$stmt_name_and_days = $db->prepare($sql_name_and_days);

$stmt_name->bindParam(':name', $name);

$stmt_name_and_days->bindParam(':name', $name);
$stmt_name_and_days->bindParam(':days', $days);

$name = 'First Task';
$stmt_name->execute();

$name = 'Second Task';
$days = 3; // must be an integer
$stmt_name_and_days->execute();