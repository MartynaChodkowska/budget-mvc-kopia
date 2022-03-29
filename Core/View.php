<?php

namespace Core;

/**
 * View
 *
 * PHP version 7.4.
 */
 
class View
{
	/**
	 * Render a view file
	 *
	 * @param string $view The view file
	 *
	 * @return void
	 */
	 public static function render ($view, $args = [])
	 {
		 extract($args, EXTR_SKIP);
		 
		 $file = "../App/Views/$view"; // relative to Core directory
		 
		 if (is_readable($file)){
			 require $file;
			 } else {
				// echo "$file not found";
				throw new \Exception("$file not found");
			 }
	 }
	
	/**
	 * Render  a view template using Twig
	 *
	 * @param string $template The template file
	 * @param array $args Associative array of data to display in the view (optional)
 	 *
	 * @return void
     */
    public static function renderTemplate(string $template, array $args = [])
    {
        echo static::getTemplate($template, $args);
    }
	
	/**
	 * Get the contents of a view template using Twig
	 *
	 * @param string $template The template file
	 * @param array $args Associative array of data to display in the view (optional)
 	 *
	 * @return string
     */
    public static function getTemplate(string $template, array $args = [])
    {
        static $twig = null;
 
        if ($twig === null)
        {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
			//$twig->addGlobal('session', $_SESSION);
			//$twig->addGlobal('is_logged_in', \App\Auth::isLoggedIn()); 
			$twig->addGlobal('current_user', \App\Auth::getUser());
			$twig->addGlobal('flash_messages', \App\Flash::getMessages());
			//$twig->addGlobal('balance_type', \App\Controllers\Balancesheet::getaction());
        }
 
        return $twig->render($template, $args);
    }
}