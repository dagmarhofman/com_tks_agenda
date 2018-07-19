<?php


// No direct access
defined('_JEXEC') or die;

class tks_agendaHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_TKS_AGENDA_TITLE_ITEMS'),
			'index.php?option=com_tks_agenda&view=items',
			$vName == 'items'
		);

		JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES') . ' (' . JText::_('COM_TKS_AGENDA_TITLE_ITEMS') . ')',
			"index.php?option=com_categories&extension=com_tks_agenda",
			$vName == 'categories'
		);
		if ($vName=='categories') {
			JToolBarHelper::title('Takties - agenda: JCATEGORIES (COM_TKS_AGENDA_TITLE_ITEMS)');
		}

		JHtmlSidebar::addEntry(
			JText::_('COM_TKS_AGENDA_TITLE_DOWNLOADS'),
			'index.php?option=com_tks_agenda&view=downloads',
			$vName == 'downloads'
		);

			JHtmlSidebar::addEntry(
			JText::_('COM_TKS_AGENDA_TITLE_NEWSITEMS'),
			'index.php?option=com_tks_agenda&view=newsitems',
			$vName == 'newsitems'
		);
				JHtmlSidebar::addEntry(
			JText::_('JCATEGORIES') . ' (' . JText::_('COM_TKS_AGENDA_TITLE_NEWSITEMS') . ')',
			"index.php?option=com_categories&extension=com_tks_agenda.newscatid",
			$vName == 'categories'
		);
		if ($vName=='categories') {
			JToolBarHelper::title('Takties - agenda: JCATEGORIES (COM_TKS_AGENDA_TITLE_NEWSITEMS)');
		}

	 

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_tks_agenda';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
