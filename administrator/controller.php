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
		
		$id = $ids[0];
		if( $id < 536870911 ) {
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&layout=edit&recur_edit=false&view=item&id=' . $ids[0] , false));
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&layout=edit&recur_edit=true&view=item&id=' . $ids[0] , false));
		}


	}
	
	public function item_delete_redirect() {
	
		$ids = $this->input->get( 'cid' );

		/*
			de recurring ids beginnen op een hoog getal!
			de kleine getallen in $ids zijn dan gewone afspraken.
			
			ON DELETE CASCADE de ITEMs
			delete de recurring items apart.
		
			( Dit alles zou eigenlijk via de JTable structuur moeten gaan, dit is een "kludge" )		
		*/

		foreach( $ids as $id ) {
			$db = JFactory::getDbo();			
			$query = $db->getQuery(true);

			if( $id < 536870911 ) {
				$query->delete($db->quoteName('#__tks_agenda_items'));
			} else {
				$query->delete($db->quoteName('#__tks_agenda_recurring'));
			}

			$conditions = array(
    			$db->quoteName('id') . ' = ' . $id, 
  			);			
			$query->where($conditions);

		   $db->setQuery($query);
		   $db->execute();
		}

		JFactory::getApplication()->enqueueMessage( 'Items deleted' , 'notice'); 
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&layout=edit&view=items', false));
		
		return;		
	}
	
}
