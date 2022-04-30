<?php

/**
 * This file is part of the JBSPage package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package JBSPage
 * @link https://oswframe.com
 * @license MIT License
 *
 */

/**
 * Create Template
 */
$osW_Template=new \osWFrame\Core\Template();

/**
 * Create Navigation
 */
$Navigation=new \JBSPage\Runtime\Navigation();
$osW_Template->setVar('Navigation', $Navigation);

/**
 * Hook top
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'_engine_top.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/**
 * Header
 */
\osWFrame\Core\Network::sendHeader('Content-Type: text/html; charset=utf-8');
$osW_Template->addVoidTag('base', ['href'=>\osWFrame\Core\Settings::getStringVar('project_domain_full')]);
$osW_Template->addVoidTag('meta', ['charset'=>'utf-8']);
$osW_Template->addVoidTag('meta', ['http-equiv'=>'X-UA-Compatible', 'content'=>'IE=edge']);
$osW_Template->addVoidTag('meta', ['name'=>'viewport', 'content'=>'width=device-width, initial-scale=1, shrink-to-fit=no']);

/**
 * Database
 */
if (strlen(\osWFrame\Core\Settings::getStringVar('database_server')>0)) {
	\osWFrame\Core\DB::addConnectionMYSQL(\osWFrame\Core\Settings::getStringVar('database_server'), \osWFrame\Core\Settings::getStringVar('database_username'), \osWFrame\Core\Settings::getStringVar('database_password'), \osWFrame\Core\Settings::getStringVar('database_db'), \osWFrame\Core\Settings::getStringVar('database_port'));
	\osWFrame\Core\DB::connect();
}

/**
 * Set module
 */
\osWFrame\Core\Settings::setStringVar('frame_current_module', \osWFrame\Core\Settings::getStringVar('frame_default_module'));

/**
 * Language check
 */
$language_available=\osWFrame\Core\Language::getAvailableLanguages();
if ((\osWFrame\Core\Settings::getStringVar('jbspage_language_detect')==null)||(\osWFrame\Core\Settings::getStringVar('jbspage_language_detect')=='path')) {
	$language=\osWFrame\Core\Settings::catchStringGetValue('language');
	if (($language!=null)&&(isset($language_available[$language]))) {
		\osWFrame\Core\Language::setCurrentLanguage($language_available[$language]);
	}
} elseif (\osWFrame\Core\Settings::getStringVar('jbspage_language_detect')=='domain') {
	/*
	 * sub domain
	 */
} elseif (\osWFrame\Core\Settings::getStringVar('jbspage_language_detect')=='session') {
	$language=\osWFrame\Core\Session::getStringVar('language');
	if ($language!=null) {
		\osWFrame\Core\Language::setCurrentLanguage($language);
	}
	\osWFrame\Core\Session::setStringVar('language', \osWFrame\Core\Language::getCurrentLanguage());

	$language=\osWFrame\Core\Settings::catchStringValue('language');
	if ($language!='') {
		\osWFrame\Core\Language::setCurrentLanguage($language);
		\osWFrame\Core\Session::setStringVar('language', \osWFrame\Core\Language::getCurrentLanguage());
	}
	$language=\osWFrame\Core\Session::getStringVar('language');
}

\osWFrame\Core\Language::loadLanguageFile('core');

/**
 * Hook middle
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'_engine_middle.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/**
 * Check page exists
 */
$Navigation->setPage($Navigation->validatePage(\osWFrame\Core\Language::getNameModule(\osWFrame\Core\Settings::catchStringValue('page'))));
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.$Navigation->getPage().'.inc.php';
if (\osWFrame\Core\Filesystem::existsFile($file)!==true) {
	$Navigation->setPage('error');
}

/**
 * Hook logic
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'_engine_logic.inc.php';
if (file_exists($file)) {
	require_once $file;
}

/**
 * FavIcon
 */
$osW_FavIcon=new \osWFrame\Core\FavIcon('modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('jbspage_favicon_logo'), $osW_Template);
$osW_FavIcon->setIcons2Template();

/**
 * Run page
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_current_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.$Navigation->getPage().'.inc.php';
if (file_exists($file)) {
	include_once $file;
	$osW_Template->setVarFromFile('content', $Navigation->getPage(), \osWFrame\Core\Settings::getStringVar('frame_current_module'));
}

/**
 * Validate URL if page exists
 */
if ($Navigation->getPage()!='error') {
	$Navigation->checkUrl();
}

/**
 * Hook bottom
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'_engine_bottom.inc.php';
if (file_exists($file)) {
	require_once $file;
}

?>