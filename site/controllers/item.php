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

class tks_agendaControllerItem extends tks_agendaController 
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


		JFactory::getApplication()->setUserState('com_tks_agenda.edit.item.mode', 'update');



		// Set the user id for the user to edit in the session.
		$app->setUserState('com_tks_agenda.edit.item.id', $editId);

		// Get the model.
		$model = $this->getModel('Item', 'tks_agendaModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId && $previousId !== $editId)
		{
			$model->checkin($previousId);
		}

		$recurring_id = $app->input->get('recur_id', -1); 
		
		
		$app->setUserState( 'com_tks_agenda.edit.item.recurid', $recurring_id );

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&mode=1'  , false));
	}
 
	/**
	 * Method to save a user's profile data.
	 *
	 * @return    void
	 *
	 * @throws Exception
	 * @since    1.6
	 */
	public function publish()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Checking if the user can remove object
		$user = JFactory::getUser();

		if ($user->authorise('core.edit', 'com_tks_agenda') || $user->authorise('core.edit.state', 'com_tks_agenda'))
		{
			$model = $this->getModel('Item', 'tks_agendaModel');

			// Get the user data.
			$id    = $app->input->getInt('id');
			$state = $app->input->getInt('state');

			// Attempt to save the data.
			$return = $model->publish($id, $state);

			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(JText::sprintf('Save failed: %s', $model->getError()), 'warning');
			}

			// Clear the profile id from the session.
			$app->setUserState('com_tks_agenda.edit.item.id', null);

			// Flush the data from the session.
			$app->setUserState('com_tks_agenda.edit.item.data', null);

			// Redirect to the list screen.
			$this->setMessage(JText::_('COM_TKS_AGENDA_ITEM_SAVED_SUCCESSFULLY'));
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				// If there isn't any menu item active, redirect to list view
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=items', false));
			}
			else
			{
				$this->setRedirect(JRoute::_($item->link . $menuitemid, false));
			}
		}
		else
		{
			throw new Exception(500);
		}
	}

	/**
	 * Remove data
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function remove()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Checking if the user can remove object
		$user = JFactory::getUser();

		if ($user->authorise('core.delete', 'com_tks_agenda') || $user->authorise('core.delete.medewerker', 'com_tks_agenda'))
		{
			$model = $this->getModel('Item', 'tks_agendaModel');

			$recurring_id = $app->input->getInt('recur_id', -1);			
			$id = $app->input->getInt('id', 0);
			
			if( $recurring_id == -1 ) {
		 		$return = $model->delete($id);
			} else {
		 		$return = $model->deleterecur($recurring_id);
			}
			
			// Check for errors.
			if ($return === false)
			{
				$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			}
			else
			{
				// Check in the profile.
				if ($return)
				{
					$model->checkin($return);
				}

				// Clear the profile id from the session.
				$app->setUserState('com_tks_agenda.edit.item.id', null);

				// Flush the data from the session.
				$app->setUserState('com_tks_agenda.edit.item.data', null);

				$this->setMessage(JText::_('COM_TKS_AGENDA_ITEM_DELETED_SUCCESSFULLY'));
			}

			// Redirect to the list screen.
			$menu = JFactory::getApplication()->getMenu();
			$item = $menu->getActive();

			if (!$item)
			{
				// If there isn't any menu item active, redirect to list view
				$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=items', false));
			}
			else
			{
				$this->setRedirect(JRoute::_($item->link . $menuitemid, false));
			}
 		}
		else
		{
			throw new Exception(500);
		}
	}


}
