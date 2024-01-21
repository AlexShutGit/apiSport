<?php

class Program
{
    // соединение с БД и таблицей "programs"
    private $conn;
    private $table_name = "programs";

    // свойства объекта
    public $id;
    public $name;
    public $type_id;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения всех категорий товаров
    public function readAll()
    {
        $query = "SELECT
                p.id, p.name, pt.name as program_type, p.description
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                programs_type pt
                    ON p.type_id = pt.id
            ORDER BY
                id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
