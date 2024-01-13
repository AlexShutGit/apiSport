<?php

namespace Models;

use Databases\MySqlDatabase;

class WorkoutsModel extends BaseModel
{
    /**
     * Отдает одного студента
     *
     * @author Valery Shibaev
     * @version 1.0, 25.10.2023
     *
     * @param int $id Идентификатор студента
     * @return array
     */

    public function getWorkouts(int $userId, int $page) : array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $records_per_page = 30;
        $offset = ($page-1) * $records_per_page; 
        
        $query = 'SELECT *, (SELECT fw.workout_id 
        FROM favorites_workouts fw  
        WHERE fw.user_id = ' . $userId . ' AND fw.workout_id = w.id) as `is_favorite` 
        FROM `workouts` w ORDER BY name LIMIT ' . $records_per_page . ' OFFSET ' . $offset;

        return $connection->fetchAll($query);
    }

    public function searchWorkouts(int $userId, int $page, string $keywords): array
    {
        $records_per_page = 30;
        $offset = ($page-1) * $records_per_page; 
        
        
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $query = "SELECT *, (SELECT fw.workout_id 
        FROM favorites_workouts fw  
        WHERE fw.user_id = " . $userId . " AND fw.workout_id = w.id) as `is_favorite`
        FROM workouts w
        WHERE
            name LIKE '%" . $keywords . "%'
        ORDER BY
            name LIMIT " . $records_per_page . " OFFSET " . $offset;

        return $connection->fetchAll($query);
    }
}