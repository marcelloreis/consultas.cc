<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as 
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

Cache::config('default', array('engine' => 'File'));

/**
 * You can attach event listeners to the request lifecyle as Dispatcher Filter . By Default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	// 'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

/**
 *Configuracao e liberacao dos plugin que serao usados na aplicacao
 */
CakePlugin::load('Main', array('bootstrap' => true));
CakePlugin::load('Google', array('bootstrap' => true));
CakePlugin::load('Facebook', array('bootstrap' => true));
CakePlugin::load('Dompdf', array('bootstrap' => true));
CakePlugin::load('Boletos');
CakePlugin::load('Upload');
CakePlugin::load('DebugKit');

/**
 * PAGINATION
 */
define('LIMIT', 20);

/**
 * Diretorios 
 */
define('PATH_WEBROOT', dirname(dirname(__FILE__)) . DS . 'webroot');

/**
 * IDs padroes do sistema 
 */
//Grupos
define('ADMIN_GROUP', 1);
define('GOOGLE_GROUP', 2);
define('FACEBOOK_GROUP', 3);
define('CLIENT_GROUP', 4);
//Status
define('STATUS_ACTIVE', 1);
//Usuarios
define('ADMIN_USER', 1);
//Lixeira
define('ACTION_TRASH', 'trashed');
define('ACTION_DELETE', 'deleted');
//IDs padroes
define('FEMALE', 1);
define('MALE', 2);

/**
* Templates das mensagens
*/
define('FLASH_SESSION_FORM', 'session_form');
define('FLASH_SESSION_LOGIN', 'session_login');
define('FLASH_TEMPLATE', 'Components/flash-message');
define('FLASH_TEMPLATE_DASHBOARD', 'Components/flash-message-dashboard');

/**
* Classe de status das mensagens
*/
define('FLASH_CLASS_INFO', 'alert-info');
define('FLASH_CLASS_SUCCESS', 'alert-success');
define('FLASH_CLASS_ERROR', 'alert-error');
define('FLASH_CLASS_ALERT', 'alert');

/**
* Mensagens padroes
*/
define('FLASH_SAVE_SUCCESS', 'Formulário salvo com sucesso.');
define('FLASH_SAVE_ERROR', 'Não foi possível salvar os dados do formulário.');
define('FLASH_SAVE_ALERT', 'Formar salvo, mas com falhas.');

/**
* Importa o bootstrap do projeto
*/
require_once 'bootstrap_app.php';





