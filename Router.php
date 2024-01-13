<?php

use Controllers\{HomePageController, NotFoundController, UsersController, WorkoutsController};

class Router
{
	/** @var string Класс контроллера, который мы создаем */
	public $controllerClass;
	/** @var string Вызываемый метод */
	public $method;
	/** @var array|string  */
	public $data = [];

	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';

	/** Маршруты для проверки */
	const ROUTE_URLS = [
		'GET' => [
			'/users' => [HomePageController::class, 'getConcreteUser'],
			'/test/users' => [HomePageController::class, 'getConcreteUser'],
		],
		'POST' => [
			'/' => [HomePageController::class, 'index'],
		],
		'/' => [HomePageController::class, 'index'],
		'/users/' => [UsersController::class, 'index'],
		'/users/{userId}/' => [UsersController::class, 'getConcreteUser'],
		'/users/{userId}{page}' => [UsersController::class, 'getFavoritesWorkouts'],
		'/users/addFavoriteWorkout/{userId}{workoutId}/' => [UsersController::class, 'addFavoriteWorkoutUser'],
		'/users/deleteFavoriteWorkout/{userId}{workoutId}/' => [UsersController::class, 'deleteFavoriteWorkoutUser'],
		'/users/{userId}{page}{keywords}' => [UsersController::class, 'searchFavoriteWorkout'],

		'/workouts/{userId}{page}/' => [WorkoutsController::class, 'getWorkouts'],
		'/workouts/{userId}{page}{keywords}/' => [WorkoutsController::class, 'search'],
	];

	/**
	 * Конструктор класса
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 08.10.2023
	 *
	 * @param string $uri Проверяемый URI
	 */
	public function __construct(string $uri)
	{
		$parsedUri = $this->parseUriToSchema($uri);
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
	private function parseUriToSchema(string $uri): array
	{
		if (!$uri) {
			return [NotFoundController::class, 'index'];
		}

		if (!str_ends_with($uri, '/')) {
			$uri .= '/';
		}

		$requestMethod = $_SERVER['REQUEST_METHOD'];

		foreach (self::ROUTE_URLS[$requestMethod] as $routePattern => $controllerData) {
			if (!str_ends_with($routePattern, '/')) {
				$routePattern .= '/';
			}

			$explodedUri = explode('/', $routePattern);
			$parsedUri = implode('\/', $explodedUri);
			$parsedUri = '/' . $parsedUri . '/ui';
			$parsedUrl = parse_url($uri);

			if (preg_match($parsedUri, $parsedUrl['path']) && !preg_filter($parsedUri, '', $parsedUrl['path'])) {
				$data = $this->prepareData($requestMethod, $uri);

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
	public static function getRouter(string $uri): ?Router
	{
		if (!$uri) {
			return null;
		}

		return new self($uri);
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
			default:
				return [];
		}
	}
}