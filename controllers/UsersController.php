<?php

namespace Controllers;

use Models\UsersModel;

class UsersController extends BaseController
{
    /**
     * Входная точка на главную страницу
     *
     * @author Alexey Chuev
     * @version 1.0, 23.10.2023
     *
     * @return string
     */
    public function index(): string
    {
        $model = new UsersModel();
        $resultData = $model->getUsers();
        return $this->getView(['users' => $resultData]);
    }

    /**
     * Отдает конкретного пользователя
     *
     * @author Alexey Chuev
     * @version 1.0, 08.10.2023
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function getConcreteUser(array $data): string
    {
        $id = (int) $data['userId'];
        if (!$id) {
            return $this->redirectToNotFound();
        }

        $model = new UsersModel();
        $resultData = $model->getUser($id);

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['user' => $resultData]);
    }

     /**
     * Отдает все проблемы
     *
     * @author Alexey Chuev
     * @version 1.0, 08.10.2023
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function getTroubles(array $data): string
    {
        $model = new UsersModel();
        $resultData = $model->getTroubles();

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['troubles' => $resultData]);
    }

    /**
     * Создает нового пользователя
     *
     * @author Chuev Alexey
     * @version 1.0, 13.01.2024
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function createUser(array $data): string
    {
        $name = $data['name'];
        $age = $data['age'];
        $sex = $data['sex'];
        $height = $data['height'];
        $weight = $data['weight'];
        if (!$name || !$age || !$sex || !$height || !$weight) {
            return $this->redirectToNotFound(['message' => 'Заполните все поля']);
        }

        $model = new UsersModel();
        $resultData = $model->createUser($name, $age, $sex, $height, $weight);

        if (!$resultData) {
            return $this->redirectToNotFound(['message' => 'Неизвестная ошибка создания пользователя']);
        }

        return $this->getView(['user' => $resultData]);
    }

    /**
     * Получение избранных упражнений
     *
     * @author Chuev Alexey
     * @version 1.0, 13.01.2024
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function getFavoritesWorkouts(array $data): string
    {
        $userId = (int) $data['userId'];
        $page = (int) $data['page'];
        if (!$userId || !$page) {
            return $this->redirectToNotFound(['workouts' => []]);
        }

        $model = new UsersModel();
        $resultData = $model->getFavoritesWorkouts($userId, $page);
        for ($i = 0; $i < count($resultData); $i++) {
            $resultData[$i]['is_favorite'] = true;
        }

        if (!$resultData) {
            return $this->redirectToNotFound(['workouts' => []]);
        }

        return $this->getView(['workouts' => $resultData]);
    }

    /**
     * Добавляет в упражнение в избранные
     *
     * @author Chuev Alexey
     * @version 1.0, 13.01.2024
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function addFavoriteWorkoutUser(array $data): string
    {
        $userId = (int) $data['userId'];
        $workoutId = (int) $data['workoutId'];
        
        if (!$userId || !$workoutId) {
            return $this->redirectToNotFound(['value' => false]);
        }

        $model = new UsersModel();
        $resultData = $model->addFavoriteWorkout($userId, $workoutId);

        if (!$resultData) {
            
            return $this->redirectToNotFound(['value' => false]);
        }

        return $this->getView(['value' => true]);
    }

    /**
     * Удаляет упражнение из избранных
     *
     * @author Chuev Alexey
     * @version 1.0, 13.01.2024
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function deleteFavoriteWorkoutUser(array $data): string
    {
        $userId = (int) $data['userId'];
        $workoutId = (int) $data['workoutId'];
        if (!$userId || !$workoutId) {
            return $this->redirectToNotFound(['value' => false]);
        }

        $model = new UsersModel();
        $resultData = $model->deleteFavoriteWorkout($userId, $workoutId);

        if (!$resultData) {
            return $this->redirectToNotFound(['value' => false]);
        }

        return $this->getView(['value' => true]);
    }

    /**
     * Добавление пользователю логина и пароля
     *
     * @author Chuev Alexey
     * @version 1.0, 13.01.2024
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function registration(array $data): string
    {
        $userId = (int) $data['userId'];
        $login = (string) $data['login'];
        $password = (string) $data['password'];
        
        if (!$userId || !$login || !$password) {
            return $this->redirectToNotFound(['value' => false]);
        }

        $model = new UsersModel();
        $resultData = $model->registation($userId, $login, $password);

        if ($resultData['message']) {
            return $this->redirectToNotFound($resultData);
        }

        return $this->getView(['value' => true]);
    }

    /**
     * Авторизация пользователя
     *
     * @author Chuev Alexey
     * @version 1.0, 13.01.2024
     *
     * @param array $data Приходящие данные из uri
     * @return string
     */
    public function authorization(array $data): string
    {
        $login = (string) $data['login'];
        $password = (string) $data['password'];
        
        if (!$login || !$password) {
            return $this->redirectToNotFound(['user' => [`id` => 0]]);
        }

        $model = new UsersModel();
        $resultData = $model->authorization($login, $password);

        if (!$resultData) {
            return $this->redirectToNotFound(['value' => false]);
        }

        return $this->getView($resultData);
    }
}
