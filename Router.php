<?php

use Controllers\{HomePageController, NotFoundController, ProgramsController, SearchController, UsersController, WorkoutsController};

class Router
{
	/** @var string Класс контроллера, который мы создаем */
	public $controllerClass;
	/** @var string Вызываемый метод */
	public $method;
	/** @var array|string  */
	public $data = [];
    /** @var string HTTP метод GET  */
	const METHOD_GET = 'GET';
    /** @var string HTTP метод POST */
	const METHOD_POST = 'POST';
    /** @var string HTTP метод DELETE */
	const METHOD_DELETE = 'DELETE';

	/** Маршруты для проверки */
	const ROUTE_URLS = [
		'GET' => [
            '/api/v1/user' => [UsersController::class, 'getUser'],
			'/api/v1/user/troubles' => [UsersController::class, 'getTroubles'],
            '/api/v1/user/favoritesWorkouts' => [UsersController::class, 'getFavoritesWorkouts'],

            '/api/v1/workouts' => [WorkoutsController::class, 'getWorkouts'],

            '/api/v1/search/workouts' => [SearchController::class, 'search'],

			'/api/v1/programs/week' => [ProgramsController::class, 'getWeek'],
		],
		'POST' => [
            '/api/v1/user/create' => [UsersController::class, 'createUser'],
			'/api/v1/user/addFavoriteWorkout' => [UsersController::class, 'addFavoriteWorkoutUser'],
			'/api/v1/user/registration' => [UsersController::class, 'registration'],
			'/api/v1/user/authorization' => [UsersController::class, 'authorization'],
		],
        'DELETE' => [
            '/api/v1/user/deleteFavoriteWorkout' => [UsersController::class, 'deleteFavoriteWorkoutUser'],
        ]
	];

	/**
	 * Конструктор класса
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 08.10.2023
	 *
	 * @param string $uri Проверяемый URI
     * @param string $method HTTP метод
	 */
	public function __construct(string $uri, string $method)
	{
		$parsedUri = $this->parseUriToSchema($uri, $method);
		[$this->controllerClass, $this->method] = $parsedUri;
		if (isset($parsedUri['data']) && $parsedUri['data']) {
			$this->data = $parsedUri['data'];
		} else {
			$this->data = [];
		}
	}

	/**
	 * По передаваемомум uri сравнивает с роутером и парсит в переменные параметры
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 08.10.2023
	 *
	 * @param string $uri Проверяемый URI
	 * @return array
	 */
	private function parseUriToSchema(string $uri, string $method): array
	{
		if (!$uri) {
			return [NotFoundController::class, 'index'];
		}

		foreach (self::ROUTE_URLS[$method] as $routePattern => $controllerData) {

			$explodedUri = explode('/', $routePattern);
			$parsedUri = implode('\/', $explodedUri);
			$parsedUri = '/' . $parsedUri . '/ui';
			$parsedUrl = parse_url($uri);

			if (preg_match($parsedUri, $parsedUrl['path']) && !preg_filter($parsedUri, '', $parsedUrl['path'])) {
				$data = $this->prepareData($method, $uri);

				return array_merge($controllerData, ['data' => $data]);
			}
		}

		return [NotFoundController::class, 'index'];
	}

	/**
	 * Отдает роутер по URI
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 08.10.2023
	 *
	 * @param string $uri Проверяемый URI
	 * @return Router|null
	 */
	public static function getRouter(string $uri, string $method): ?Router
	{
		if (!$uri) {
			return null;
		}

		return new self($uri, $method);
	}

	/**
	 * Подготавливает данные
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 13.01.2023
	 *
	 * @param string $requestMethod Запрашиваемый метод: GET, POST
	 * @param string $uri Ссылка
	 * @return array
	 */
	private function prepareData(string $requestMethod, string $uri): array
	{
		parse_str(parse_url($uri)['query'], $output);

		switch ($requestMethod) {
			case self::METHOD_POST:
				$postData = json_decode(file_get_contents('php://input',true), true);
				return array_merge($postData, $output);
			case self::METHOD_GET:
				return $output;
            case self::METHOD_DELETE:
                $postData = json_decode(file_get_contents('php://input',true), true);
				return array_merge($postData, $output);
			default:
				return [];
		}
	}
}