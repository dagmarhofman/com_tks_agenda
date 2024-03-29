<?php

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
$superadmin = $user->authorise('core.admin');

if (!$this->items) :
	echo '<h5>Geen bestanden</h5>';
else :
?>
<form action="<?php echo JRoute::_('index.php?option=com_tks_agenda&view=downloads'); ?>" method="post" name="adminForm" id="adminForm">
	
	<table class="table table-striped table-bordered" id="downloadList">
		<thead>
			<tr>
				
				<th class=''>
					<?php echo JHtml::_('grid.sort',  'Titel', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
					<?php echo JHtml::_('grid.sort',  'Bestandsnaam', 'a.file', $listDirn, $listOrder); ?>
				</th>
				
				 
				
			</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item) : ?>
			<?php if ($this->escape($item->state) == 1): ?>
			
			<tr class="row<?php echo $i % 2; ?>">
			<td>		
					<?php echo $this->escape($item->title); ?>
				</td>
				<td>
					<?php
						if (!empty($item->file)) :
							$fileArr = (array) explode(',', $item->file);
							foreach ($fileArr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'downloads' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="Download bestand">' . $singleFile . '</a> ';
								endif;
							endforeach;
						else:
							echo $item->file;
					endif; ?>
				</td>
				
				<?php endif;?>
				<?php if ($canDelete): ?>
				<td class=" ">
			 
					<?php if ($canDelete): ?>
					<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=downloadform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php endif;?>
<?php if ($canCreate) : ?>
<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=downloadform.edit&id=0', false, 2); ?>"
	class="btn btn-success btn-small"><i
	class="icon-plus"></i>
<?php echo JText::_('Upload bestand'); ?></a>
<?php endif; ?>