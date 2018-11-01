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
class tks_agendaControllerItemEditForm extends tks_agendaController
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

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_tks_agenda&view=itemeditform&layout=edit', false));
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
	}

}
