<?php

namespace Controllers;

use Models\SearchModel;

class SearchController extends BaseController
{
    /**
     * Поиск по упражнениям
     *
     * @author Alexey Chuev
     * @version 1.0, 23.10.2023
     *
     * @return string
     */

    function search(array $data)
    {
        $userId = (int) $data['userId'];
        $keywords = (string) $data['text'];
        $type = (string) $data['type'];
        $page = (int) $data['page'];

        if ($keywords != '') {
            $model = new SearchModel();
            if ($type == "workouts") {
                $resultData = $model->searchWorkouts($userId, $page, $keywords);
                for ($i = 0; $i < count($resultData); $i++) {
                    $resultData[$i]['is_favorite'] = (bool) $resultData[$i]['is_favorite'];
                }
            } else {
                $resultData = $model->searchFavoritesWorkouts($userId, $page, $keywords);
                for ($i = 0; $i < count($resultData); $i++) {
                    $resultData[$i]['is_favorite'] = true;
                }
            }
        }
        if (!$resultData) {
            return $this->redirectToNotFound(['workouts' => []]);
        }



        return $this->getView(['workouts' => $resultData]);
    }
}
