<?php

namespace Controllers;

use Models\WorkoutsModel;

class WorkoutsController extends BaseController
{
    /**
     * Входная точка на главную страницу
     *
     * @author Valery Shibaev
     * @version 1.0, 23.10.2023
     *
     * @return string
     */
    public function getWorkouts(array $data): string
    {
        $userId = (int) $data['userId'];
        $page = (int) $data['page'];

        $model = new WorkoutsModel();
        $resultData = $model->getWorkouts($userId, $page);

        return $this->getView(['workouts' => $resultData]);
    }
    
    function search(array $data)
    {
        $userId = (int) $data['userId'];
        $page = (int) $data['page'];
        $keywords = (string) $data['keywords'];

        $userModel = new WorkoutsModel();
        $resultData = $userModel->searchWorkouts($userId, $page, $keywords);

        if (!$resultData) {
            return $this->redirectToNotFound();
        }

        return $this->getView(['workouts' => $resultData]);
    }
}