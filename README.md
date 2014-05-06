# Databases for Beginners with PHP and SQLite with PDO

This repository partners with a presentation given to the
[Las Vegas PHP Users Group](http://www.meetup.com/Las-Vegas-PHP-Users-Group/) on Tuesday, May 6, 2014.

We'll be using [PDO](http://www.php.net/manual/en/class.pdo.php) since it applies to many databases,
including SQLite, MySQL, and others.

### Creating/Connecting to a database

To connect to a database using PDO, we first need the [DSN](http://www.php.net/manual/en/pdo.construct.php),
or location, of the database. Our database will be located in memory so our dsn will be _sqlite::memory:_.

    $db = new PDO('sqlite::memory:');

Now we have access to the database with the variable _$db_ and can start executing queries.

### First, we need a table

The SQL query for creating a table looks like this:

    CREATE TABLE <table_name> (
    column_name column_type options,
    ...
    column_name column_type options)

For our items table, we'll create a name column and a finish_in_days column. Since our task list UI will have
an input for how many days we give ourselves to complete a task, we'll use a combination of finish_in_days and
the created timestamp to calculate the deadline in code. In my opinion, modelling our data structure after the
user inputs keeps our data user-centric and gives future maintainers consistent context about what our little
application actually does.

    CREATE TABLE IF NOT EXISTS items (
     id integer primary key autoincrement,
     name varchar(255) not null,
     finish_in_days integer,
     created timestamp default current_timestamp
    );

This query will create a table with a primary key that auto-increments, a required name field, and an optional
finish_by field (for our procrastinators). To give us some protection against sql injection and other nefarious
attacks, we'll use a prepared statement.

Let's execute it (see _create_table.php_):

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

See [PDO::prepare](http://www.php.net/manual/en/pdo.prepare.php) and
[PDOStatement::execute](http://www.php.net/manual/en/pdostatement.execute.php).

### Creating our first task item

See _insert_tasks.php_.

Prepared statements work great for parameterized insert statements. Remember, finish_in_days is optional, so we'll
need two prepared statements. Here is what we'll be using:

    $sql_name = "INSERT INTO items (name) values (:name)";
    $sql_name_and_days = "INSERT INTO items (name, finish_in_days) values (:name, :days)";

    $stmt_name = $db->prepare($sql_name);
    $stmt_name_and_days = $db->prepare($sql_name_and_days);

Next, we need to bind our parameters to locally scoped variables. We do this so when we execute the query, PDO
looks at our local scope and chooses the correct values.

    $stmt_name->bindParam(':name', $name);

    $stmt_name_and_days->bindParam(':name', $name);
    $stmt_name_and_days->bindParam(':days', $days);

See [PDOStatement::bindParam](http://www.php.net/manual/en/pdostatement.bindparam.php).

So we have two ways to insert data into the database based on our user's input. Let's create an entry for each:

    $name = 'First Task';
    $stmt_name->execute();

    $name = 'Second Task';
    $days = 3;
    $stmt_name_and_days->execute();

If you want to bind a value and not a parameter, use
[PDOStatement::bindValue](http://www.php.net/manual/en/pdostatement.bindvalue.php).

### Fetching our tasks

See _fetch_tasks.php_.

Let's go ahead and grab all our tasks. First, let's prepare our statement and execute it:

    $stmt_fetch_all_items = $db->prepare("SELECT * FROM items");
    $stmt_fetch_all_items->execute();

Then let's fetch all the results using [PDOStatement::fetchAll](http://www.php.net/manual/en/pdostatement.fetchall.php):

    $items = $stmt_fetch_all_items->fetchAll();

Now we have an array of the items from our database, but the array is indexed in a pretty nasty way. We should
specify how we want the array returned. For this case, we'll want an associative array. Our line changes to:

    $items = $stmt_fetch_all_items->fetchAll(PDO::FETCH_ASSOC);

_$items_ is an array of arrays now with the final array being each item. Let's walk the array and echo the results:

    foreach($items as $index => $items_array) {
      foreach($items_array as $field => $value){
        echo "$field has $value" . PHP_EOL;
      }
      echo PHP_EOL;
    }

The output should be similar to (your "created has" lines will have different times):

    id has 1
    name has First Task
    finish_in_days has
    created has 2014-05-06 17:19:49

    id has 2
    name has Second Task
    finish_in_days has 3
    created has 2014-05-06 17:19:49