<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Items view class
*/

class tks_agendaViewItems extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

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
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		tks_agendaHelper::addSubmenu('items');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT . '/helpers/tks_agenda.php';

		$state = $this->get('State');
		$canDo = tks_agendaHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_TKS_AGENDA_TITLE_ITEMS'), 'items.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/item';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('item.add', 'JTOOLBAR_NEW');
			}
			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
					JToolBarHelper::editList('item_edit_redirect', 'JTOOLBAR_EDIT' );
			}
		}
		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
			}
			elseif (isset($this->items[0]))
			{
				//JToolBarHelper::deleteList('', 'items.delete', 'JTOOLBAR_DELETE');
				JToolBarHelper::deleteList('', 'item_delete_redirect', 'JTOOLBAR_DELETE' );				
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'items.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('items.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_tks_agenda');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_tks_agenda&view=items');

		$this->extra_sidebar = '';
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`created_by`' => JText::_('COM_TKS_AGENDA_ITEMS_CREATED_BY'),
 			'a.`start`' => JText::_('COM_TKS_AGENDA_ITEMS_START'),
			'a.`end`' => JText::_('COM_TKS_AGENDA_ITEMS_END'),
			'a.`recur_id`' => JText::_('JGRID_HEADING_RECUR_ID'),
			'a.`rstart`' => JText::_('JGRID_HEADING_RECUR_RSTART'),
			'a.`rend`' => JText::_('JGRID_HEADING_RECUR_REND'),
 		);
	}
}
