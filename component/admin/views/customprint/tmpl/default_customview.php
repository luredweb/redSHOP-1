<?php
/** 
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved. 
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com 
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

?>
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	submitbutton(pressbutton);
}


function submitbutton(pressbutton) 
{
	var form = document.adminForm;
	
	if(pressbutton == 'printall')
	{
		//var w = window.open('','Popup_Window',"width=900,height=800,toolbar=1,scrollbars=1,resizable=1");
	//	form.target = 'Popup_Window';
		form.submit();
		return true;
	}
	return;
}

function printbutton(pressbutton) 
{
	var form = document.adminForm1;
	
	if(pressbutton == 'printall')
	{
		var w = window.open('','Popup_Window',"width=900,height=800,toolbar=1,scrollbars=1,resizable=1");
		form.target = 'Popup_Window';
		form.submit();
		
		return true;
	}
	return;
}

</script>
<div id="editcell">
 	<?php 
	JPluginHelper::importPlugin( 'redshop_custom_views' );
	$dispatcher =& JDispatcher::getInstance(); 
	$printoption = JRequest::getVar('printoption');
	$popup = JRequest::getVar('popup');
	$tmplPath = JPATH_SITE.DS.'plugins'.DS.'redshop_custom_views'.DS.$printoption.DS.'tmpl';
	$this->addTemplatePath($tmplPath);
	echo $this->loadTemplate($printoption);
	
	?>
</div>
