<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Newsitem controller class.
 *
 * @since  1.6
 */
class tks_agendaControllerNewsitem extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'newsitems';
		parent::__construct();
	}
}
