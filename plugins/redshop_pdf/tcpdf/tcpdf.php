<?php
/**
 * @package     Redshopb.Plugin
 * @subpackage  redshop_pdf
 *
 * @copyright   Copyright (C) 2012 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

use Redshop\Order\Template;



defined('JPATH_BASE') or die;

// Load redSHOP library
jimport('redshop.library');

// Load tcPDF library
JLoader::import('helper', __DIR__ . '/helper');

/**
 * PlgRedshop_PdfTcPDF class.
 *
 * @package  Redshopb.Plugin
 * @since    1.0.0
 */
class PlgRedshop_PdfTcPDF extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     */
    protected $autoloadLanguage = true;

    /**
<<<<<<< HEAD
     * [$fontFile description]
     * @var string
     */
    protected $fontFile;

    /**
     * [$fontName description]
     * @var string
     */
    protected  $fontName;

    /**
     * [$TCPDF description]
     * @var object
     */
    protected $TCPDF;

    /**
     * Array of standard font names.
     * @protected
     */
    protected $CoreFonts;
=======
     * [$fontPDF description]
     * @var  null
     */
    protected $fontPDF = null;
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

    /**
     * __construct
     * @param  mixed  &$subject
     * @param  array  $config
     */
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

<<<<<<< HEAD
        $this->TCPDF = new PlgRedshop_PdfTcPDFHelper();
        $this->TCPDF->setFontSubsetting(true);
        $this->TCPDF->SetAuthor(JText::_('LIB_REDSHOP_PDF_CREATOR'));
        $this->TCPDF->SetCreator(JText::_('LIB_REDSHOP_PDF_CREATOR'));
        $this->TCPDF->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $this->TCPDF->SetMargins(8, 8, 8);
        $this->CoreFonts = array(
                    'courier'       => 'courier',
                    'courierb'      => 'courierB',
                    'courieri'      => 'courierI',
                    'courierbi'     => 'courierBI',
                    'helvetica'     => 'helvetica',
                    'helveticab'    => 'helveticaB',
                    'helveticai'    => 'helveticaI',
                    'helveticabi'   => 'helveticaBI',
                    'times'         => 'times',
                    'timesb'        => 'timesB',
                    'timesi'        => 'timesI',
                    'timesbi'       => 'timesBI',
                    'symbol'        => 'symbol',
                    'zapfdingbats'  => 'zapfdingbats'
        );
    }

    /**
     * [settingTCPDF description]
     * @param  integer $setHeaderFont [description]
     * @param  integer $setFont       [description]
     */
    public function settingTCPDF($setHeaderFont = 8, $setFont = 6)
    {
        if ($this->getFont($this->params->get('fontPDF')))
        {   
            $path = JPATH_ROOT . '/media/com_redshop/fonts/';
            
            $this->fontName = TCPDF_FONTS::addTTFfont($path . $this->fontFile, 'TrueTypeUnicode', 32);
        }
        else 
        {
           $this->fontName = empty($this->CoreFonts[$this->fontFile]) ? 'times' : $this->CoreFonts[$this->fontFile]; 
        }

        $this->TCPDF->SetFont($this->fontName, '', $setFont);
        $this->TCPDF->setHeaderFont(array($this->fontName, '', $setHeaderFont));
        $this->TCPDF->AddPage();
    }

    /**
     * get font
     * @param  string $params 
     * @return boolean         
     */
    public function getFont($params)
    {

        if (!strstr($params, 'ttf'))
        {
            $this->fontFile = $params;
            return false;
        }

        $ext = explode('.', $params);

        $this->fontFile = $ext[1] . '.' . $ext[0];

        return true;
=======
        $this->fontPDF = $this->params->get('fontPDF');
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b
    }

    /**
     * Event for create PDF file of order.
     *
     * @param   int      $orderId  Id of order.
     * @param   string   $pdfHtml  Html template of PDF
     * @param   string   $code     Code when generate PDF.
     * @param   boolean  $isEmail  Is generate for use in Email?
     *
     * @return  string            Name of PDF file.
     *
     * @since   1.0.0
     */
    public function onRedshopOrderCreateInvoicePdf($orderId = 0, $pdfHtml = '', $code = 'F', $isEmail = false)
    {
        if (!$orderId || empty($pdfHtml)) {
            return false;
        }

        // Load payment languages
        RedshopHelperPayment::loadLanguages();

        // Changed font to support Unicode Characters - Specially Polish Characters
<<<<<<< HEAD
        $this->TCPDF->SetTitle(JText::sprintf('PLG_REDSHOP_PDF_TCPDF_INVOICE_TITLE', $orderId));
        $this->settingTCPDF();
        $this->TCPDF->writeHTML($pdfHtml);
=======
        $pdfObj = new PlgRedshop_PdfTcPDFHelper;
        $pdfObj->SetTitle(JText::sprintf('PLG_REDSHOP_PDF_TCPDF_INVOICE_TITLE', $orderId));
        $pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdfObj->setHeaderFont(array($this->fontPDF, '', 8));
        $pdfObj->SetFont($this->fontPDF, "", 6);
        $pdfObj->AddPage();
        $pdfObj->writeHTML($pdfHtml);
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

        $invoiceFolder = JPATH_SITE . '/components/com_redshop/assets/document/invoice/';

        if (!$isEmail) {
            ob_end_clean();
<<<<<<< HEAD
            $this->TCPDF->Output($invoiceFolder . '/' . $orderId . ".pdf", $code);
=======
            $pdfObj->Output($invoiceFolder . '/' . $orderId . ".pdf", $code);
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

            return $orderId;
        }

        $invoiceFolder .= $orderId;
        $invoicePdf = 'invoice-' . round(microtime(true) * 1000);

        // Delete currently order invoice
        if (JFolder::exists($invoiceFolder)) {
            JFolder::delete($invoiceFolder);
        }

        JFolder::create($invoiceFolder);

        ob_end_clean();
<<<<<<< HEAD
        $this->TCPDF->Output($invoiceFolder . '/' . $invoicePdf . ".pdf", $code);
=======
        $pdfObj->Output($invoiceFolder . '/' . $invoicePdf . ".pdf", $code);
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

        return $invoicePdf;
    }

    /**
     * Event for create PDF file of multi-order.
     *
     * @param   array   $orderIds  Id of order.
     * @param   string  $pdfHtml   Html template of PDF
     *
     * @return  string
     *
     * @since  1.0.0
     */
    public function onRedshopOrderCreateMultiInvoicePdf($orderIds = array(), $pdfHtml = '')
    {
        if (empty($orderIds) || empty($pdfHtml)) {
            return '';
        }

        RedshopHelperPayment::loadLanguages();

        $cartHelper = rsCarthelper::getInstance();

        // Changed font to support Unicode Characters - Specially Polish Characters
<<<<<<< HEAD
        $this->TCPDF->SetTitle(JText::_('PLG_REDSHOP_PDF_TCPDF_MULTI_INVOICE_TITLE'));
        $this->settingTCPDF();
=======
        $pdfObj = new PlgRedshop_PdfTcPDFHelper;
        $pdfObj->SetTitle(JText::_('PLG_REDSHOP_PDF_TCPDF_MULTI_INVOICE_TITLE'));
        $pdfObj->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdfObj->setHeaderFont(array($this->fontPDF, '', 8));
        $pdfObj->SetFont($this->fontPDF, "", 6);
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

        foreach ($orderIds as $orderId) {
            $ordersDetail = RedshopHelperOrder::getOrderDetails($orderId);
            $message = $pdfHtml;

            $printTag = "<a onclick='window.print();' title='" . JText::_('COM_REDSHOP_PRINT') . "'>"
                . "<img src=" . JSYSTEM_IMAGES_PATH . "printButton.png  alt='" . JText::_('COM_REDSHOP_PRINT') . "' title='"
                . JText::_('COM_REDSHOP_PRINT') . "' /></a>";

            $message = str_replace("{print}", $printTag, $message);
            $message = str_replace("{order_mail_intro_text_title}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT_TITLE'), $message);
            $message = str_replace("{order_mail_intro_text}", JText::_('COM_REDSHOP_ORDER_MAIL_INTRO_TEXT'), $message);
            $message = Template::replaceTemplate($ordersDetail, $message, true);
<<<<<<< HEAD
            $this->TCPDF->AddPage();
            $this->TCPDF->WriteHTML($message, true, false, true, false, '');
        }

        $invoicePdfName = "multiprintorder" . round(microtime(true) * 1000);
        $this->TCPDF->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf", "F");
=======
            $pdfObj->AddPage();
            $pdfObj->WriteHTML($message, true, false, true, false, '');
        }

        $invoicePdfName = "multiprintorder" . round(microtime(true) * 1000);
        $pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $invoicePdfName . ".pdf", "F");
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b
        $storeFiles = array('index.html', '' . $invoicePdfName . '.pdf');

        foreach (glob(JPATH_SITE . "/components/com_redshop/assets/document/invoice/*") as $file) {
            if (!in_array(basename($file), $storeFiles)) {
                JFile::delete($file);
            }
        }

        return $invoicePdfName;
    }

    /**
     * Event for create gift card Pdf file.
     *
     * @param   object  $giftCard  Gift card data.
     * @param   string  $template  HTML code of template.
     *
     * @return  string             Name of generated PDF file.
     *
     * @since   1.0.0
     */
    public function onRedshopOrderCreateGiftCard($giftCard = null, $template = '')
    {
        if (empty($giftCard) || empty($template)) {
            return '';
        }

        $pdf = new PlgRedshop_PdfTcPDFHelper('P', 'mm', 'A4');

        if (file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftCard->giftcard_bgimage) && $giftCard->giftcard_bgimage) {
            $pdf->backgroundImage = REDSHOP_FRONT_IMAGES_RELPATH . 'giftcard/' . $giftCard->giftcard_bgimage;
        }

