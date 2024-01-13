<?php

/**
 * Класс для загрузки подключаемых файлов
 *
 * @author Valery Shibaev
 * @version 1.0, 18.12.2023git@github.com:devsug/testing_testov.git
 */
class AutoLoader
{
	/**
	 * Регистрирует передаваемые классы
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 18.12.2023
	 *
	 * @return bool
	 */
	public function register(): bool
	{
		return spl_autoload_register([new self(), 'autoload']);
	}

	/**
	 * Загружает передаваемые классы
	 *
	 * @author Valery Shibaev
	 * @version 1.0, 18.11.2023
	 *
	 * @param string $namespaceClass Имя из namespace
	 * @return void
	 *
	 */
	private function autoload(string $namespaceClass): void
	{
		$explodedNamespace = explode('\\', $namespaceClass);
		$className = array_pop($explodedNamespace);
		$preparedNamespace = strtolower(implode('/', $explodedNamespace));
		$namespace = $preparedNamespace ? $preparedNamespace . '/' : $preparedNamespace;
		$filePath = __DIR__ . '/' . $namespace . $className . '.php';

		if ($filePath) {
			require_once $filePath;
		}
	}
}