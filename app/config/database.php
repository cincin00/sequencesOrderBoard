<?php

try {
    $pdo['host'] = 'localhost';
    $pdo['dbname'] = 'board_example';
    $pdo['user'] = 'root';
    $pdo['password'] = '';
    $dbh = new PDO('mysql:host='.$pdo['host'].';dbname='.$pdo['dbname'], $pdo['user'], $pdo['password']);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    exit;
}
