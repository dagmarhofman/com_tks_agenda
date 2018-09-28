<?php

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
<h5> RECURRING ITEM </h5>

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
	action="<?php echo JRoute::_('index.php?option=com_tks_agenda&layout=edit&id=' . (int) $this->item->id . '&recur_id=' . 6666 ); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">
 <div class="form-inline form-inline-header">
	<div class="control-group">
			<div class="control-label"><label id="jform_title-lbl" for="jform_title" class="">
	Bedrijf</label>
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


			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('start'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('end'); ?></div>
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
