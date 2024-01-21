<?php

namespace Models;

use Databases\MySqlDatabase;

class WorkoutsModel extends BaseModel
{
    /**
     * Отдает все упражнения
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $id Идентификатор студента
     * @return array
     */

    public function getWorkouts(int $userId, int $page) : array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $recordsPerPage = 30;
        $offset = ($page-1) * $recordsPerPage; 
        
        $query = 'SELECT *, (SELECT fw.workout_id 
        FROM favorites_workouts fw  
        WHERE fw.user_id = ' . $userId . ' AND fw.workout_id = w.id) as `is_favorite` 
        FROM `workouts` w ORDER BY name LIMIT ' . $recordsPerPage . ' OFFSET ' . $offset;

        return $connection->fetchAll($query);
    }
}