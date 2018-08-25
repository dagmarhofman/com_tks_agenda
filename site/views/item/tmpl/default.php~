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
         
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_tks_agenda.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_tks_agenda' . $this->item->id)) {
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}

$startdate = explode(' ',date('d-m-Y H:i',strtotime($this->item->start)));

$enddate = explode(' ',date('d-m-Y H:i',strtotime($this->item->end)));
   ?>
<?php if ($this->item) : ?>
<h4><?php echo $this->item->created_by_name;?></h4>
<div class="start-tijd"><?php echo $startdate[0];?> - <strong><?php echo $startdate[1];?></strong></div>
<div class="end-tijd"><?php echo $enddate[0];?> - <strong><?php echo $enddate[1];?></strong></div>
 
 <?php if($this->item->recurring == true  && isset($this->item->recur_type)): ?>
    <?php switch ($this->item->recur_type) {
                case "dag":
                $date_calculation = "een dagelijks";
                break;
            case "week":
                $date_calculation = "een weekelijks";
                break;
            case "maand":
                $date_calculation = "een maandelijks";
                break;
            }
            if( $this->item->recurring == "No" ) {
					$date_calculation = "geen";            
            }
            
            ?>
            <p>Dit is <?php echo $date_calculation;?> terugkomende reservering.</p>
    <?php endif; ?>





    <?php if(!empty($this->item->reason)) : ?>
           <?php echo '<p>'.$this->item->reason.'</p>'; ?>
    <?php endif; ?>

    <?php 

     if($userId == $this->item->created_by): ?>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=item.edit&id='.$this->item->id); ?>"><?php echo JText::_("Bewerk"); ?></a>
    <?php endif; ?>


    <?php 

    if($userId == $this->item->created_by):?>
        <a class="btn delete-button" href="#"><?php echo JText::_("Verwijder"); ?></a>
         <script type="text/javascript">

            jQuery(document).ready(function () {
                jQuery('.delete-button').click(deleteItem);
            });

            function deleteItem() {
                var item_id = jQuery(this).attr('data-item-id');
                 if (confirm("<?php echo JText::_('COM_TKS_AGENDA_DELETE_MESSAGE'); ?>")) {
                    window.location.href = '<?php echo JRoute::_('index.php?option=com_tks_agenda&task=item.remove&id='.$this->item->id, false, 2) ?>';
                }
     }
</script>
        <?php endif; ?>

       



    <?php
else:
    echo JText::_('COM_TKS_AGENDA_ITEM_NOT_LOADED');
endif;




		 