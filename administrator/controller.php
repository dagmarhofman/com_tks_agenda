<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class tks_agendaController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/tks_agenda.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'items');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
	public function item_edit_redirect() {

		$ids = $this->input->get( 'cid' );
		
		if( count($ids) < 1 ) {
			JFactory::getApplication()->enqueueMessage( 'Selecteer slechts een item om te wijzigen.', 'error'); 
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=items', false));
			return;			
		} else if (count($ids) > 1 ) {
			JFactory::getApplication()->enqueueMessage( 'Selecteer slechts een item om te wijzigen.', 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=items', false));
			return; 		
		}	
		
		JFactory::getApplication()->enqueueMessage( 'Wijzig', 'notice'); 
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&layout=edit&view=item&id=' . $ids[0] , false));
	}
}
