<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Gckloosterveen
 * @author     Stephan Zuidberg <stephan@takties.nl>
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

		// Get the model.
		$model = $this->getModel('ItemForm', 'tks_agendaModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
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
		$db = JFactory::getDBO();

		$model = $this->getModel('ItemForm', 'tks_agendaModel');
 		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		// Validate the posted data.
		$form = $model->getForm();

		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}
		// Validate the posted data.
		$data = $model->validate($form, $data);
		$input = $app->input;
		$jform = $input->get('jform', array(), 'ARRAY');
		// Check for errors.
			if ($data === false)
			{
				// Get the validation messages.
				$errors = $model->getErrors();

				// Push up to three validation messages out to the user.
				for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
				{
					if ($errors[$i] instanceof Exception)
					{
						$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
					}
					else
					{
						$app->enqueueMessage($errors[$i], 'warning');
					}
				}
				// Save the data in the session.
				$app->setUserState('com_tks_agenda.edit.item.data', $jform);
				// Redirect back to the edit screen.
				$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
			}

			$startDate = new DateTime($data['start'],new DateTimeZone("Europe/Amsterdam"));
			$endDate = new DateTime($data['end'],new DateTimeZone("Europe/Amsterdam"));
			$endRecurring = new DateTime($data['end_recur'],new DateTimeZone("Europe/Amsterdam"));
			
			$interval = $startDate->diff($endRecurring);
			// check if if recurring less than a year
			if ($interval->days >= 365) {
					$app->setUserState('com_tks_agenda.edit.item.data', $data);
					$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
					$this->setMessage(JText::sprintf('De herhaling is meer dan een jaar! Dit is niet toegestaan.', $model->getError()), 'warning');
					return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));

			}

			if (isset($jform['recurring']) && $jform['recurring'] == true) {
				$values = '';
				do {
						switch ($data['recur_type']) {
						case 'dag':
							$startDate->modify("+1 day");
							$endDate->modify("+1 day");
							break;
						case 'week':
							$startDate->modify("+1 week");
							$endDate->modify("+1 week");
							break;
						case 'maand':
							$startDate->modify("+1 month");
							$endDate->modify("+1 month");
							break;
						default:
							$startDate->modify("");
							$endDate->modify("");
						break;
					}

					$values[] = array('rstart' => $startDate->format("Y-m-d H:i:s"), 'rend' => $endDate->format("Y-m-d H:i:s"));					
					} while ($startDate <= $endRecurring);

			}
	 		/*
	 		http://stackoverflow.com/questions/25549765/find-booking-overlaps-to-check-dates-availability
	 		*/
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('a.id','a.start', 'a.end','b.rstart','b.rend')));
			$query->from($db->quoteName('tks_agenda_items','a'));
			
			$query->join('LEFT', $db->quoteName('tks_agenda_recurring', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.rid') . ')') ;
			$query->where($db->quoteName('a.state') ." = 1");
			$query->andWhere($db->quoteName('a.start') ." >= NOW()");
			$query->andWhere($db->quoteName('a.end'). " > ".$db->quote($data['start'])." AND ".$db->quoteName('a.end'). " < ".$db->quote($data['end']));
			$query->orWhere($db->quoteName('a.start'). " > ".$db->quote($data['start'])." AND ".$db->quoteName('a.start'). " < ".$db->quote($data['end']));
			
	 		if(isset($jform['recurring']) && $jform['recurring'] == true) {
	  			
				$query->orWhere($db->quoteName('b.rend'). " > ".$db->quote($data['start'])." AND ".$db->quoteName('b.rend'). " < ".$db->quote($data['end']));
				$query->orWhere($db->quoteName('b.rstart'). " > ".$db->quote($data['start'])." AND ".$db->quoteName('b.rstart'). " < ".$db->quote($data['end']));

	  		}	  	 
		  //echo $query->dump();
		  //exit();
 			$db->setQuery($query);
	 
   			$result = $db->loadObjectList();
		 
			// CHECK: Als de reservatie overlapt return naar de edit screen en of de huidige berwerking niet hetzelfde is
   			//if ($result && $result[0]->start != $data['start'] && $result[0]->end != $data['end']) {
   			if ($result ) {
				
				$app->setUserState('com_tks_agenda.edit.item.data', $data);
				$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
				$this->setMessage(JText::sprintf('Deze datum en tijd is al geboekt', $model->getError()), 'warning');
				return $this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
			}
			// Attempt to save the data.
			$return = $model->save($data);

			// Check for errors.
			if ($return === false)
			{
				// Save the data in the session.
				$app->setUserState('com_tks_agenda.edit.item.data', $data);

				// Redirect back to the edit screen.
				$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
				$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
			}

		if (isset($jform['recurring']) && $jform['recurring'] == true) {

			$startDate = new DateTime($jform['start'],new DateTimeZone("Europe/Amsterdam"));
			$endDate = new DateTime($jform['end'],new DateTimeZone("Europe/Amsterdam"));
			$endRecurring = new DateTime($jform['end_recur'],new DateTimeZone("Europe/Amsterdam"));
			do {
				switch ($jform['recur_type']) {
					case 'dag':
						$startDate->modify("+1 day");
						$endDate->modify("+1 day");
					break;
					case 'week':
						$startDate->modify("+1 week");
						$endDate->modify("+1 week");
					break;
					case 'maand':
						$startDate->modify("+1 month");
						$endDate->modify("+1 month");
					break;					 
				}
 				$datesArray[] = $db->quote($return).', '.$db->quote($startDate->format("Y-m-d H:i:s")).', '.$db->quote($endDate->format("Y-m-d H:i:s"));	

			} while ($startDate <= $endRecurring);
 

			$query = $db->getQuery(true);
			$columns = array('rid', 'rstart', 'rend');
			$query->insert($db->quoteName('tks_agenda_recurring'));
			$query->columns($db->quoteName($columns));
			$query->values(implode('), (', $datesArray));
		    $db->setQuery($query);
		    $db->execute();
 
 
		}   		

	

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}
		// Clear the profile id from the session.
		$app->setUserState('com_tks_agenda.edit.item.id', null);
		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_TKS_AGENDA_SAVE_SUCCESS'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_tks_agenda&view=items' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_tks_agenda.edit.item.data', null);
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
		$model = $this->getModel('ItemForm', 'tks_agendaModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_tks_agenda&view=items' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
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
		$model = $this->getModel('ItemForm', 'tks_agendaModel');

		// Get the user data.
		$data       = array();
		$data['id'] = $app->input->getInt('id');

		// Check for errors.
		if (empty($data['id']))
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_tks_agenda.edit.item.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=item&layout=edit&id=' . $id, false));
		}

		// Attempt to save the data.
		$return = $model->delete($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_tks_agenda.edit.item.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_tks_agenda.edit.item.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=item&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		// Clear the profile id from the session.
		$app->setUserState('com_tks_agenda.edit.item.id', null);

		// Redirect to the list screen.
		$this->setMessage(JText::_('COM_TKS_AGENDA_ITEM_DELETED_SUCCESSFULLY'));
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_tks_agenda&view=items' : $item->link);
		$this->setRedirect(JRoute::_($url, false));

		// Flush the data from the session.
		$app->setUserState('com_tks_agenda.edit.item.data', null);
	}

}
