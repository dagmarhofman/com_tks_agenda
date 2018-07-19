<?php

defined('_JEXEC') or die;

class tks_agendaFrontendHelper
{


		public static function get_brightness($hex) {

		 // returns brightness value from 0 to 255
		 // strip off any leading #
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

	/**
	* Get category name using category ID
	* @param integer $category_id Category ID
	* @return mixed category name if the category was found, null otherwise
	*/
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
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
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
	/**
	 * Gets the files attached to an item
	 *
	 * @param   int     $pk     The item's id
	 *
	 * @param   string  $table  The table's name
	 *
	 * @param   string  $field  The field's name
	 *
	 * @return  array  The files
	 */
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

    /**
     * Gets the edit permission for an user
     *
     * @param   mixed  $item  The item
     *
     * @return  bool
     */
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
