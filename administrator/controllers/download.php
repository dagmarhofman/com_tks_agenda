<?php


// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Download controller class. 
 *
 */
class tks_agendaControllerDownload extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'downloads';
		parent::__construct();
	}
}
