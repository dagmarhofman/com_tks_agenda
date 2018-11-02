<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Main Controller class
 *	Displays the items view at first
 *
 */
class tks_agendaController extends JControllerLegacy
{

 /**
	* Display method
	*
	* @param   boolean	$cachable	 	
	* @param   boolean 	$urlparams  
	*
	* @see JTable::_getAssetParentId
	*
	* @return mixed The id on success, false on failure.
  */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/tks_agenda.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'items'); 
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
