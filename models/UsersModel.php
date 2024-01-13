<?php

namespace Models;

use Databases\MySqlDatabase;

class UsersModel extends BaseModel
{

    /**
     * Отдает всех пользователй по запросу
     *
     * @author Valery Shibaev
     * @version 1.0, 24.10.2023
     *
     * @return array
     */
    public function getUsers(): array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        
        return $connection->fetchAll('SELECT id, name, sex, age, height, weight, BMI FROM users');
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
    public function getUser(int $id): array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $query = 'SELECT id, name, sex, age, height, weight, BMI FROM users WHERE id = ' . $id;

        return $connection->fetchFirstItem($query);
    }

    public function getFavoritesWorkouts(int $userId, int $page) : array
    {
        $records_per_page = 30;
        $offset = ($page-1) * $records_per_page; 
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        
        $query = 'SELECT w.id, w.name, w.description, w.image_url 
        FROM favorites_workouts fw 
        LEFT JOIN workouts w 
        ON w.id = fw.workout_id 
        WHERE fw.user_id = ' . $userId . '
            ORDER BY
                name LIMIT ' . $records_per_page . ' OFFSET ' . $offset;

        return $connection->fetchAll($query);
    }

    public function addFavoriteWorkout(int $userId, int $workoutId)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        $query = 'INSERT INTO
            favorites_workouts
        SET
            user_id=' . $userId . ', workout_id=' . $workoutId;

        return $connection->changeQuery($query);
    }

    function deleteFavoriteWorkout(int $userId, int $workoutId)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        $query = 'DELETE FROM favorites_workouts WHERE user_id=' . $userId . ' AND workout_id='. $workoutId;

        return $connection->changeQuery($query);
    }

    public function searchFavoritesWorkouts(int $userId, int $page, string $keywords)
    {
        $records_per_page = 30;
        $offset = ($page-1) * $records_per_page; 
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        
        $query = "SELECT * 
        FROM `workouts` w 
        LEFT JOIN favorites_workouts fw 
        ON w.id = fw.workout_id 
        WHERE fw.user_id = " . $userId . " AND w.name LIKE '%" . $keywords . "%' 
        ORDER BY
            w.name LIMIT " . $records_per_page . " OFFSET " . $offset;

        return $connection->fetchAll($query);
    }
}