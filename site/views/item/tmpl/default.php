<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Gckloosterveen
 * @author     Stephan Zuidberg <stephan@takties.nl>
 * @copyright  Copyright (C) 2016. Alle rechten voorbehouden.
 * @license    GNU General Public License versie 2 of hoger; Zie LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$user       = JFactory::getUser();
$userId     = $user->get('id');
$superadmin = $user->authorise('core.admin');

$canCreate  = $user->authorise('core.create', 'com_tks_agenda');
$canEdit    = $user->authorise('core.edit', 'com_tks_agenda');
$canCheckin = $user->authorise('core.manage', 'com_tks_agenda');
$canChange  = $user->authorise('core.edit.state', 'com_tks_agenda');
$canDelete  = $user->authorise('core.delete', 'com_tks_agenda');
$canEditOwn = $user->authorise('core.edit.own', 'com_tks_agenda.item.'.$this->item->id);
$canDeleteMedewerker  = $user->authorise('core.delete.medewerker', 'com_tks_agenda.item.'.$this->item->id);
$canEditMedewerker    = $user->authorise('core.edit.medewerker', 'com_tks_agenda.item.'.$this->item->id);
$canEditOwnMedewerker    = $user->authorise('core.edit.own.medewerker','com_tks_agenda.item.'.$this->item->id);
$recurring_id = -1;
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_tks_agenda.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_tks_agenda' . $this->item->id)) {
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}



?>


<?php 

	 $s = $this->item->stamp;
	
	
	if( $this->item ) {
		if( $this->item->recurring == "Yes" ) {
			echo "<h1> Herhaalde afspraak (" . $this->item->recur_type . "): </h1>";		
				$start_date = strtotime($this->item->start);
				$end_date =	strtotime($this->item->end);		    		
		} else {
			echo "<h1> Afspraak: </h1>";
				$start_date = strtotime($this->item->start);
				$end_date =	strtotime($this->item->end);		    		

}		
		if($userId == $this->item->created_by):	

			$start_date_out = date('j F Y H:i', $start_date );
			$end_date_out = date('j F Y H:i', $end_date );
			
			$start_date_outf = date('Y-m-d H:i:s', $start_date );
			$end_date_outf = date('Y-m-d H:i:s', $end_date );

			if( $start_date_outf == $s['start_stamp'] && $end_date_outf == $s['end_stamp'] ) {
?>
	<div class="start-tijd">
	<strong style="color:blue;"><?php echo $start_date_out;?> - <?php echo $end_date_out;?></strong>
	</div>
<?php
			} else {
?>
	<div class="start-tijd">
	<strong><?php echo $start_date_out;?> - <?php echo $end_date_out;?></strong>
	</div>

<?php			
			
			}
?>
<h2> Reden </h2>
<?php
	echo $this->item->reason;
			echo '<br/>';
			foreach( $this->item->recur_events as $event ) {
				if( $event[1] == $this->item->id ) {

					if( $s['start_stamp'] == $event[2] && $s['end_stamp'] == $event[3] ) {
						echo '<span style="color:blue;"> <strong> ' . $event[2] . ' - ' . $event[3] . ' </strong> </span><br/>';
						$recurring_id = $event[0];
						$this->item->recur_id = $event[0];
					} else {
						echo '<span style="color:black;">' . $event[2] . ' - ' . $event[3] . '</span><br/>';
					}
				}
			}

?>

   <div class="modal-footer">
 
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=item.edit&id='. $this->item->id . '&mode=update' . '&recur_id=' . $recurring_id   ); ?>"><?php echo JText::_("Bewerk"); ?></a>
<?php 

		endif; 
?>
<?php
		if($userId == $this->item->created_by):
?>


		<a class="btn delete-button" href="#"><?php echo JText::_("Verwijder"); ?></a>
<script type="text/javascript">
      	jQuery(document).ready(function () {
         	jQuery('.delete-button').click(deleteItem);
         });
			function deleteItem() {
         	var item_id = jQuery(this).attr('data-item-id');
            if (confirm("<?php echo JText::_('COM_TKS_AGENDA_DELETE_MESSAGE'); ?>")) {
            	window.location.href = '<?php echo JRoute::_('index.php?option=com_tks_agenda&task=item.remove&id=' . $this->item->id  . '&recur_id=' . $recurring_id , false, 2) ?>';
            }
     		}
</script>

<?php 
		endif; 
?>

   
        <a class="btn sluit-button" href="#"> <?php echo JText::_("Sluit venster"); ?> </a>
    </div>
    
<script type="text/javascript">
      	jQuery(document).ready(function () {
         	jQuery('.sluit-button').click(sluitItem);
         });
			function sluitItem() {
	
            window.location.href = '<?php echo JRoute::_('index.php?option=com_tks_agenda&view=items', false) ?>'; 		
				window.location.reload(true);	
     		}
</script>
    
 
<?php		
			
	} else {
		echo JText::_('COM_TKS_AGENDA_ITEM_NOT_LOADED');
	}
	
?>




		 