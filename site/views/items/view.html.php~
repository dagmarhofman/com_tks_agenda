<?php

/**
 * @version    CVS: 1.0.0
 * @author     Dagmar Hofman <dhofman@initfour.nl>
 * @copyright  Copyright (C) 2016. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of com_tks_agenda.
 *
 * @since  1.6
 */
class tks_agendaViewitems extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

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
		$app = JFactory::getApplication();

		$this->state      = $this->get('State');
		$this->items = $this->get('Items');	
		
		$this->pagination = $this->get('Pagination');
		
		var_export($this->items);
		
		//$params = JComponentHelper::getParams(JRequest::getVar('option')); // Get parameter helper (corrected 'JRquest' spelling)
		$this->params     = $app->getParams('com_tks_agenda');

		JHtml::_('jquery.framework');	
		JHtml::_('bootstrap.framework');
		JHtml::_('bootstrap.popover');

		$doc = JFactory::getDocument();
 		$doc->addStylesheet(JURI::root().'components/com_tks_agenda/assets/css/calendar.css');
		$doc->addScript(JURI::root().'components/com_tks_agenda/assets/js/language/nl-NL.js');

		$doc->addScript(JURI::root().'components/com_tks_agenda/assets/js/vendor/underscore-min.js');
		$doc->addScript(JURI::root().'components/com_tks_agenda/assets/js/vendor/jstz.min.js');
		$doc->addScript(JURI::root().'components/com_tks_agenda/assets/js/calendar.js');
		$doc->addScript(JURI::root().'components/com_tks_agenda/assets/js/app.js');
 				
		$dispatcher = JEventDispatcher::getInstance();
		JPluginHelper::importPlugin('content');
		
		
		
		$dispatcher->trigger('onContentPrepareAgenda', array ('com_tks_agenda.item', &$this->item, &$this->params, ''));

	 
	   $errors = $this->get('Errors');

		// Check for errors.
		if (count($errors))
		{
			throw new Exception(implode("\n", $errors));
		}
 
		$this->_prepareDocument();
	

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
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_TKS_AGENDA_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}
}
