<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @version    1.0.1
 * @package    com_tks_agenda
 * @author     Dagmar Hofman <stephan@takties.nl>
 * @copyright  Copyright (C) 2018. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of later; See LICENSE.txt
 */


/**
 * The tks_agendaController is the main comtroller for the administrator view.
 */
class tks_agendaController extends JControllerLegacy
{
	/**
	 *
	 * The display routine. By default the main controller routes to the ITEMS view.
	 *
	 * @param 	boolean	$cachable	If true, the view output will be cached
	 *
	 * @param	array		$urlparams	An array of safe URL parameters and their variable types.
	 *
	 *	@return	object	A \JControllerLegacy object to support chaining.	
	*/
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT . '/helpers/tks_agenda.php';

		$view = JFactory::getApplication()->input->getCmd('view', 'items');
		JFactory::getApplication()->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
		
	/**
	 *
	 * The item edit redirect routine. This routine checks if only one item is selected.
	 * It then redirects to the ITEM view or else, it redirects back to the ITEMS view.
	 *
	 *	@return	void 	
	*/
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
		
		$session = &JFactory::getSession();
		$session->set("last_edit_redirect_id", $ids[0] );

		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&layout=edit&view=item' , false));

	}
	
	/**
	 *
	 * The item delete redirect routine. This routine deletes all selected items.
	 * It constructs and runs the query per result. Perhaps we should do it all in one query?
	 * It then redirects to the ITEMS view.
	 *
	 *	@return	void 	
	*/
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
