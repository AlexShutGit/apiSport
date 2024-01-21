<?php

namespace Controllers;

use Models\ProgramsModel;

class ProgramsController extends BaseController
{
    /**
     * Получение Дней недели
     *
     * @author Alexey Chuev
     * @version 1.0, 23.10.2023
     *
     * @return string
     */
    public function getWeek(array $data): string
    {        
        $model = new ProgramsModel();
        $resultData = $model->getWeek();

        if(!$resultData){
            return $this->redirectToNotFound(['title'=>'error', 'message'=>"Ошибка при получении дней"]);
        }

        return $this->getView(['days' => $resultData]);
    }
}