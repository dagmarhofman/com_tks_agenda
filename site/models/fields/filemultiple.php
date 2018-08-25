<?php
/**
 * @package    com_tks_agenda
 * @author     Dagmar Hofman, Stephan Zuidberg
 * @copyright  Copyright (C) 2018. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 *
 */
class JFormFieldFileMultiple extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 */
	protected $type = 'file';

	/**
	 * Method to get the field input markup.
	 *
	 * @return    string    The field input markup.
	 *
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = '<input type="file" name="' . $this->name . '[]" multiple >';

		return $html;
	}
}
