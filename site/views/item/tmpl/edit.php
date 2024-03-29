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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');


$user       = JFactory::getUser();




// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_tks_agenda/css/edit.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'item.cancel') {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		else {
			
			if (task != 'item.cancel' && document.formvalidator.isValid(document.id('item-form'))) {
				
				Joomla.submitform(task, document.getElementById('item-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_tks_agenda&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">
 <div class="form-inline form-inline-header">
	<div class="control-group">
			<div class="control-label"><label id="jform_title-lbl" for="jform_title" class="">
	Bedrijf</label>
</div>
		<div class="controls">

		<?php if(empty($this->item->created_by)){ 

			$profile = JUserHelper::getProfile($user->id);

		$company = $profile->profile5['company'];


?>
 		<input type="text" value="<?php echo $company ?>" disabled/>

				<?php } 
				else{ 

								$profile = JUserHelper::getProfile($this->item->created_by);
		$company = $profile->profile5['company'];


?>
		<input class="input-large" type="text" value="<?php echo $company ?>" disabled/>

				<?php } ?>

				</div>

</div>
</div>


	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_TKS_AGENDA_TITLE_ITEM', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

			<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
			</div>
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>


			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('start'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('end'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('recurring'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('recurring'); ?></div>
			</div>
				<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('recur_type'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('recur_type'); ?></div>
			</div>

		<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('end_recur'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('end_recur'); ?></div>
			</div>

			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('reason'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('reason'); ?></div>
			</div>


				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if (JFactory::getUser()->authorise('core.admin','tks_agenda')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
