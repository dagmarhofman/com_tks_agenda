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

	

	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		
		$task = $this->getTask();
		$item = $model->getItem();

		if($task == 'apply' || $task == 'save' && $item->recurring == true){

	 		$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$id = $item->id;

 
			$query->select('id,rid')
			      ->from($db->quoteName('tks_agenda_recurring'))
			      ->where($db->quoteName('rid').'='.$id);
			$db->setQuery($query);
			$rows = $db->loadObjectList();

			if ($rows) {
			 /*$startDate = new DateTime($item->start,new DateTimeZone("Europe/Amsterdam"));
					$endDate = new DateTime($item->end,new DateTimeZone("Europe/Amsterdam"));
					$endRecurring = new DateTime($item->end_recur,new DateTimeZone("Europe/Amsterdam"));

	 				$query = $db->getQuery(true);
					$query->update($db->quoteName('#__old_recurring'));
					foreach ($rows as $row) {
							if ($startDate >= $endRecurring) {
						 		break;
						 	}
						 	$startDate->modify("+1 week");
						$endDate->modify("+1 week");
						$values[] = $db->quoteName('id') .'='.$db->quote($row->id).', '.
									$db->quoteName('rid') .'='.$db->quote($id).', '.
									$db->quoteName('rstart') .'='.$db->quote($startDate->format("Y-m-d H:i:s")).' , '.
									$db->quoteName('rend') .'='.$db->quote($endDate->format("Y-m-d H:i:s"));
		
 $query->set(implode(',', $values));
 				$query->where(array($db->quoteName('rid') . '='.$id,$db->quoteName('id') . '='.$row->id ));
					}
 
						
 				
				$db->setQuery($query);
		 
			$db->execute(); */
 
			JFactory::getApplication()->enqueueMessage('Als de datum en tijd na het opslaan gewijzigd word.. Dient de boeking opnieuw te worden gemaakt verwijder dan deze boeking', 'warning');

			} else {

				
					$startDate = new DateTime($item->start,new DateTimeZone("Europe/Amsterdam"));
					$endDate = new DateTime($item->end,new DateTimeZone("Europe/Amsterdam"));
					$endRecurring = new DateTime($item->end_recur,new DateTimeZone("Europe/Amsterdam"));

					$columns = array('rid', 'rstart', 'rend');
					$query->insert($db->quoteName('tks_agenda_recurring'));
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
