<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class tks_agendaViewPortal extends JViewLegacy
{
	 

	protected $state;

	protected $item;

	protected $form;

	protected $params;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();
 

		$this->state  = $this->get('State');
		$this->item   = $this->get('Data');
		$this->params = $app->getParams('com_tks_agenda');
 		$dispatcher = JEventDispatcher::getInstance();
 

		JPluginHelper::importPlugin('content');
		$dispatcher->trigger('onContentPrepareAgenda', array ('com_tks_agenda.portal', &$this->item, &$this->params, ''));

	 


		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
  	 

 
	}
}
