<?php

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

//JLoader::register('tks_agendaFrontendHelper', JPATH_COMPONENT . '/helpers/tks_agenda.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('tks_agenda');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
