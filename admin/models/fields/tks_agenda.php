<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

class JFormFieldtks_agenda extends JFormFieldList
{

	protected $type = 'tks_experiment';

	protected function getOptions()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,item');
		$query->from('tks_agenda_select');
		$db->setQuery((string) $query);
		$messages = $db->loadObjectList();
		$options  = array();

		if ($messages)
		{
			foreach ($messages as $message)
			{
				$options[] = JHtml::_('select.option', $message->id, $message->item);
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
