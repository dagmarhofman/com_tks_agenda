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
		
		$app = JFactory::getApplication();
		$recurring_id = $app->input->getInt( 'recur_id',-1 );
		$app->enqueueMessage(' TASK-> ' . $task . ' RECURID-> ' . $recurring_id, 'notice');


		$str = var_export($task, true) . var_export($item, true);
		JFactory::getApplication()->enqueueMessage($str, 'notice');
	}
}
