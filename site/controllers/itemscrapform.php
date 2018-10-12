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

/**
 * Item Scrap Form Controller
 *
 */

class tks_agendaControllerItemScrapForm extends JControllerForm
{

	/**
	 * Function that checks if dates a and b are in overlap_array 
	 *
	 * @param 	string	$a	first date
	 * @param	string	$b	second date
	 *	@param	array		$c	date array
	 *
	 * @return	bool	not in overlap array?
	 */

	public function not_in_overlap_array( $a, $b, $c ) 
	{
		$flag = true;
		
		foreach( $c as $val ) {
			if( $val[0][$i] == $a && $val[1][$i] == $b ) {
				$flag = false;
			}			
		}
	}
	
	/**
	 * Save recurring items 
	 *
	 * @param 	integer	$id	id of recurring item
	 *
	 * @return	void	
	 */
	public function save_recurring_items( $id ) 
	{
			$app   = JFactory::getApplication();
			
			
			$db = JFactory::getDBO();
			$recurArray = 	$app->getUserState('com_tks_agenda.edit.item.recurring');
			$overlapArray = 	$app->getUserState('com_tks_agenda.edit.item.overlap');

			//deze plant 1 afspraak te veel over een loze datum ???
			$i = 0;
			while( $recurArray[0][$i] != NULL ) {
				$recurArrayFlat[$i] = $db->quote($id).', '. $db->quote($recurArray[0][$i]) .', ' . $db->quote($recurArray[1][$i]) ;
				$i++;			
			} ;			

			$i = 0;
			do {
				$overlapArrayFlat[$i] = $db->quote($id).', '. $db->quote($overlapArray[0][$i]) .', ' . $db->quote($overlapArray[1][$i]) ;
				$i++;			
			} while( $overlapArray[0][$i] != NULL );			

			
			$total = array_diff( $recurArrayFlat, $overlapArrayFlat);
			
			$query = $db->getQuery(true);
			$columns = array('rid', 'rstart', 'rend');
			$query->insert($db->quoteName('#__tks_agenda_recurring'));
			$query->columns($db->quoteName($columns));
			$query->values(implode('), (', $total));
		   $db->setQuery($query);
		   $db->execute();

			$this->setMessage(JText::sprintf( 'De afspraken zijn ingevoerd.'  ,  $this->model->getError()), 'notice');
		   
	}
	
	/**
	 * submit form data
	 *
	 * @param 	integer 	$key 	key, not used
	 *
	 * @return	void 
	 */
	public function submit($key = NULL)
	{

		// Initialise variables.
		$app   = JFactory::getApplication();
		$db = JFactory::getDBO();

		$this->model = $this->getModel('ItemScrapForm', 'tks_agendaModel');
 		
 		// Get the user data.
		$this->data = $app->getUserState('com_tks_agenda.edit.item.data');
		
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

		// Attempt to save the data.
		$return = $this->model->save($this->data);


		$this->save_recurring_items($return);

		//$this->setMessage(JText::sprintf( 'De afspraken zijn ingevoerd.',  $this->model->getError()), 'warning');								
		$url  = (empty($item->link) ? 'index.php?option=com_tks_agenda&view=items' : $item->link);
		$this->setRedirect(JRoute::_($url, false));
	}
	
	/**
	 * cancel form data
	 *
	 * @param 	integer 	$key 	key, not used
	 *
	 * @return	void 
	 */
	public function cancel($key = NULL)
	{
				// Get the model.
		$this->model = $this->getModel('ItemForm', 'tks_agendaModel');

		$this->setMessage(JText::sprintf( 'Op basis van deze planning kunnen de herhaalafspraken niet worden ingevoerd. ' . $id, $this->model->getError()), 'warning');								
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
	}

}
