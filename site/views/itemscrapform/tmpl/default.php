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

$app   = JFactory::getApplication();

$mode = $app->getUserState('com_tks_agenda.edit.item.mode');
$overlap = $app->getUserState('com_tks_agenda.edit.item.overlap');

?>

<?php

	if( $mode == 'create' ) {
		echo '<h1> Huur vergaderruimte </h1>' ;
	}
	elseif ( $mode = 'update' ) {
		echo '<h1> Bewerk huur vergaderruimte </h1>' ;
	}

	$i = 0;
	echo 'Op basis van de door u ingevoerde gegevens kunnen de volgende herhaalafspraken niet doorgaan: <br/>';	
	while( $overlap[0][$i] != NULL ) {
		echo $overlap[0][$i] . ' - ' . $overlap[1][$i] . '<br/>';	
		$i++;	
	}
	echo 'Wilt u deze afspraken schrappen uit de agenda?<br/>';

?>

<form action="<?php echo JRoute::_('index.php?option=com_tks_agenda&view=itemscrapform'); ?>" method="post"
      name="scrapForm" id="scrapForm">
      
		<div class="control-group">
			<div class="controls">

					<a class="btn"
			     href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemscrapform.submit'); ?>"
				   title="<?php echo JText::_('Opslaan'); ?>">
							<?php echo JText::_('Opslaan'); ?>
					</a>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemscrapform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>
	
 </form>