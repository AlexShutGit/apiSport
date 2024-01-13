<?php

namespace Controllers;

use Models\UsersModel;

class HomePageController extends BaseController
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
}