<?php

/**
 * @version    1.0.1
 * @package    tks_agenda
 * @author     Dagmar Hofman <stephan@takties.nl>
 * @copyright  Copyright (C) 2016. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


/**
 * Methods supporting a list of Gckloosterveen records.
 *
 * @since  1.6
 */
class tks_agendaModelItems extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'catid', 'a.catid',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
 				'start', 'a.start',
				'end', 'a.end',
 			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		//$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
		//$this->setState('list.limit', $limit);

		//$limitstart = $app->getUserStateFromRequest('limitstart', 'limitstart', 0);
		//$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');

		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');

		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
{
	$list['ordering'] = 'ordering';
}

if (empty($list['direction']))
{
	$list['direction'] = 'asc';
}

		if (isset($list['ordering']))
		{
			$this->setState('list.ordering', $list['ordering']);
		}

		if (isset($list['direction']))
		{
			$this->setState('list.direction', $list['direction']);
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.	$db    = $this->getDbo();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'state as aa, a.id as bb, b.id as cc, created_by.username as dd, start as ee, end as ff, recurring, rstart, rend, recur_type '
				)
		);

		$query->from('`#__tks_agenda_items` AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		// Join over the category 'catid'
		$query->select('catid.title AS catid_title');
		$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		$query->join('INNER', '#__tks_agenda_recurring b ON a.id = b.rid');
		
		if (!JFactory::getUser()->authorise('core.edit', 'com_tks_agenda'))
		{
			$query->where('a.state = 1');
		}
		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.title LIKE '.$search.' )');
			}
		}

		//union de items tabel
		//state as aa, a.id as bb, b.id as cc, created_by.username as dd, start as ee, end as ff, recurring, rstart, rend, recur_type

		$db2    = $this->getDbo();
		$query2 = $db->getQuery(true);


		$query2
			->select(
				$this->getState(
					'list.select', 'state as aa, a.id as bb, -1 as cc, created_by.username as dd, start as ee, end as ff, recurring, NULL as rstart, NULL as rend, CASE  WHEN  recurring = "Yes"  THEN recur_type ELSE "geen" END AS recur_type '    
				)
		);

		$query2->from('`#__tks_agenda_items` AS a');
		
		// Join over the users for the checked out user.
		$query2->select('uc.name AS editor');
		$query2->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		// Join over the category 'catid'
		$query2->select('catid.title AS catid_title');
		$query2->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

		// Join over the created by field 'created_by'
		$query2->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
	
		$result = $query->union($query2);
		
		//test
		if($orderCol == 'ordering' ) {
			$orderCol = 'bb';		
		}
		
		if ($orderCol && $orderDirn)
		{
			$result->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		
		return $result;
	}


	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		// Add the list ordering clause.
		$col  = $this->state->get('list.direction');
		$order = $this->state->get('list.ordering');


		$items = parent::getItems();
		foreach ($items as $item)
		{
			if (isset($item->catid))
			{
				// Get the title of that particular template
					$title = tks_agendaFrontendHelper::getCategoryNameByCategoryId($item->catid);
					// Finally replace the data object with proper information
					$item->catid = !empty($title) ? $title : $item->catid;
			}
		}
		
		return $items;
	}
	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_TKS_AGENDA_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}
}
