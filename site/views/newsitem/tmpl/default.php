<?php
/**
* @version    CVS: 1.0.0
* @package    Com_Gckloosterveen
* @author     Stephan Zuidberg <stephan@takties.nl>
* @copyright  2016 Takties
* @license    GNU General Public License version 2 or later; see LICENSE.txt
*/
// No direct access
defined('_JEXEC') or die;

$user = JFactory::getUser();
$suer =  JFactory::getUser($this->item->created_by);
$canCreate  = $user->authorise('core.create', 'com_tks_agenda');
 $canCheckin = $user->authorise('core.manage', 'com_tks_agenda');
$canChange  = $user->authorise('core.edit.state', 'com_tks_agenda');
$canDelete  = $user->authorise('core.delete', 'com_tks_agenda');
JRequest::setVar('newscatid',$this->item->newscatid);

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_tks_agenda.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_tks_agenda' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>
<h2><?php echo $this->item->title; ?></h2>
<dl class="article-info">
	<dt>Categorie:</dt><dd><?php echo $this->item->newscatid_title; ?></dd>
	<dt>Geplaatst door:</dt><dd><?php echo $this->item->created_by_name; ?></dd>
</dl>
<div><?php echo $this->item->description; ?></div>
<div>
	
</div>

<?php if($canEdit && $this->item->checked_out == 0): ?>
<a class="btn" href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=newsitem.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_TKS_AGENDA_EDIT_ITEM"); ?></a>
<?php endif; ?>
<?php if(JFactory::getUser()->authorise('core.delete','com_tks_agenda.newsitem.'.$this->item->id) && $user->id == $this->item->created_by):?>
<a class="btn" href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=newsitem.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_TKS_AGENDA_DELETE_ITEM"); ?></a>
<?php endif; ?>
<?php
else:
echo JText::_('COM_TKS_AGENDA_ITEM_NOT_LOADED');
endif;