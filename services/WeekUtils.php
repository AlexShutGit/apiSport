<?php

namespace Services;

class WeekUtils
{
    /** @var string День недели понедельник  */
    const MONDAY = 'Понедельник';
    /** @var string День недели вторник */
    const TUESDAY = 'Вторник';
    /** @var string День недели среда */
    const WEDNESDAY = 'Среда';
    /** @var string День недели четверг */
    const THURSDAY = 'Четверг';
    /** @var string День недели пятница */
    const FRIDAY = 'Пятница';
    /** @var string День недели суббота */
    const SATURDAY = 'Суббота'; 
    /** @var string День недели воскресенье */
    const SUNDAY = 'Воскресенье';

    /** @var array Дни недели в строком представлении */
    const WEEK_STRINGS = [
        self::MONDAY, 
        self::TUESDAY,
        self::WEDNESDAY,
        self::THURSDAY,
        self::FRIDAY,
        self::SATURDAY,
        self::SUNDAY, 
    ];

    /** @var array Дни недели в представлении данных для отправки */
    const WEEK_DATA_SEND = [
        ['id' => 1, 'name' => self::MONDAY], 
        ['id' => 2, 'name' => self::TUESDAY],
        ['id' => 3, 'name' => self::WEDNESDAY],
        ['id' => 4, 'name' => self::THURSDAY],
        ['id' => 5, 'name' => self::FRIDAY],
        ['id' => 6, 'name' => self::SATURDAY],
        ['id' => 7, 'name' => self::SUNDAY],
    ];
}