<?php

// No direct access.
defined('_JEXEC') or die;

/**
 * Downloads list controller class.
 *
 * @since  1.6
 */
class tks_agendaControllerDownloads extends tks_agendaController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Downloads', $prefix = 'tks_agendaModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
