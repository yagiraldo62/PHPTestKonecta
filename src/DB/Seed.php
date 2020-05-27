<?php
require '../../bootstrap.php';

$statement = <<<EOS
    INSERT INTO user
        (username,pass)
    VALUES
        ('guest','pass'),
        ('guest2','pass');

    INSERT INTO categories
        (id,name)
    VALUES
        (1,'School'),
        (2,'House');

    INSERT INTO products
        (name, reference, price, weight, category, stock)
    VALUES
        ('Book', '001', 1200, 2, 1,20),
        ('Pen', '002', 400, 1, 1,20),
        ('Table', '003', 2500, 10, 1,2),
        ('Bed', '004', 2500, 10, 2,3),
        ('Television', '005', 5500, 7, 2,2),
        ('couch', '006', 6300, 70, 2,1);
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}