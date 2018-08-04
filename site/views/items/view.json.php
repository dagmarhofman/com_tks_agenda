<?php

/**
 * @version    CVS: 1.0.0
 * @package    com_tks_agenda
 * @author     Stephan Zuidberg <stephan@takties.nl>
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

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function recur_events($id,$avatar,$company,$companycolor,$companytextcolor,$start,$end,$start_time,$end_time,$recur_type,$end_recur) {
		 
		$date_calculation = "";
		switch ($recur_type) {
		    case "dag":
		    $date_calculation = " +1 day";
		    break;
		case "week":
		    $date_calculation = " +1 week";
		    break;
		case "maand":
		    $date_calculation = " +1 month";
		    break;
		default:
		    $date_calculation = "none";
		}

		$dateArray[] =  $start;

		$day = strtotime($start);
		$end = strtotime($end);
		$to = strtotime($end_recur);

		$oneday = strtotime('-1 day', $to);
		$i=0;

		while( $day <= $oneday) {

		if ($day <= $oneday) {
			$day = strtotime( $date_calculation, $day);
		    $end = strtotime( $date_calculation, $end);
		 

		 	$out[] = array(
				        'id' => $id."_".$i,
		 		        'user' => $avatar,
				        'company' => $company,
				     	'companycolor' => strtoupper($companycolor),
				     	'companytextcolor' => $companytextcolor,
				        'start' => $day. '000',
				        'end' => $end. '000',
				        'start_time' => $start_time,
				        'end_time' => $end_time,
						"url" => "index.php?option=com_tks_agenda&view=item&id=".$id."&format=raw",
				    );
			}
			$i++;
		    }
		    if (empty( $out)) {
		    	 return array();
		    }else {
		    	 return  $out;

		    }
  
 

	}

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

		//echo '<pre>';var_dump($this->items);'</pre>';
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}
		   // Get the document object.
		$doc = JFactory::getDocument();
		 
		// Set the MIME type for JSON output.
		//$doc->setMimeEncoding('application/json');
		$user = JFactory::getUser();
		$out = array();
		foreach($this->items as $row) {
		if ($row->state == 1) {

		$profile = JUserHelper::getProfile($row->created_by);
		//each plugin adds its own object  loaded with an array so you can access your fields like this
	
		if(!empty($profile->profile5['company']) && !empty($profile->profile5['avatar'])){
			$company = $profile->profile5['company'];
			$avatar = $profile->profile5['avatar'];
			$companycolor = $profile->profile5['companycolor'];

			 if (tks_agendaSiteFrontendHelper::get_brightness($companycolor) > 130) {
			       $companytextcolor = "#4d4d4d"; 
			      } else {
			       $companytextcolor = "#FFFFFF"; 
			      }
			}


			$start_time = date("H:i",strtotime($row->start));
			$end_time = date("H:i",strtotime($row->end));

		    $out[] = array(
		        'id' => $row->id,
 		        'user' => $avatar,
		        'company' => $company,
		     	'companycolor' => strtoupper($companycolor),
		     	'companytextcolor' => $companytextcolor,
		        'start' => strtotime($row->start) . '000',
		        'end' => strtotime($row->end) .'000',
		        'start_time' => $start_time,
		        'end_time' => $end_time,
				"url" => "index.php?option=com_tks_agenda&view=item&id=".$row->id."&format=raw",
 
		    );
		    $recurarray = array();

			if ($row->recurring == true) {

				$recurarray = $this->recur_events($row->id,$avatar,$company,$companycolor,$companytextcolor,$row->start,$row->end,$start_time,$end_time,$row->recur_type,$row->end_recur);
				$out = array_merge($out,$recurarray);
			}
 		}

		}
		//$out = $this->flatten($out);
		echo json_encode(array('success' => 1, 'result' => $out),JSON_PRETTY_PRINT);
          
		//parent::display($tpl);
	}

	 
}



