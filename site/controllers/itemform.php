<?php

/**
 * @version    1.0.1
 * @package    tks_agenda
 * @author     Dagmar Hofman <stephan@takties.nl>
 * @copyright  Copyright (C) 2016. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */
 
// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Item controller class.
 *
 * @since  1.6
 */
class tks_agendaControllerItemForm extends tks_agendaController
{
	
	/**
 	 * @var	object	$jform			The form object.
 	 * @var	array		$uitval			The dates that cannot be requested.
 	 * @var	date		$startDate		The starting date of the item.
 	 * @var	object	$endDate			The end date of the item.
 	 * @var	object	$endRecurring	The end date of the recurring items.
 	 * @var	object	$model			The model.
 	 * @var	object	$db				The database object.
 	 * @var	array		$data				The form data.
 	 * @var	string	$last_error		Error notice.
 	 */
	var $jform = NULL;

	var $uitval = NULL;	
	var $startDate = NULL;
	var $endDate = NULL;
	var $endRecurring = NULL;
	var $model = NULL;
	var $db = NULL;
	var $data = NULL;	
	var $last_error = NULL;
	
	//vreemd dat deze flag nodig is, dat een setRedirect niet werkt in een method anders dan save?
	var $overlapfail = false;

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_tks_agenda.edit.item.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_tks_agenda.edit.item.id', $editId);

		JFactory::getApplication()->setUserState('com_tks_agenda.edit.item.mode', 'create');
		
		
		// Get the model.
		$this->model = $this->getModel('ItemForm', 'tks_agendaModel');

