# PHP PDO (PHP Data Objects)

- PDO is a database access layer that provides a uniform method of access to multiple databases.
- It allows developers to interact with databases using a unified interface, regardless of the database system being used (MySQL, PostgreSQL, SQLite, etc.)

### 1. Connecting to a Database:
To establish a connection to a database using PDO, you need to provide these connection details:
* hostname
* username
* password
* database name
* database type (DSN)

### 2. Executing Queries:
You can execute SQL queries using PDO's query method or prepare-execute methods. 

* query() method
* prepare() execute() method 

> Prepared statements provide better security by separating SQL logic from data.

When using prepared statements, you can use 2 different param types.
1. Positional Params 
2. Named Params

And 2 diffent fetching methods.
1. fetch() for single row 
2. fetchAll() for multiple rows

#### Different modes
When fetching, either using query method or prepare-execute methods, you can pass __different modes__. For example

- PDO::FETCH_ASSOC
- PDO::FETCH_CLASS
- PDO::FETCH_OBJ

Check more at [PHP Documentation](https://www.php.net/manual/en/pdostatement.fetch.php).

Or if you don't want to pass __mode__ every time you fetch, you can set attribute in __pdo object__. For example

```php
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
```

Not only SELECT, you can execute other SQL queries (INSERT, UPDATE, DELETE, SEARCH etc).

```php
// INSERT DATA
$title = 'Post Seventeen';
$body = 'This is the body of post seventeen';
$author = 'Avril';

$sql = 'INSERT INTO posts(title, body, author) VALUES(:title, :body, :author)';
$stmt = $pdo->prepare($sql);
$stmt->execute(['title' => $title, 'body' => $body, 'author' => $author]);
echo 'Post Added';

// UPDATE DATA
$id = 1;
$body = 'This is the updated post';

$sql = 'UPDATE posts SET body = :body WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['body' => $body, 'id' => $id]);
echo 'Post Updated';

// DELETE DATA
$id = 3;

$sql = 'DELETE FROM posts WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
echo 'Post Deleted';

// SEARCH DATA
$search = "%f%";
$sql = 'SELECT * FROM posts WHERE title LIKE ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$search]);
$posts = $stmt->fetchAll();
```


### 3. Handling Transactions:
PDO supports transactions, which allow you to execute multiple SQL statements as a single unit of work. 

> This ensures data consistency.

```php
try {
    $pdo->beginTransaction();
    // execute multiple statements here
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
}
```