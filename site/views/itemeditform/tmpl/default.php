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

?>


<h1> ITEMEDITFORM </h1>

abracadabra.

<form	id="form-edititem" 
		action="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemeditform.save'); ?>" 
		method="post" 
		class="form-validate" 
		enctype="multipart/form-data"
		>
		
	
		
		
	<button type="submit" name="itemFormSubmit" class="validate btn btn-primary">
		<?php echo JText::_('Opslaan'); ?>
	</button>
	<a class="btn"
		href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemeditform.cancel'); ?>"
		title="<?php echo JText::_('JCANCEL'); ?>">
		<?php echo JText::_('JCANCEL'); ?>
	</a>
	
</form>

