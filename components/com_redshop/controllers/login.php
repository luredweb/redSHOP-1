<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */
defined('_JEXEC') or die('Restricted access');

/**
 * login Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class loginController extends JControllerLegacy
{
    /*
      *  setlogin function
      */
    function setlogin()
    {
        $username     = JRequest::getVar('username', '', 'method', 'username');
        $password     = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
        $option       = JRequest::getVar('option');
        $Itemid       = JRequest::getVar('Itemid');
        $returnitemid = JRequest::getVar('returnitemid');
        $menu         =& JSite::getMenu();
        $mywishlist   = JRequest::getVar('mywishlist');
        $item         = $menu->getItem($returnitemid);

        include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
        $redhelper = new redhelper();

        $model = &$this->getModel('login');

        $shoppergroupid = JRequest::getInt('protalid', '', 'post', 0);

        $msg = "";

        if ($shoppergroupid != 0)
        {
            $check = $model->CheckShopperGroup($username, $shoppergroupid);
            $link  = "index.php?option=" . $option . "&view=login&layout=portal&protalid=" . $shoppergroupid;
            if ($check > 0)
            {
                $model->setlogin($username, $password);
                $return = JRequest::getVar('return');
            }
            else
            {
                $msg    = JText::_("COM_REDSHOP_SHOPPERGROUP_NOT_MATCH");
                $return = "";
            }
        }
        else
        {
            $model->setlogin($username, $password);
            $return = JRequest::getVar('return');
        }

        if ($mywishlist == 1)
        {
            $wishreturn = JRoute::_('index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid=' . $Itemid, false);
            $this->setRedirect($wishreturn);
        }
        else
        {
            if ($item)
            {
                $link = $item->link . '&Itemid=' . $returnitemid;
            }
            else
            {
                $link = 'index.php?option=' . $option . '&Itemid=' . $returnitemid;
            }

            if (!empty($return))
            {
                $s_Itemid = $redhelper->getCheckoutItemid();
                $Itemid   = $s_Itemid ? $s_Itemid : $Itemid;
                $return   = JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid, false);

                $this->setRedirect($return);
            }
            else
            {
                $this->setRedirect($link, $msg);
            }
        }
    }

    /*
      *  logout function
      */
    function logout()
    {

        $mainframe     = JFactory::getApplication();
        $logout_itemid = JRequest::getVar('logout');
        $menu          =& JSite::getMenu();
        $item          = $menu->getItem($logout_itemid);
        if ($item)
        {
            $link = JRoute::_($item->link . '&Itemid=' . $logout_itemid);
        }
        else
        {
            $link = JRoute::_('index.php?option=com_redshop');
        }
        $mainframe->logout();
        $this->setRedirect($link);
    }
}
