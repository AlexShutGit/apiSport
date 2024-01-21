<?php

namespace Models;

use Databases\MySqlDatabase;

class SearchModel extends BaseModel
{
    /**
     * Поиск по упражнениям
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $id Идентификатор студента
     * @return array
     */

    public function searchWorkouts(int $userId, int $page, string $keywords): array
    {
        $recordsPerPage = 30;
        $offset = ($page-1) * $recordsPerPage; 
        
        
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $query = "SELECT *, (SELECT fw.workout_id 
        FROM favorites_workouts fw  
        WHERE fw.user_id = " . $userId . " AND fw.workout_id = w.id) as `is_favorite`
        FROM workouts w
        WHERE name LIKE '%" . $keywords . "%'
        ORDER BY name LIMIT " . $recordsPerPage . " OFFSET " . $offset;

        return $connection->fetchAll($query);
    }
    /**
     * Поиск по избранным упражнениям
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $userId Идентификатор пользователя
     * @param int $page Страница
     * @param int $keywords Текст поиска 
     * @return array
     */
    public function searchFavoritesWorkouts(int $userId, int $page, string $keywords)
    {
        $recordsPerPage = 30;
        $offset = ($page-1) * $recordsPerPage; 
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        
        $query = "SELECT * 
        FROM `workouts` w 
        LEFT JOIN favorites_workouts fw 
        ON w.id = fw.workout_id 
        WHERE fw.user_id = " . $userId . " AND w.name LIKE '%" . $keywords . "%' 
        ORDER BY w.name LIMIT " . $recordsPerPage . " OFFSET " . $offset;

        return $connection->fetchAll($query);
    }
}