<<<<<<< HEAD
        $this->TCPDF->SetCreator(PDF_CREATOR);
        $this->TCPDF->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $this->TCPDF->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->TCPDF->SetHeaderMargin(0);
        $this->TCPDF->SetFooterMargin(0);
        $this->TCPDF->setPrintFooter(false);
        $this->TCPDF->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $this->settingTCPDF(PDF_FONT_SIZE_MAIN, 18);
        $this->TCPDF->writeHTML($template, true, false, false, false, '');
        $pdfName = time();
        $this->TCPDF->Output(JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfName . ".pdf", "F");
=======
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetFont($this->fontPDF, '', 18);
        $pdf->AddPage();

        $pdf->writeHTML($template, true, false, false, false, '');
        $pdfName = time();
        $pdf->Output(JPATH_SITE . '/components/com_redshop/assets/orders/' . $pdfName . ".pdf", "F");
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

        return $pdfName;
    }

    /**
     * Event for create shipped invoice PDF file of order.
     *
     * @param   int     $orderId  Id of order.
     * @param   string  $pdfHtml  Html template of PDF
     *
     * @return  string            Name of PDF file.
     *
     * @since   1.0.0
     */
    public function onRedshopPdfCreateShippedInvoice($orderId = 0, $pdfHtml = '')
    {
        if (!$orderId || empty($pdfHtml)) {
            return false;
        }

        // Load payment languages
        RedshopHelperPayment::loadLanguages();

        // Changed font to support Unicode Characters - Specially Polish Characters
<<<<<<< HEAD

        $this->TCPDF->SetTitle(JText::_('PLG_REDSHOP_PDF_TCPDF_SHIPPED_INVOICE_TITLE'));
        $pdfObj->SetMargins(20, 85, 20);
        $this->settingTCPDF(); 

        // Writing Body area
        $this->TCPDF->WriteHTML($pdfHtml, true, false, true, false, '');

        $pdfName = 'shipped_' . $orderId;
        $this->TCPDF->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $pdfName . ".pdf", "F");
