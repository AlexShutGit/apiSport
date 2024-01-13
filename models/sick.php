<?php

class Sick
{
    // соединение с БД и таблицей 'workouts'
    private $conn;
    private $table_name = 'sicks';

    // свойства объекта
    public $id;
    public $name;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения всех упражнений
    public function readAll()
    {
        $query = 'SELECT
                id, name
            FROM
                ' . $this->table_name . '
            ORDER BY
                name';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
