<?php

/**
 * @version    1.0.1
 * @package    tks_agenda
 * @author     Dagmar Hofman <stephan@takties.nl>
 * @copyright  Copyright (C) 2016. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Gckloosterveen.
 *
 * @since  1.6
 */
class tks_agendaViewitems extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;



	public function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
	}
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$this->state      = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->params     = $app->getParams('com_tks_agenda');
	
		require_once JPATH_COMPONENT . '/helpers/tks_agenda.php';

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}
		   // Get the document object.
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		$out = array();

		foreach($this->items as $row) {
			if ($row->state == 1) {
				$profile = JUserHelper::getProfile($row->created_by);
				$avatar = '';
				$company = '';
				$companytextcolor = '';
				if(!empty($profile->profile5['company']) && !empty($profile->profile5['avatar'])) {
					$company = $profile->profile5['company'];
					$avatar = $profile->profile5['avatar'];
					if (tks_agendaSiteFrontendHelper::get_brightness($companycolor) > 130) {
					} else {
			      	$companytextcolor = "#4d4d4d"; 
			      	$companytextcolor = "#FFFFFF"; 
			      }
				}
				$companycolor = $profile->profile5['companycolor'];
				$start_time = date("H:i",strtotime($row->start));
				$end_time = date("H:i",strtotime($row->end));

				$start_date = date( "Y-m-d", strtotime($row->start));
				$end_date =	date( "Y-m-d", strtotime($row->end) );
				$start_stamp = strtotime($row->start);
				$end_stamp = strtotime($row->end);		
				
		    	$out[] = array(
		    		'id' => $row->id,
 		      	'user' => $avatar,
		      	'company' => $company,
		     		'companycolor' => strtoupper($companycolor),
		     		'companytextcolor' => $companytextcolor,
		       	'start' => strtotime($start_date) . '000',
		       	'end' => strtotime($end_date) .'000',
		       	'start_time' => $start_time,
		      	'end_time' => $end_time,
					"url" => "index.php?option=com_tks_agenda&view=item&id=".$row->id."&format=raw&start_stamp=" . $start_stamp . "&end_stamp=" . $end_stamp,
		    		);
		    	
		    	if( $row->recurring == 'Yes') {
			    		for( $i = 0; isset($row->recur_events_start[$i]) && $row->recur_events_start[$i] != null; $i++) {		    		
							$start_time = date("H:i",strtotime($row->recur_events_start[$i]));
							$end_time = date("H:i",strtotime($row->recur_events_end[$i]));		

							$start_date = date( "Y-m-d", strtotime($row->recur_events_start[$i] ) );
							$end_date =	date( "Y-m-d", strtotime($row->recur_events_end[$i]) );
							$start_stamp = strtotime($row->recur_events_start[$i]);
							$end_stamp = strtotime($row->recur_events_end[$i]);
				    		$out[] = array(
			   	     		'id' => $row->id,
	 			        		'user' => $avatar,
				        		'company' => $company,
				     			'companycolor' => strtoupper($companycolor),
				     			'companytextcolor' => $companytextcolor,
					       	'start' => strtotime($start_date) . '000',
					       	'end' => strtotime($end_date) .'000',
				        		'start_time' => $start_time,
				        		'end_time' => $end_time,
								"url" => "index.php?option=com_tks_agenda&view=item&id=".$row->id."&format=raw&start_stamp=" . $start_stamp . "&end_stamp=" . $end_stamp,
				    			);
		    		}
		    	} // $row->recurring == 'Yes'
		   } // $row->state == 1
		} //foreach
				
			
		echo json_encode(array('success' => 1, 'result' => $out),JSON_PRETTY_PRINT);
	}

	 
}



