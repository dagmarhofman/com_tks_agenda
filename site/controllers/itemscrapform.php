<?php


// No direct access
defined('_JEXEC') or die;

class tks_agendaControllerItemScrapForm extends JControllerForm
{



	
	public function save_recurring_items( $id ) 
	{
			$app   = JFactory::getApplication();
			
			
			$db = JFactory::getDBO();
			$recurArray = 	$app->getUserState('com_tks_agenda.edit.item.recurring');
			$overlapArray = 	$app->getUserState('com_tks_agenda.edit.item.overlap');

			$i = 0;
			while( $recurArray[0][$i] != null) {
				$total[$i] = $db->quote($id)  . ', ' . $db->quote($recurArray[0][$i]) . ', ' . $db->quote($recurArray[1][$i]);
				$i++;
			}		
			$j = $i;
			$i = 0;
			while( $overlapArray[0][$i] != null) {
				$total[$j + $i] = $db->quote($id)  . ', ' . $db->quote($overlapArray[0][$i]) . ', ' . $db->quote($overlapArray[1][$i]);
				$i++;			
			}		
			$size = $i + $j;

			$j = 0;
			sort($total); 			
			
			for( $i = 0; $i < $size; $i++) {
				if( $total[$i] == $total[$i+1] ) {
					$i++;	
					continue;
				} 
				$datesArrayFlat[$j] = $total[$i]; 
				$j++;
			}
		
						
			$query = $db->getQuery(true);
			$columns = array('rid', 'rstart', 'rend');
			$query->insert($db->quoteName('#__tks_agenda_recurring'));
			$query->columns($db->quoteName($columns));
			$query->values(implode('), (', $datesArrayFlat));
		   $db->setQuery($query);
		   $db->execute();

			$this->setMessage(JText::sprintf( 'De afspraken zijn ingevoerd.' ,  $this->model->getError()), 'notice');
		   
	}
	

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
	public function cancel($key = NULL)
	{
				// Get the model.
		$this->model = $this->getModel('ItemForm', 'tks_agendaModel');

		$this->setMessage(JText::sprintf( 'Op basis van deze planning kunnen de herhaalafspraken niet worden ingevoerd. ' . $id, $this->model->getError()), 'warning');								
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemform&layout=edit&id=' . $id, false));
	}

}
