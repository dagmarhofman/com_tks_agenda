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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_tks_agenda');
$canEdit    = $user->authorise('core.edit', 'com_tks_agenda');
$canCheckin = $user->authorise('core.manage', 'com_tks_agenda');
$canChange  = $user->authorise('core.edit.state', 'com_tks_agenda');
$canDelete  = $user->authorise('core.delete', 'com_tks_agenda');

?>





<form action="<?php echo JRoute::_('index.php?option=com_tks_agenda&view=items'); ?>" method="post"
      name="adminForm" id="adminForm">
<div class="g-container">
	<div class="g-grid">
	 	<div class="g-block  size-100">
	       <div class="g-title page-header"><h3></h3></div>
	    </div>	
	</div>
		<div class="g-grid">
	 	<div class="g-block  size-100">
	   <div class="pull-right form-inline">
			<div class="btn-group">
				<button class="btn btn-primary" data-calendar-nav="prev"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> <span>Vorige</span></button>
				<button class="btn" data-calendar-nav="today">Vandaag</button>
				<button class="btn btn-primary" data-calendar-nav="next"><span>Volgende</span> <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-warning active" data-calendar-view="month"><i class="fa fa-calendar" aria-hidden="true"></i> <span>Maand</span></button>
 				<button class="btn btn-warning" data-calendar-view="day"><span>Dag</span> <i class="fa fa-calendar-o" aria-hidden="true"></i></button>
			</div>
		</div>
	    </div>	
	</div>
	<div class="g-grid">
	 	<div class="g-block  size-100">

  			<div id="calendar">
  			
  			
  			</div>
  			
	    </div>	
	</div>
</div>

	
	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemform.edit&id=0&mode=create', false, 2); ?>"
		   class="btn btn-success btn-small"><i
				class="fa fa-add"></i>
			<?php echo JText::_('Reserveer vergaderruimte'); ?></a>
	<?php endif; ?>


<div class="modal hide fade" id="events-modal">
    <div class="modal-header">
        <h3>Reservering details</h3>
    </div>
    <div class="modal-body" style="height: 400px">
    </div>
 
</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">


	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {
		var item_id = jQuery(this).attr('data-item-id');
		<?php if($canDelete) : ?>
		if (confirm("<?php echo JText::_('COM_TKS_AGENDA_DELETE_MESSAGE'); ?>")) {
			window.location.href = '<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemform.remove&id=', false, 2) ?>' + item_id;
		}
		<?php endif; ?>
	}
</script>


