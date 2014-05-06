<?php

$db = new PDO('sqlite::memory:');

$sql = <<<SQL
       CREATE TABLE IF NOT EXISTS items (
        id integer primary key autoincrement,
        name varchar(255) not null,
        finish_in_days integer,
        created timestamp default current_timestamp
       );
SQL;

$create_stmt = $db->prepare($sql);
$create_stmt->execute();

