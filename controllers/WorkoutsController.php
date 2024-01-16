<?php

namespace Controllers;

use Models\WorkoutsModel;

class WorkoutsController extends BaseController
{
    /**
     * Получение упражнений
     *
     * @author Alexey Chuev
     * @version 1.0, 23.10.2023
     *
     * @return string
     */
    public function getWorkouts(array $data): string
    {
        $userId = (int) $data['userId'];
        $page = (int) $data['page'];

        if(!$userId || !$page){
            return $this->redirectToNotFound(['workouts' => []]);
        }
        
        $model = new WorkoutsModel();
        $resultData = $model->getWorkouts($userId, $page);

        for ($i = 0; $i < count($resultData); $i++) {
            $resultData[$i]['is_favorite'] = (bool) $resultData[$i]['is_favorite'];
        }

        if(!$resultData){
            return $this->redirectToNotFound(['workouts' => []]);
        }

        return $this->getView(['workouts' => $resultData]);
    }
}