=======
        $pdfObj = new PlgRedshop_PdfTcPDFHelper;

        $pdfObj->SetTitle(JText::_('PLG_REDSHOP_PDF_TCPDF_SHIPPED_INVOICE_TITLE'));
        $pdfObj->SetMargins(20, 85, 20);
        $pdfObj->setHeaderFont(array($this->fontPDF, '', 8));
        $pdfObj->SetFont($this->fontPDF, "", 6);

        // Writing Body area
        $pdfObj->AddPage();
        $pdfObj->WriteHTML($pdfHtml, true, false, true, false, '');

        $pdfName = 'shipped_' . $orderId;
        $pdfObj->Output(JPATH_SITE . '/components/com_redshop/assets/document/invoice/' . $pdfName . ".pdf", "F");
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b

        return $pdfName;
    }

    /**
     * Event for generate stock note PDF of order.
     *
     * @param   object  $orderData  Order detail
     * @param   string  $pdfHtml    Html template of PDF
     *
     * @return  void.
     *
     * @since   1.0.0
     */
    public function onRedshopOrderGenerateStockNotePdf($orderData = null, $pdfHtml = '')
    {
        if (empty($orderData) || empty($pdfHtml)) {
            return;
        }

        // Load payment languages
        RedshopHelperPayment::loadLanguages();

        // Changed font to support Unicode Characters - Specially Polish Characters
<<<<<<< HEAD
        $this->TCPDF->SetTitle(JText::sprintf('PLG_REDSHOP_PDF_TCPDF_ORDER_STOCK_NOTE_TITLE', $orderData->order_id));
        $this->TCPDF->SetMargins(15, 15, 15);
        $this->TCPDF->SetHeaderData('', '', '', JText::_('COM_REDSHOP_ORDER') . ' ' . $orderData->order_id);
        $this->settingTCPDF(10, 10);
        $this->TCPDF->WriteHTML($pdfHtml);
        $this->TCPDF->Output('order_stock_note_' . $orderData->order_id . '.pdf', 'D');
=======
        $pdfObj = new PlgRedshop_PdfTcPDFHelper;
        $pdfObj->SetTitle(JText::sprintf('PLG_REDSHOP_PDF_TCPDF_ORDER_STOCK_NOTE_TITLE', $orderData->order_id));
        $pdfObj->SetMargins(15, 15, 15);
        $pdfObj->SetHeaderData('', '', '', JText::_('COM_REDSHOP_ORDER') . ' ' . $orderData->order_id);
        $pdfObj->setHeaderFont(array($this->fontPDF, '', 10));
        $pdfObj->SetFont($this->fontPDF, "", 10);
        $pdfObj->AddPage();
        $pdfObj->WriteHTML($pdfHtml);
        $pdfObj->Output('order_stock_note_' . $orderData->order_id . '.pdf', 'D');
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b
    }

    /**
     * Event for generate invoice PDF of order.
     *
     * @param   object  $orderData  Order detail
     * @param   string  $pdfHtml    Html template of PDF
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function onRedshopOrderGenerateShippingPdf($orderData = null, $pdfHtml = '')
    {
        if (empty($orderData) || empty($pdfHtml)) {
            return;
        }

        // Load payment languages
        RedshopHelperPayment::loadLanguages();

        // Changed font to support Unicode Characters - Specially Polish Characters
<<<<<<< HEAD
        $this->TCPDF->SetTitle(JText::_('COM_REDSHOP_ORDER') . ': ' . $orderData->order_id);
        $this->TCPDF->SetMargins(15, 15, 15);
        $this->TCPDF->SetHeaderData('', '', '', JText::_('COM_REDSHOP_ORDER') . ': ' . $orderData->order_id);
        $this->settingTCPDF(10, 12);
        $this->TCPDF->WriteHTML($pdfHtml);
        $this->TCPDF->Output('Order_' . $orderData->order_id . ".pdf", "D");
    }
}
=======
        $pdfObj = new PlgRedshop_PdfTcPDFHelper;
        $pdfObj->SetTitle(JText::_('COM_REDSHOP_ORDER') . ': ' . $orderData->order_id);
        $pdfObj->SetMargins(15, 15, 15);
        $pdfObj->SetHeaderData('', '', '', JText::_('COM_REDSHOP_ORDER') . ': ' . $orderData->order_id);
        $pdfObj->setHeaderFont(array($this->fontPDF, '', 10));
        $pdfObj->SetFont($this->fontPDF, '', 12);
        $pdfObj->AddPage();
        $pdfObj->WriteHTML($pdfHtml);
        $pdfObj->Output('Order_' . $orderData->order_id . ".pdf", "D");
    }
}
>>>>>>> ff1146c33e294016e15be7f31da73d1316f7874b
