<?php


// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_tks_agenda', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_tks_agenda/js/form.js');

$user    = JFactory::getUser();
$canEdit = tks_agendaSiteFrontendHelper::canUserEdit($this->item, $user);


?>

<div class="download-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_TKS_AGENDA_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_TKS_AGENDA_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1>Upload</h1>
		<?php endif; ?>

		<form id="form-download" action="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=download.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
			
				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

				<input type="hidden" name="jform[state]" value="1" />

				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php //echo $this->form->renderField('time_created'); ?>

	<?php echo $this->form->renderField('title'); ?>

	<?php echo $this->form->renderField('alias'); ?>

	<?php echo $this->form->renderField('file'); ?>

	<?php if (!empty($this->item->file)) :
		foreach ((array) $this->item->file as $singleFile) : 
			if (!is_array($singleFile)) :
				echo '<a href="' . JRoute::_(JUri::root() . 'downloads' . DIRECTORY_SEPARATOR . $singleFile, false) . '">' . $singleFile . '</a> ';
			endif;
		endforeach;
	endif; ?>
	<input type="hidden" name="jform[file_hidden]" id="jform_file_hidden" value="<?php echo str_replace('Array,', '', implode(',', (array) $this->item->file)); ?>" />
	<?php echo $this->form->renderField('description'); ?>
				 
			 
			<div class="control-group">
				<div class="controls">

						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=downloadform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_tks_agenda"/>
			<input type="hidden" name="task"
				   value="downloadform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
