<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Item controller class.
 *
 * @since  1.6
 */
class tks_agendaControllerItem extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'items';
		parent::__construct();
	}

	/**
	 *
	 * PostSaveHook edits items or creates them.
	 *
	 * @param	JModelLegacy	$model 		The data model object.
	 *	@param	array				$validData	The validated data.
	 *
	 */	

	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{	
		$task = $this->getTask();
		$item = $model->getItem();

		// uit item halen ?!
		$session = &JFactory::getSession();
		$id = $session->get("last_edit_redirect_id");		

		$start = $item->start; //,new DateTimeZone("Europe/Amsterdam"))->format('Y-m-d H:i:s');
		$end = $item->end; //,new DateTimeZone("Europe/Amsterdam"))->format('Y-m-d H:i:s');
		
		
		$layout = JFactory::getApplication()->input->get('layout');		

		if( $layout == 'edit' && $id < 536870911 ) {
			//JFactory::getApplication()->enqueueMessage('DEBUG: edit ITEM called.' . $id , 'notice');			

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$fields = array(
			    $db->quoteName('start') . ' = \'' . $start . '\'',
			    $db->quoteName('end') . ' = \'' . $end . '\''
			);
			$conditions = array(
			    $db->quoteName('id') . ' = ' . $id
			);
			$query->update($db->quoteName('#__tks_agenda_items'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$result = $db->execute();


			//redirect according to $task			
			
			$this->setRedirect( JRoute::_('index.php?option=com_tks_agenda'), false );
			return;		
		}
		if( $layout == 'edit' && $id >= 536870911) {
			//JFactory::getApplication()->enqueueMessage('DEBUG: edit RECURITEM called.' . $id , 'notice');			

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$fields = array(
			    $db->quoteName('rstart') . ' = \'' . $start . '\'' ,
			    $db->quoteName('rend') . ' = \'' . $end . '\'' 
			);
			$conditions = array(
			    $db->quoteName('id') . ' = ' . $id
			);
			$query->update($db->quoteName('#__tks_agenda_recurring'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$result = $db->execute();


			$this->setRedirect( JRoute::_('index.php?option=com_tks_agenda'), false );
			return;		
		}

		if($task == 'apply' || $task == 'save' && $item->recurring == true)
		{
	 		$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$id = $item->id;

 
			$query->select('id,rid')
			      ->from($db->quoteName('#__tks_agenda_recurring'))
			      ->where($db->quoteName('rid').'='.$id);
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			if ($rows) 
			{
				JFactory::getApplication()->enqueueMessage('Als de datum en tijd na het opslaan gewijzigd word.. Dient de boeking opnieuw te worden gemaakt verwijder dan deze boeking', 'warning');
			} else 
			{				
					$startDate = new DateTime($item->start,new DateTimeZone("Europe/Amsterdam"));
					$endDate = new DateTime($item->end,new DateTimeZone("Europe/Amsterdam"));
					$endRecurring = new DateTime($item->end_recur,new DateTimeZone("Europe/Amsterdam"));

					$columns = array('rid', 'rstart', 'rend');
					$query->insert($db->quoteName('#__tks_agenda_recurring'));
					$query->columns($db->quoteName($columns));
				
					do {
						switch ($item->recur_type) {
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
						$values[] = $db->quote($id).', '.$db->quote($startDate->format("Y-m-d H:i:s")).' , '.$db->quote($endDate->format("Y-m-d H:i:s"));
					
				} while ($startDate <= $endRecurring);

		 		$query->values(implode('), (', $values));
		 		$db->setQuery($query);
				$db->execute();
			}
		} 
	}
}
