<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldDateTime extends JFormField {

    protected $type = 'DateTime';

    public function getInput() {
         $doc = JFactory::getDocument();
          JHtml::_('jquery.framework');  
          JHtml::_('bootstrap.framework');
          $doc->addStylesheet(JURI::root().'components/com_tks_agenda/assets/css/jquery.datetimepicker.min.css');
          $doc->addScript(JURI::root().'components/com_tks_agenda/assets/js/jquery.datetimepicker.full.min.js'); 
         

          $javascript = ';(function($) {'."\n";
            $javascript .= '$(document).ready(function(){'."\n";
              $javascript .= '$.datetimepicker.setLocale("nl");'."\n";
              $javascript .= '$("#'.$this->id.'").datetimepicker({  format:"Y-m-d H:i:00",minDate:0,step:15});'."\n";
            $javascript .= '});'."\n";
          $javascript .= '})(jQuery);'."\n";

          $doc->addScriptDeclaration($javascript);
          $html = '<div class="controls">';
             $html .= '<input id="'.$this->id.'" name="'.$this->name.'" class="dateinput" type="text" value="'.$this->value.'" >';
            $html .= '</div>';
        return $html;
                    
    }
}