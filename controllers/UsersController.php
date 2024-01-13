<?php

namespace Controllers;

use Models\UsersModel;
use Models\usersModel as ModelsUsersModel;

class UsersController extends BaseController
{
    /**
     * Входная точка на главную страницу
     *
     * @author Valery Shibaev
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
     * @author Valery Shibaev
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

        $userModel = new UsersModel();
        $resultData = $userModel->getUser($id);

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['user' => $resultData]);
    }

    public function getFavoritesWorkouts(array $data): string
    {
        $userId = (int) $data['user_id'];
        $page = (int) $data['page'];
        // var_dump($userId, $page);
        if (!$userId || !$page) {
            return $this->redirectToNotFound();
        }

        $userModel = new UsersModel();
        $resultData = $userModel->getFavoritesWorkouts($userId, $page);
        for ($i = 0; $i < count($resultData); $i++) {
            $resultData[$i]['is_favorite']= true ;
        }
        
        if (!$resultData) {
            return $this->redirectToNotFound(['workouts' => []]);
        }

        return $this->getView(['workouts' => $resultData]);
    }

    public function addFavoriteWorkoutUser(array $data): string
    {
        $userId = (int) $data['userId'];
        $workoutId = (int) $data['workoutId'];
        if (!$userId || $workoutId) {
            return $this->redirectToNotFound();
        }

        $userModel = new UsersModel();
        $resultData = $userModel->addFavoriteWorkout($userId, $workoutId);

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['workout' => $resultData]);
    }

    public function deleteFavoriteWorkoutUser(array $data): string
    {
        $userId = (int) $data['userId'];
        $workoutId = (int) $data['workoutId'];
        if (!$userId || $workoutId) {
            return $this->redirectToNotFound();
        }

        $userModel = new UsersModel();
        $resultData = $userModel->deleteFavoriteWorkout($userId, $workoutId);

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['workout' => $resultData]);
    }

    public function searchFavoriteWorkout(array $data): string
    {
        $userId = (int) $data['userId'];
        $keywords = (int) $data['keywords'];
        $page = (int) $data['page'];

        if (!$userId || $keywords || $page) {
            return $this->redirectToNotFound();
        }

        $userModel = new UsersModel();
        $resultData = $userModel->searchFavoritesWorkouts($userId, $page, $keywords);

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['workout' => $resultData]);
    }
}