<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php';
jimport('joomla.application.component.controller');
/**
 * Order Detail Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class passwordController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	/*
	 *  Metod to reset Password
	 */
	function reset()
	{
		$post = JRequest::get('post');
		$model = & $this->getModel('password');
		$Itemid = JRequest::getVar('Itemid');
		$layout = "";
		//Request a reset
		if ($model->resetpassword($post))
		{
			$redshopMail = new redshopMail();
			if ($redshopMail->sendResetPasswordMail($post['email']))
			{
				$layout = "&layout=token";
				$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_SEND');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_NOT_SEND');
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_NOT_SEND');
		}
		$this->setRedirect('index.php?option=com_redshop&view=password' . $layout . '&Itemid=' . $Itemid, $msg);
	}

	/*
	 *  Method to changepassword
	 */
	function changepassword()
	{
		$post = JRequest::get('post');
		$model = & $this->getModel('password');
		$token = $post['token'];
		$Itemid = JRequest::getVar('Itemid');
		if ($model->changepassword($token))
		{
			parent::display();
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_TOKEN_ERROR');
			$this->setRedirect('index.php?option=com_redshop&view=password&layout=token&Itemid=' . $Itemid, $msg);
		}
	}

	/*
	 *  Method to setpassword
	 */
	function setpassword()
	{
		$post = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');

		$model = & $this->getModel('password');

		if ($model->setpassword($post))
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_DONE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_ERROR');
		}

		$this->setRedirect('index.php?option=com_redshop&view=login&Itemid=' . $Itemid, $msg);
	}
}
