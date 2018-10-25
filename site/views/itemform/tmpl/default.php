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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select'); 
		
// Load admin language file
$lang = JFactory::getLanguage();
$user = JFactory::getUser();
$lang->load('com_tks_agenda', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_tks_agenda/js/form.js');

 if (!empty($this->item->id)): 
	$profile = JUserHelper::getProfile($this->item->created_by);
	$avatar = $profile->profile5['avatar'];
	$company = $profile->profile5['company'];
	$username = JFactory::getUser($this->item->created_by)->name;
else :
	$profile = JUserHelper::getProfile($user->id);
	$avatar = $profile->profile5['avatar'];
	$company = $profile->profile5['company'];
	$username = JFactory::getUser()->name;

endif;

$app   = JFactory::getApplication();
$recur_id = $app->getUserState( 'com_tks_agenda.edit.item.recurid' );		 

if( $recur_id  > 0 )
	$is_top_item = false;
else 
$is_top_item = true;

//var_export($this->item);
//  echo '<pre>';var_dump($profile);'</pre>';
/**/
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-item').submit(function (event) {
			});
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-item').submit(function (event) {
				
			});
		});
	}

			

</script>

<div class="item-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Bewerk huur vergaderruimte</h1>
	<?php else: ?>
		<h1>Huur vergaderruimte</h1>
	<?php endif; ?>
	<?php
	if(!$is_top_item ) {
		echo "<strong style=\"color:red;\"> U bewerkt een herhaalafspraak </strong> "; 	
	}
	?>
	<form id="form-item" action="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=item.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
		
	<div class="g-grid">
			<div class="g-block size-50">
				<div class="control-group">
					<div class="control-label"><label>Bedrijf</label></div>
					<div class="controls"><input type="text" disabled="" value="<?php echo $company;?>"/></div>
				</div>

			</div>
			<div class="g-block size-50">
				<div class="control-group">
					<div class="control-label"><label>Medewerker</label></div>
					<div class="controls"><input type="text" disabled="" value="<?php echo $username;?>"/></div>
				</div>
			</div>
			<div class="g-block size-33">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('start'); ?></div>
				</div>
			</div>
			<div class="g-block size-33">
				<div class="control-group">
					<div class="control-label"><label>Eind *</label></div>
					<div class="controls"><?php echo $this->form->getInput('end'); ?></div>
				</div>
			</div>
			<div class="g-block size-33">
				 
			</div>

<?php
		
		if (empty($this->item->id) || $is_top_item  ):
?>			
			
			<div class="g-block size-15">
					<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('recurring'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('recurring'); ?></div>
				</div>
			</div>

			<div id="recurring_attrib">			
						
			<div class="g-block size-25">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('recur_type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('recur_type'); ?></div>
				</div>
			</div>

			<div class="g-block size-60">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('end_recur'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('end_recur'); ?></div>
				</div>
			</div>

			</div>
			
<?php		
	endif;
?>			
	 		<div class="g-block size-100">
	 		<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('reason'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('reason'); ?></div>
				</div>
	 		</div>
		</div>

			<div class="fltlft" style="display:none;" >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
		 
				<?php if (!JFactory::getUser()->authorise('core.admin','tks_agenda')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-item").appendChild(input);
                    });
                </script>
             <?php endif; ?>
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" name="itemFormSubmit" class="validate btn btn-primary">
						<?php echo JText::_('Opslaan'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_tks_agenda&task=itemform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>
	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	 <div class="hide">
		<?php echo $this->form->getInput('catid'); ?>
	</div>
 	<input type="hidden" name="jform[catid]" value="<?php echo $this->item->catid; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
		<input type="hidden" name="option" value="com_tks_agenda"/>
		<input type="hidden" name="task" value="itemform.save"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>


