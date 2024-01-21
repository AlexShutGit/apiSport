<?php

namespace Controllers;

/**
 * Базовый контроллер
 *
 * @author Valery Shibaev
 * @version 1.0, 23.10.2023
 */
class BaseController
{
    /**
     * Отрисовывает шаблон twig
     *
     * @author Valery Shibaev
     * @version 1.0, 08.10.2023
     *
     * @param string $viewPath Путь до шаблоны
     * @param array $data Данные, которые надо передать в шаблон
     * @return string
     */
    public function getView(array $data = []): string
    {
        return json_encode($data, 256);
    }

    /**
     * Метод редиректа на 404
     *
     * @author Valery Shibaev
     * @version 1.0, 08.10.2023
     *
     * @return string
     */
    public function redirectToNotFound(array $data = ['title'=>'error', 'message' => 'Что-то пошло не так :(']): string
    {
        http_response_code(400);
        return json_encode($data, 256);
    }
}