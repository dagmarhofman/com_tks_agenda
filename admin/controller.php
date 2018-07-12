<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');


class tks_agendaController extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/tks_agenda.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'newsitems'); //!! was->items (default view?)
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
