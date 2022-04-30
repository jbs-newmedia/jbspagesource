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
 * @var \osWFrame\Core\Template $osW_Template
 */

/**
 * Hook top
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'_output_top.inc.php';
if (file_exists($file)) {
	require_once $file;
}

echo $osW_Template->getOutput('index', 'project');

/**
 * Hook bottom
 */
$file=\osWFrame\Core\Settings::getStringVar('settings_abspath').'modules'.DIRECTORY_SEPARATOR.\osWFrame\Core\Settings::getStringVar('frame_default_module').DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'_output_bottom.inc.php';
if (file_exists($file)) {
	require_once $file;
}

?>