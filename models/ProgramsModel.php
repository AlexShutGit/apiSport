<?php

namespace Models;

use Databases\MySqlDatabase;
use Services\WeekUtils;

class ProgramsModel extends BaseModel
{
     /**
     * Отдает дни недели
     *
     * @author Alexey Chuev
     * @version 1.0, 25.10.2023
     *
     * @return array
     */
    public function getWeek(): array
    {
        return WeekUtils::WEEK_DATA_SEND;
    }

    public function getProgram() : array
    {
        return [];
    }

    public function createProgram(string $program) : array
    {

        return [];
    } 
}