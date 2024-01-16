<?php

namespace DataBases;

use PDO;
use PDOException;

class MySqlDatabase implements DataBaseDefault
{
    /** @var array Инстансы баз данных */
    private static array $instances = [];
    /** @var PDO Соединение с базой данных */
    private PDO $connection;

    /**
     * Конструктор класса
     *
     * @author Valery Shibaev
     * @version 1.0, 24.10.2023
     *
     * @param string $dataBaseName
     */
    private function __construct(string $dataBaseName)
    {
        $host = $_ENV['MYSQL_DB_HOST'];
        $dataBaseConnection = $_ENV['MYSQL_DB_CONNECTION'];
        $user = $_ENV['MYSQL_DB_USER'];
        $password = $_ENV['MYSQL_DB_PASSWORD'];

        try {
            $this->connection = new PDO(
                sprintf('%s:host=%s;dbname=%s', $dataBaseConnection, $host, $dataBaseName),
                $user,
                $password
            );
        } catch (PDOException $exception) {
            trigger_error($exception, E_USER_ERROR);
        }
    }

    /** Закрываем методы для синглтона */
    private function __clone() {}
    private function __wakeup() {}
    private function __sleep() {}

    /**
     * Возвращает соединение с БД
     *
     * @author Valery Shibaev
     * @version 1.0, 24.10.2023
     *
     * @param string $dataBase База данных
     * @return DataBaseDefault
     */
    public static function getInstance(string $dataBase = 'api_database'): DataBaseDefault
    {
        if (!isset(self::$instances[$dataBase])) {
            self::$instances[$dataBase] = new self($dataBase);
        }

        return self::$instances[$dataBase];
    }

    /**
     * @inheritDoc
     */
    public function fetchAll(string $query): array
    {
        $data = $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            return [];
        }
        
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function fetchFirstItem(string $query): array
    {
        return $this->connection->query($query)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritDoc
     */
    public function fetchFirstColumn(string $query): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function changeQuery(string $query): bool
    {
        try {
            $preparedQuery = $this->connection->prepare($query);
        if($preparedQuery->execute()) {
            return true;
        }
        return false;    
        } catch (\Throwable $th) {
            return false;
        }
        
    }
}