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

$user       = JFactory::getUser();
$userId     = $user->get('id');

$canCreate  = $user->authorise('core.create', 'com_gckloosterveen');
$canEdit    = $user->authorise('core.edit', 'com_gckloosterveen');
$canCheckin = $user->authorise('core.manage', 'com_gckloosterveen');
$canChange  = $user->authorise('core.edit.state', 'com_gckloosterveen');
$canDelete  = $user->authorise('core.delete', 'com_gckloosterveen');
$canDeleteMedewerker  = $user->authorise('core.delete.medewerker', 'com_gckloosterveen');
$canEditMedewerker    = $user->authorise('core.edit.medewerker', 'com_gckloosterveen');
$canEditOwnMedewerker    = $user->authorise('core.edit.own.medewerker', 'com_gckloosterveen');



  

$profile = JUserHelper::getProfile($user->id);
$avatar = $profile->profile5['avatar'];
$company = $profile->profile5['company'];
?>
<div class="g-grid">
	<div class="g-block size-100">
		<h2>Medewerkers portaal</h2>
	</div>
	<div class="g-block size-15">
		<div class="g-content">
			<div class="portal-avatar">
				<img src="<?php echo $avatar ?>" alt="<?php echo htmlspecialchars($user->get('name'));?>"/>
			</div>
		</div>
	</div>
	<div class="g-block size-85">
		<div class="g-content">
			<h4><strong><?php echo htmlspecialchars($user->get('name'));?></strong> - <small><?php echo $company;?> </small></h4>
			
			<a class="btn btn-primary btn-small" href="<?php echo JRoute::_('index.php?option=com_users&view=profile&layout=edit');?>" title="My Profile">Bewerk profiel</a>
<a class="btn btn-primary btn-small" href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&'. JSession::getFormToken().'=1&return='.base64_encode(JURI::current())); ?>
" title="My Profile">Uitloggen</a>			
		</div>
	</div>
	
	
</div>