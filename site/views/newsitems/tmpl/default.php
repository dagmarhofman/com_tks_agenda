<?php

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$username     = $user->get('name');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_tks_agenda');
$canEdit    = $user->authorise('core.edit', 'com_tks_agenda');
$canCheckin = $user->authorise('core.manage', 'com_tks_agenda');
$canChange  = $user->authorise('core.edit.state', 'com_tks_agenda');
$canDelete  = $user->authorise('core.delete', 'com_tks_agenda');
 $isroot = $user->authorise('core.admin');
  
?>

<form action="<?php echo JRoute::_('index.php?option=com_tks_agenda&view=newsitems'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php //echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
 		 
	 

		<?php foreach ($this->items as $i => $item) : ?>

			<?php $canEdit = $user->authorise('core.edit', 'com_tks_agenda'); ?>

			<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_tks_agenda')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<div class="row<?php echo $i % 2; ?>">

				<?php /*if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<div class="center">
						<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_tks_agenda&task=newsitem.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
						<?php if ($item->state == 1): ?>
							<i class="icon-publish"></i>
						<?php else: ?>
							<i class="icon-unpublish"></i>
						<?php endif; ?>
						</a>
					</div>
				<?php endif; */?>
 
				<h3 class="title">
				<?php /*if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'newsitems.', $canCheckin); ?>
				<?php endif;*/ ?>
				<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&view=newsitem&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->title); ?></a>
				</h3>

				<?php $created_by_name = JFactory::getUser($item->created_by)->get('name');?>
				<dl class="article-info">
					<dt>Categorie:</dt><dd><?php echo $item->newscatid; ?></dd>
					<dt>Geplaatst door:</dt><dd><?php echo $created_by_name; ?></dd>
				</dl>

					<div class="description">

 
 					<?php echo tks_agendaSiteFrontendHelper::truncate($item->description,50,'</p>','...'); ?>
				</div>
					<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_tks_agenda&view=newsitem&id='.(int) $item->id); ?>">
															<?php echo JText::_('Lees meer'); ?> 
					</a>



				<?php if ($canEdit || $canDelete && $isroot == true): ?>
					<div class="well" style="margin-top: 20px;">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=newsitemform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i> <?php echo JText::_('Bewerk'); ?> </a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=newsitemform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i> <?php echo JText::_('Verwijder'); ?></a>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		<?php endforeach; ?>
  			<div>
				<?php echo $this->pagination->getListFooter(); ;
 ?>
</div>
	 
	<?php if ($canCreate && $isroot == true) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=newsitemform.edit&id=0', false, 2); ?>" class="btn btn-success btn-small"> <i class="icon-plus"></i>
			<?php echo JText::_('Maak nieuws item aan'); ?></a> 
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_TKS_AGENDA_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
