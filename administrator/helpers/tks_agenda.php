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
/*
class tks_agendaFrontendHelper
{


		public static function get_brightness($hex) {

		 $hex = str_replace('#', '', $hex);

		 $c_r = hexdec(substr($hex, 0, 2));
		 $c_g = hexdec(substr($hex, 2, 2));
		 $c_b = hexdec(substr($hex, 4, 2));

		 return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
		}

		public static function truncate($string, $limit, $break=".", $pad="...") {
        if(strlen($string) <= $limit) return $string; 
        if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
            if($breakpoint < strlen($string) - 1) { 
                $string = substr($string, 0, $breakpoint) . $pad; } 
            } return $string; 
}

	public static function getCategoryNameByCategoryId($category_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('title')
			->from('#__categories')
			->where('id = ' . intval($category_id));

		$db->setQuery($query);
		return $db->loadResult();
	}
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_tks_agenda/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_tks_agenda/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'tks_agendaModel');
		}

		return $model;
	}

	public static function getFiles($pk, $table, $field)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select($field)
			->from($table)
			->where('id = ' . (int) $pk);

		$db->setQuery($query);

		return explode(',', $db->loadResult());
	}

    public static function canUserEdit($item)
    {
        $permission = false;
        $user       = JFactory::getUser();

        if ($user->authorise('core.edit', 'com_tks_agenda'))
        {
            $permission = true;
        }
        else
        {
            if (isset($item->created_by))
            {
                if ($user->authorise('core.edit.own', 'com_tks_agenda') && $item->created_by == $user->id)
                {
                    $permission = true;
                }
            }
            else
            {
                $permission = true;
            }
        }

        return $permission;
    }
}
*/