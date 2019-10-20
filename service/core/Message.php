<?php
namespace Core;

class Message
{
    static $table_name = 'messages';
    static $db_connection;
    public $topic;
    public $author;
    public $date;
    public $text;

    public function __construct(string $topic, string $author, int $date, string $text)
    {
        $this->topic = $topic;
        $this->author = $author;
        $this->date = $date;
        $this->text = $text;
    }

    static function setDbConnection(\PDO $db_connection)
    {
        self::$db_connection = $db_connection;
    }

    static function prepareDatabase()
    {
        self::createTable();
        self::createFunction();
    }

    static function createTable()
    {
        $table_exists = self::$db_connection->query("SELECT to_regclass('public.". self::$table_name ."');")->fetch();
        if( is_null($table_exists["to_regclass"]) ) {
            print_r("Creating table '". self::$table_name ."'... \n");
            self::$db_connection->query("CREATE TABLE ". self::$table_name ." (
                id SERIAL PRIMARY KEY,
                topic VARCHAR(125) NOT NULL,
                author VARCHAR(125) NOT NULL,
                date INT NOT NULL,
                text TEXT NOT NULL
            )");
        } else {
            print_r("Table '". self::$table_name ."' exists! \n");
        }
    }

    static function createFunction()
    {
        print_r("Creating function 'message_insert'... \n");
        self::$db_connection->query("CREATE FUNCTION message_insert(topic VARCHAR(125), author VARCHAR(125), date INT, text TEXT) RETURNS VOID AS
        $$
        BEGIN
            INSERT INTO ". self::$table_name ." (topic, author, date, text)
            VALUES ( topic,  author,  date,  text);
        END
        $$ 
        LANGUAGE plpgsql;");
    }

    public function save()
    {
//        $query = self::$db_connection->prepare("INSERT INTO ". self::$table_name ."(topic, author, date, text) VALUES (:topic, :author, :date, :text)");
        $query = self::$db_connection->prepare('SELECT public."message_insert"(:topic, :author, :date, :text)');
        $query->execute([
            'topic' => $this->topic,
            'author' => $this->author,
            'date' => $this->date,
            'text' => $this->text,
        ]);
        return self::$db_connection->lastInsertId();
    }
}