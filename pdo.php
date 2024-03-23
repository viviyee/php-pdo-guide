<?php

### 1. Connecting to a Database

$host = 'localhost';
$username = 'root';
$password = '123456';
$dbname = 'pdoguide';

$dsn = "mysql:host=$host;dbname=$dbname";

$pdo = new PDO($dsn, $username, $password);


### 2. Executing Queries

// query() method
$statement = $pdo->query('SELECT * FROM posts');
while($row = $statement->fetch(PDO::FETCH_OBJ)) {
    // var_dump($row);
}

// prepare() method
$author = 'anna'; // let's pretend this comes from userinput
$is_published = true;

$sql_unsafe = "SELECT * FROM posts WHERE author = '$author'"; // unsafe way. don't do this

// positional params
$sql = "SELECT * FROM posts WHERE author = ? AND is_published = ?"; 
$statement = $pdo->prepare($sql);
$statement->execute([$author, $is_published]);

// named params
$sql = "SELECT * FROM posts WHERE author = :author AND is_published = :is_published"; 
$statement = $pdo->prepare($sql);
$statement->execute([
    'author' => $author,
    'is_published' => $is_published
]);

// fetch multiple rows
$posts = $statement->fetchAll(PDO::FETCH_OBJ); 
// var_dump($posts);

// fetch single row
$sql = "SELECT * FROM posts WHERE id = :id";
$statement = $pdo->prepare($sql);
$statement->execute([
    'id' => 2, // let's pretend this comes from user input
]);
$post = $statement->fetch(PDO::FETCH_BOTH);
// var_dump($post);


### 3. Handling Transactions:

$title = "Post 10";
$body = "This is the body of post 10.";
$author = "John Doe";

try {
    $pdo->beginTransaction();

    // execute 1
    $statement = $pdo->prepare("INSERT INTO posts(title, body, author) VALUES(:title, :body, :author)");
    $statement->execute([
        'title' => $title,
        'body' => $body,
        'author' => $author,
    ]);

    // execute 2
    $statement = $pdo->prepare("UPDATE posts SET author = :author WHERE id = :id");
    $statement->execute([
        'author' => 'test user',
        'id' => 1
    ]);

    // commit
    $pdo->commit();

    echo "Executed 2 sql statements.";
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Transaction failed. ".$e->getMessage();
}