		// Check out the item
		if ($editId)
		{
			$this->model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$this->model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since  1.6
	 */
	
	public function overlap_check( $is_update = NULL, $id)
	{

		$this->overlapfail = false;

		//eerst check ik de item overlap
		$query = $this->db->getQuery(true);
	
		$query->select($this->db->quoteName(array('a.id','a.start', 'a.end')));
		$query->from($this->db->quoteName('#__tks_agenda_items','a'));
		//if( $id != 0  ) {
			$query->where( $this->db->quotename('a.id') . ' != ' . (int)$id );
		//}		
		$this->db->setQuery($query);
		$this->db->execute();
		$result_a = $this->db->loadObjectList();
		
		/* misschien de  ( (int) $row->id !=  (int) $id  ) in de WHERE clausile afvlaggen? */
		foreach( $result_a as $row ) {

			if( ( $this->startDate->format("Y-m-d H:i:s") > $row->start &&  $this->startDate->format("Y-m-d H:i:s") < $row->end  ) 
				|| ( $this->endDate->format("Y-m-d H:i:s") > $row->start  &&  $this->endDate->format("Y-m-d H:i:s") < $row->end ) 
				|| ( $this->startDate->format("Y-m-d H:i:s") <= $row->start  &&  $this->endDate->format("Y-m-d H:i:s") >= $row->end ) ) {
					if( $id != 0 ) {
						$this->setMessage(JText::sprintf( 'Deze vergaderruimte is al geboekt. ' , $this->model->getError()), 'error');								
						$this->overlapfail = true;
						return false;	
					}		
				} 			
		}

		$query = $this->db->getQuery(true);

		$query->select($this->db->quoteName(array('b.id', 'b.rid','b.rstart', 'b.rend')));
		$query->from($this->db->quoteName('#__tks_agenda_recurring','b'));
	
		$this->db->setQuery($query);
		$this->db->execute();
		$result_b = $this->db->loadObjectList();
		
		foreach( $result_b as $row ) {
			if( ( $this->startDate->format("Y-m-d H:i") > $row->rstart &&  $this->startDate->format("Y-m-d H:i") < $row->rend  ) 
				|| ( $this->endDate->format("Y-m-d H:i") > $row->rstart  &&  $this->endDate->format("Y-m-d H:i") < $row->rend ) 
				|| ( $this->startDate->format("Y-m-d H:i") <= $row->rstart  &&  $this->endDate->format("Y-m-d H:i") >= $row->rend ) ) {
					$this->overlapfail = true;
					$this->setMessage(JText::sprintf( 'Deze vergaderruimte is al geboekt.', $this->model->getError()), 'error');								
					return false;			
					}			
		}		


		//----check nu of de herhaalafspraken ergen overlappen, (zet de overlappende herhaalafspraken in een array, en vraag of die geschapt moeten worden)

		$datearr = $this->get_recur_array();

		$flag = true;
		$i = 0;		
		$j = 0;
		
		$overlaparr[][] = null;
		$overlaparr[0] = null;
		$overlaparr[1] = null;
		
		do {
			
			foreach( $result_a as $row ) {
				if( ( $datearr[0][$i] > $row->start && $datearr[0][$i] < $row->end  ) 
					|| ( $datearr[1][$i] > $row->start  &&  $datearr[1][$i] < $row->end ) 
					|| ( $datearr[0][$i] <= $row->start  &&  $datearr[1][$i] >= $row->end ) ) {
						$overlaparr[0][$j] = $datearr[0][$i];
						$overlaparr[1][$j] = $datearr[1][$i];
						$j++;			
						$flag = false;			
					} 			
			}
	
	
			foreach( $result_b as $row ) {
				if( ( $datearr[0][$i] > $row->rstart && $datearr[0][$i] < $row->rend  ) 
					|| ( $datearr[1][$i] > $row->rstart  &&  $datearr[1][$i] < $row->rend ) 
					|| ( $datearr[0][$i] <= $row->rstart  &&  $datearr[1][$i] >= $row->rend ) ) {
						$overlaparr[0][$j] = $datearr[0][$i];
						$overlaparr[1][$j] = $datearr[1][$i];
						$j++;			
						$flag = false;			
					}			
			}		
	
			$i++;
		} while( $datearr[0][$i] != NULL );	

	
		if( !$flag ) {
			$app = JFactory::getApplication();
			$app->setUserState('com_tks_agenda.edit.item.overlap', $overlaparr);
			$app->setUserState('com_tks_agenda.edit.item.recurring', $datearr);
			return false;							
		}
				
		return true;
	}
	

	/**
	 * Function that checks for date errors
	 * Also sets the error message
	 *
	 * @return  boolean	True for date error	
	 */	
	
	public function check_for_date_errors()
	{
			$firstDate = $this->startDate->format('Y-m-d');
			$secondDate = $this->endDate->format('Y-m-d');

			if( $firstDate != $secondDate ) {
				$this->setMessage(JText::sprintf('Afspraak duurt langer dan tot de volgende dag! dit is niet toegestaan.', $this->model->getError()), 'error');
				return true;			
			}
			
			if( $this->startDate == $this->endDate ) {
				$this->setMessage(JText::sprintf('Afspraak starttijd is dezelfde als de eindtijd, dit is niet toegestaan.', $this->model->getError()), 'error');
				return true;			
			}
			
									
			$interval = $this->startDate->diff($this->endRecurring);
	
			// check if if recurring less than a year
			if ($interval->days >= 365) {
					$this->setMessage(JText::sprintf('De herhaling is meer dan een jaar! Dit is niet toegestaan.', $this->model->getError()), 'error');
					return true;			

			}

			if( $this->endRecurring < $this->startDate && isset($this->jform['recurring']) && $this->jform['recurring'] == "Yes") {
				$this->setMessage(JText::sprintf('De herhaalafspraak tijd is eerder dan de starttijd! Dit is niet toegestaan.', $this->model->getError()), 'error');
				return true;			
			}
			return false;
	}

	/**
	 *	
	 * Calculates array of recurring items.
	 * 
	 * @return	array		Array of recurring dates.
	 */
	public function get_recur_array() 
	{
		$app   = JFactory::getApplication();
		$datesArray[][] = null;
		$datesArray[0][] = null;
		$datesArray[1][] = null;
		
		$i = 0;
		$this->startDate = new DateTime($this->jform['start'],new DateTimeZone("Europe/Amsterdam"));
		$this->endDate = new DateTime($this->jform['end'],new DateTimeZone("Europe/Amsterdam"));
		$this->endRecurring = new DateTime($this->jform['end_recur'],new DateTimeZone("Europe/Amsterdam"));

		$startDateIt = $this->startDate;
		$endDateIt = $this->endDate;
		$endRecurringIt = $this->endRecurring;

		
		while ($endDateIt <= $endRecurringIt ) {

			switch ($this->jform['recur_type']) {
				case 'dag':
					$startDateIt->modify("+1 day");
					$endDateIt->modify("+1 day");
				break;
				case 'week':
					$startDateIt->modify("+1 week");
					$endDateIt->modify("+1 week");
				break;
				case 'maand':
					$startDateIt->modify("+1 month");
					$endDateIt->modify("+1 month");
				break;					 
			}
 				$datesArray[0][$i] = $startDateIt->format("Y-m-d H:i:s");	
 				$datesArray[1][$i] = $endDateIt->format("Y-m-d H:i:s");	
			$i++;
		} ;
		
		
		return $datesArray;
 	}

	
	/**
	 *	
	 * Save all recurring dates in database.
	 * 
	 * @param	integer	$id	id of item to save
	 * @return	array		Array of recurring dates.
	 */
	public function save_recurring_items( $id ) 
	{
			$datesArray = $this->get_recur_array();

			$i = 0;
			do {
				$datesArrayFlat[$i] = $this->db->quote($id).', '. $this->db->quote($datesArray[0][$i]) .', ' . $this->db->quote($datesArray[1][$i]) ;
				$i++;			
			} while( $datesArray[0][$i] != NULL );			
			
			
			$query = $this->db->getQuery(true);
			$columns = array('rid', 'rstart', 'rend');
			$query->insert($this->db->quoteName('#__tks_agenda_recurring'));
			$query->columns($this->db->quoteName($columns));
			$query->values(implode('), (', $datesArrayFlat));
		   $this->db->setQuery($query);
		   $this->db->execute();
			//DAGMAR (check hier voor fouten) ?	
	}
	
	public function bewerk()
	{
		$this->setMessage('Bewerk functie aangeroepen' , 'notice');
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda', false));	
	}
	/**
	 * Method to save a user's profile data.
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since  1.6
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = JFactory::getApplication();
		$this->db = JFactory::getDBO();

		$this->model = $this->getModel('ItemForm', 'tks_agendaModel');
 		
 		// Get the user data.
		$this->data = JFactory::getApplication()->input->get('jform', array(), 'array');
		
		// Validate the posted data.
		$form = $this->model->getForm();

		if (!$form)
		{
			throw new Exception($this->model->getError(), 500);
		}

		// Validate the posted data.
		$this->data = $this->model->validate($form, $this->data);
		$input = $app->input;
		$this->jform = $input->get('jform', array(), 'ARRAY');

  		

		// Check for errors.
			if ($this->data === false)
			{
				// Get the validation messages.
				$errors = $this->model->getErrors();

				// Push up to three validation messages out to the user.
				for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
				{
					if ($errors[$i] instanceof Exception)
					{
						$app->enqueueMessage($errors[$i]->getMessage(), 'error');
					}
					else
					{
						$app->enqueueMessage($errors[$i], 'error');
					}
				}
				// Save the data in the session.
				$app->setUserState('com_tks_agenda.edit.item.data', $this->jform);
				// Redirect back to the edit screen.
				$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
			}
			
		$this->startDate = new DateTime($this->data['start'],new DateTimeZone("Europe/Amsterdam"));
		$this->endDate = new DateTime($this->data['end'],new DateTimeZone("Europe/Amsterdam"));
		$this->endRecurring = new DateTime($this->data['end_recur'],new DateTimeZone("Europe/Amsterdam"));


		$mode = $app->getUserState('com_tks_agenda.edit.item.mode');
		$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
		
		/* !!!!
			Deze vult het formulier niet terug met de vorige gegevens, wat niet gebruikersvriendelijk is.
		*/						
		//date error
		if( $this->check_for_date_errors() ) {
				return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));					
		}
			
		if($mode == 'create' ) {
			if( !$this->overlap_check( false, $id ) )	{
				$app->setUserState('com_tks_agenda.edit.item.id', $id);
				$app->setUserState('com_tks_agenda.edit.item.data', $this->data);
				if( $this->overlapfail) {
					return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));				
				}
				else {
					return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemscrapform', false));
				}			
			}		
		} else if ($mode == 'update') {
			if( !$this->overlap_check( true, $id ) )	{
				$app->setUserState('com_tks_agenda.edit.item.id', $id);
				$app->setUserState('com_tks_agenda.edit.item.data', $this->data);
				if( $this->overlapfail) {
					return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));				
				}
				else {
					return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemscrapform', false));
				}			
			}		
		} else { //this should not happen
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));				
		}


		$recurring_id = $app->getUserState( 'com_tks_agenda.edit.item.recurid' );


		if( $mode == 'create' || $recurring_id == -1 ) {
	
			// Attempt to save the data.
			$return = $this->model->save($this->data);
	
			// Check for errors.
			if ($return === false)
			{
				// Save the data in the session.
				$app->setUserState('com_tks_agenda.edit.item.data', $this->data);
	
				// Redirect back to the edit screen.
				$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
				$this->setMessage(JText::sprintf('Save failed', $this->model->getError()), 'error');
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
			}
				
			if (isset($this->jform['recurring']) && $this->jform['recurring'] == "Yes" ) {
				$this->save_recurring_items( $return );
			}
					
			// Check in the profile.
			if ($return)
			{
				$this->model->checkin($return);
			}
	
			// Clear the profile id from the session.
			$app->setUserState('com_tks_agenda.edit.item.id', null);
		} 
		else if( $mode == 'update' && $recurring_id != -1 ) {
			/* UPDATE HIER OOK de item reason, als deze gewijzigd wordt! */
			$db = JFactory::getDbo();			
			
			$query = $db->getQuery(true);

			// UPDATE recurring set( rstart, rend ) where id = $recurring_id

			
			// Fields to update.
			$fields = array(
    			$db->quoteName('rstart') . ' = ' . $db->quote( $this->jform['start'] ),
    			$db->quoteName('rend') . ' = ' . $db->quote($this->jform['end']  )
			);

			$conditions = array(
 				$db->quoteName('id') . ' = ' . $db->quote( $recurring_id )
			);

			$query->update($db->quoteName('#__tks_agenda_recurring'))->set($fields)->where($conditions);
					
		   $db->setQuery($query);
		   $db->execute();



		}
		
		
		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_TKS_AGENDA_SAVE_SUCCESS ' . $str ));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		// $url  = (empty($item->link) ? 'index.php?option=com_tks_agenda' : $item->link);
		
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda', false));
		
		// Flush the data and mode from the session.
		$app->setUserState('com_tks_agenda.edit.item.data', null);
		$app->setUserState('com_tks_agenda.edit.item.mode', null);
 		
	}



	/**
	 * Method to abort current operation
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function cancel()
	{
		$app = JFactory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_tks_agenda.edit.item.id');

		// Get the model.
		$this->model = $this->getModel('ItemForm', 'tks_agendaModel');

		// Check in the item
		if ($editId)
		{
			$this->model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		// $url  = (empty($item->link) ? 'index.php?option=com_tks_agenda' : $item->link);

		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda', false));
		
		
	}

	/**
	 * Method to remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function remove()
	{
		// Initialise variables.
		$app   = JFactory::getApplication();
		$this->model = $this->getModel('ItemForm', 'tks_agendaModel');

		// Get the user data.
		$this->data       = array();
		$this->data['id'] = $app->input->getInt('id');

		// Check for errors.
		if (empty($this->data['id']))
		{
			// Get the validation messages.
			$errors = $this->model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'error');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'error');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_tks_agenda.edit.item.data', $this->data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=item&layout=edit&id=' . $id, false));
		}

		// Attempt to save the data.
		$return = $this->model->delete($this->data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_tks_agenda.edit.item.data', $this->data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
			$this->setMessage(JText::sprintf('Delete failed', $this->model->getError()), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=item&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$this->model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_tks_agenda.edit.item.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_TKS_AGENDA_ITEM_DELETED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_tks_agenda' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_tks_agenda.edit.item.data', null);
	}

}
