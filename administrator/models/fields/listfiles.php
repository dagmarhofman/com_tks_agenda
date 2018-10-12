<?php


defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 *
 * @since  1.6
 */
class JFormFieldListFiles extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.6
	 */
	protected $type = 'listfiles';

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 *
	 * @since    1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
	 	// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		$id = JRequest::getVar('id');
		if (empty($id)) {
			return false;
		}
 		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($db->quoteName(array('id', 'title', 'file')));
		$query->from($db->quoteName('#__tks_agenda_download'));
		$query->where($db->quoteName('id') . ' = '. $db->quote($id));
		$query->order('ordering ASC');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		 	
		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObject();
		$files = explode(',',$results->file); 
		// /var_dump($files);
		$html = '<ul class="download_list">';
		foreach ($files as $file) {
			$html .= '<li>'.$file.'</li>';
		}
		$html .= '</ul>';
		return $html;

	}
}
