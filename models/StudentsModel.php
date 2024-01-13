<?php

namespace Models;

use DataBases\MySqlDatabase;

class usersModel extends BaseModel
{
    /**
     * Отдает всех студентов по запросу
     *
     * @author Valery Shibaev
     * @version 1.0, 24.10.2023
     *
     * @return array
     */
    public function getusers(): array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');

        return $connection->fetchAll('SELECT id, first_name, second_name, direction, birthday FROM users');
    }

    /**
     * Отдает одного студента
     *
     * @author Valery Shibaev
     * @version 1.0, 25.10.2023
     *
     * @param int $id Идентификатор студента
     * @return array
     */
    public function getuser(int $id): array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $query = 'SELECT id, first_name, second_name, direction, birthday FROM users WHERE id = ' . $id;

        return $connection->fetchFirstItem($query);
    }
}