<?php

namespace Models;

use Databases\MySqlDatabase;

class UsersModel extends BaseModel
{

    /**
     * Отдает всех пользователй по запросу
     *
     * @author Alexey Chuev
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
     * Отдает пользвателя
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $id Идентификатор пользователя
     * @return array
     */
    public function getUser(int $id): array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $query = 'SELECT id, name, sex, age, height, weight, BMI FROM users WHERE id = ' . $id;

        return $connection->fetchFirstItem($query);
    }

    /**
     * Отдает все проблемы
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param 
     * @return array
     */
    public function getTroubles(): array
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');
        $query = 'SELECT id, name FROM troubles';

        return $connection->fetchAll($query);
    }

    /**
     * Создает нового пользователя
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param string $name Идентификатор студента
     * @param string $sex Идентификатор студента
     * @param int $age Идентификатор студента
     * @param float $height Идентификатор студента
     * @param float $weight Идентификатор студента
     * @param array $troubles Идентификаторы проблем
     * @return array
     */
    public function createUser(string $name, string $sex, int $age, float $height, float $weight, array $troubles)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');

        $BMI = $weight * ($height * $height);

        $queryCreate = 'INSERT INTO users
        SET name=:"' . $name . '", sex=:"' . $sex . '", age=:"' . $age . '", height=:"' . $height . '", weight=:"' . $weight . '", BMI=:"' . $BMI . '"';
        $queryId = 'SELECT * FROM users ORDER BY id DESC LIMIT 1';
        $userId = $connection->fetchFirstItem($queryId);

        foreach ($troubles as $trouble) {
            $queryAddTrouble = 'INSERT INTO users_troubles
            SET user_id=' . $userId . 'trouble_id=' . $trouble;

            $connection->changeQuery($queryAddTrouble);
        }
        

        if($connection->changeQuery($queryCreate)){
            return ['user' => $userId];
        }

        return false;
    }

    /**
     * Отдает избранные упражнения пользователя
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $userId Идентификатор пользователя
     * @param int $page Страница пагинации
     * @return array
     */
    public function getFavoritesWorkouts(int $userId, int $page) : array
    {
        $recordsPerPage = 30;
        $offset = ($page-1) * $recordsPerPage; 
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        
        $query = 'SELECT w.id, w.name, w.description, w.image_url 
        FROM favorites_workouts fw 
        LEFT JOIN workouts w 
        ON w.id = fw.workout_id 
        WHERE fw.user_id = ' . $userId . '
        ORDER BY name LIMIT ' . $recordsPerPage . ' OFFSET ' . $offset;

        return $connection->fetchAll($query);
    }
    
    /**
     * Добавляет в избранные упражнение
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $userId Идентификатор пользователя
     * @param int $userId Идентификатор упражнения
     * @return array
     */
    public function addFavoriteWorkout(int $userId, int $workoutId)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        $query = 'INSERT INTO
        favorites_workouts (`user_id`, `workout_id`)
        VALUES(' . $userId . ', ' . $workoutId .')';

        return $connection->changeQuery($query);
    }

    /**
     * Удаляет из избранных упражнение
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $userId Идентификатор пользователя
     * @param int $workoutId Идентификатор упражнения
     * @return array
     */
    function deleteFavoriteWorkout(int $userId, int $workoutId)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   
        $query = 'DELETE FROM favorites_workouts WHERE user_id=' . $userId . ' AND workout_id='. $workoutId;

        return $connection->changeQuery($query);
    }

    /**
     * Добавляет логин и пароль пользователю
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param int $userId Идентификатор пользователяы
     * @param string $login Идентификатор пользователя
     * @param string $password Пароль пользователя 
     * @return array
     */
    function registation(int $userId, string $login, string $password)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database');   

        $queryCheck = 'SELECT id FROM users WHERE login = "' . $login . '"';
        $queryRegistration = 'UPDATE users
        SET login = "' . $login . '", password = "' . $password . '"
        WHERE id = ' . $userId;
        
        $isFree = (bool) $connection->fetchFirstItem($queryCheck);

        if(!$isFree){
            $connection->changeQuery($queryRegistration);
        } else {
            return ['message' => 'Логин уже занят'];
        }
        
        return ['message' => 'Неизвестная ошибка регистрации'];
    }

    /**
     * Авторизация пользователя
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @param string $login Идентификатор пользователя
     * @param string $password Пароль пользователя 
     * @return array
     */
    function authorization(string $login, string $password)
    {
        $connection = $this->getConnection(MySqlDatabase::class, 'api_database'); 

        $query = 'SELECT id, name, sex, age, height, weight, BMI 
        FROM users 
        WHERE login = "' . $login . '" AND password = "' . $password . '"';

        return $connection->fetchAll($query);
    }
}