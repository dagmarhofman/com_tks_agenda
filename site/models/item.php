<?php

/**
 * @version    1.0.1
 * @package    tks_agenda
 * @author     Dagmar Hofman <stephan@takties.nl>
 * @copyright  Copyright (C) 2016. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');
jimport('joomla.event.dispatcher');

use Joomla\Utilities\ArrayHelper;

/**
 * tks_agenda model.
 *
 */
class tks_agendaModelItem extends JModelItem
{
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 *
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_tks_agenda');

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit')
		{
			$id = JFactory::getApplication()->getUserState('com_tks_agenda.edit.item.id');
		}
		else
		{
			$id = JFactory::getApplication()->input->get('id');
			JFactory::getApplication()->setUserState('com_tks_agenda.edit.item.id', $id);
		}

		$this->setState('item.id', $id);

		// Load the parameters.
		$params       = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('item.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}

	/**
	 * Method to get an object.
	 *
	 * @param   integer  $id  The id of the object to get.
	 *
	 * @return  mixed    Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('item.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties  = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
		}

		if (isset($this->_item->created_by) )
		{
			$this->_item->created_by_name = JFactory::getUser($this->_item->created_by)->name;
		}
	
		$db    = $this->getDbo();

		$query = $db->getQuery(true);
		$query->select( '*' );
		$query->from('#__tks_agenda_recurring');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		foreach( $results as $result ) {
			$items[] = array( 
									$result->id,
									$result->rid, 
									$result->rstart,
									$result->rend,
									);	
		}
			
		$this->_item->recur_events = $items;


		//JFactory::getApplication()->input->get('id');

	$start =JFactory::getApplication()->input->get('start_stamp');
	$end = JFactory::getApplication()->input->get('end_stamp');
		$s['start_stamp'] = date('Y-m-d H:i:s',  $start );
		$s['end_stamp'] = date('Y-m-d H:i:s',  $end );

		
		foreach( $items as $val ) {
			if( $val->rstart == $start && $val->rend == $end ) {
				$s['recur_id'] = $val->id;			
			}	
		}		

		$this->_item->stamp = $s ;

		return $this->_item;
	}





	/**
	 * Get an instance of JTable class
	 *
	 * @param   string  $type    Name of the JTable class to get an instance of.
	 * @param   string  $prefix  Prefix for the table class name. Optional.
	 * @param   array   $config  Array of configuration values for the JTable object. Optional.
	 *
	 * @return  JTable|bool JTable if success, false on failure.
	 */
	public function getTable($type = 'Item', $prefix = 'tks_agendaTable', $config = array())
	{
		$this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_tks_agenda/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Get the id of an item by alias
	 *
	 * @param   string  $alias  Item alias
	 *
	 * @return  mixed
	 */
	public function getItemIdByAlias($alias)
	{
		$table = $this->getTable();

		$table->load(array('alias' => $alias));

		return $table->id;
	}

	/**
	 * Method to check in an item.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int) $this->getState('item.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin'))
			{
				if (!$table->checkin($id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param   integer  $id  The id of the row to check out.
	 *
	 * @return  boolean True on success, false on failure.
	 *
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int) $this->getState('item.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout'))
			{
				if (!$table->checkout($user->get('id'), $id))
				{
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Get the name of a category by id
	 *
	 * @param   int  $id  Category id
	 *
	 * @return  Object|null	Object if success, null in case of failure
	 */
	public function getCategoryName($id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . $id);
		$db->setQuery($query);

		return $db->loadObject();
	}

	/**
	 * Publish the element
	 *
	 * @param   int  $id     Item id
	 * @param   int  $state  Publish state
	 *
	 * @return  boolean
	 */
	public function publish($id, $state)
	{
		$table = $this->getTable();
		$table->load($id);
		$table->state = $state;

		return $table->store();
	}

	/**
	 * Method to delete an item
	 *
	 * @param   int  $id  Element id
	 *
	 * @return  bool
	 */
	public function delete($id)
	{
		$table = $this->getTable();

		return $table->delete($id);
	}

	/**
	 * Method to delete a recurring item
	 *
	 * @param   int  $id  Element id
	 *
	 * @return  bool
	 */
	public function deleterecur($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);


		$query->delete($db->quoteName('#__tks_agenda_recurring'));
		$query->where(  $db->quoteName('id') . ' = ' . $id );
	
		$db->setQuery($query);
		$db->execute();

	}
	
}
