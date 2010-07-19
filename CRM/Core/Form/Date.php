<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2010                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2010
 * $Id$
 *
 */

Class CRM_Core_Form_Date
{

    /**
     * various Date Formats
     */
    const
        DATE_yyyy_mm_dd     = 1,
        DATE_mm_dd_yy       = 2,
        DATE_mm_dd_yyyy     = 4,
        DATE_Month_dd_yyyy  = 8,
        DATE_dd_mon_yy      = 16,
        DATE_dd_mm_yyyy     = 32;
    

    /**
     * This function is to build the date-format form
     *
     * @param Object  $form   the form object that we are operating on
     * 
     * @static
     * @access public
     */
    static function buildAllowedDateFormats( &$form ) {
        
        $dateOptions = array();
        
        require_once "CRM/Utils/System.php";
        if ( CRM_Utils_System::getClassName( $form ) == 'CRM_Activity_Import_Form_UploadFile' ) {
            $dateText = ts('yyyy-mm-dd OR yyyy-mm-dd HH:mm OR yyyymmdd OR yyyymmdd HH:mm (1998-12-25 OR 1998-12-25 15:33 OR 19981225 OR 19981225 10:30 OR ( 2008-9-1 OR 2008-9-1 15:33 OR 20080901 15:33)');
        } else {
            $dateText = ts('yyyy-mm-dd OR yyyymmdd (1998-12-25 OR 19981225) OR (2008-9-1 OR 20080901)');
        }

        $dateOptions[] = HTML_QuickForm::createElement('radio', null, null, $dateText, self::DATE_yyyy_mm_dd);

        $dateOptions[] = HTML_QuickForm::createElement('radio', null, null, ts('mm/dd/yy OR mm-dd-yy (12/25/98 OR 12-25-98) OR (9/1/08 OR 9-1-08)'), self::DATE_mm_dd_yy);
        $dateOptions[] = HTML_QuickForm::createElement('radio', null, null, ts('mm/dd/yyyy OR mm-dd-yyyy (12/25/1998 OR 12-25-1998) OR (9/1/2008 OR 9-1-2008)'), self::DATE_mm_dd_yyyy);
        $dateOptions[] = HTML_QuickForm::createElement('radio', null, null, ts('Month dd, yyyy (December 12, 1998)'), self::DATE_Month_dd_yyyy);
        $dateOptions[] = HTML_QuickForm::createElement('radio', null, null, ts('dd-mon-yy OR dd/mm/yy (25-Dec-98 OR 25/12/98)'), self::DATE_dd_mon_yy);
        $dateOptions[] = HTML_QuickForm::createElement('radio', null, null, ts('dd/mm/yyyy (25/12/1998) OR (1/9/2008)'), self::DATE_dd_mm_yyyy);
        $form->addGroup($dateOptions, 'dateFormats', ts('Date Format'), '<br/>');
        $form->setDefaults(array('dateFormats' => self::DATE_yyyy_mm_dd));
    }
    
    /**
     * This function is to build the date range - relative or absolute
     *
     * @param Object  $form   the form object that we are operating on
     * 
     * @static
     * @access public
     */
    static function buildDateRange( &$form, $fieldName, $count = 1, $required = false, $addReportFilters = true ) {
        $selector = array (ts('Choose Date Range'),
                           'this.year'        => ts('This Year'),
                           'this.fiscal_year' => ts('This Fiscal Year'),
                           'this.quarter'     => ts('This Quarter'),
                           'this.month'       => ts('This Month'),
                           'this.week'        => ts('This Week'),
                           'this.day'         => ts('This Day'),
                           
                           'previous.year'        => ts('Previous Year'),
                           'previous.fiscal_year' => ts('Previous Fiscal Year'),
                           'previous.quarter'     => ts('Previous Quarter'),
                           'previous.month'       => ts('Previous Month'),
                           'previous.week'        => ts('Previous Week'),
                           'previous.day'         => ts('Previous Day'),

                           'previous_before.year'    => ts('Previous Before Year'),
                           'previous_before.quarter' => ts('Previous Before Quarter'),
                           'previous_before.month'   => ts('Previous Before Month'),
                           'previous_before.week'    => ts('Previous Before Week'),
                           'previous_before.day'     => ts('Previous Before Day'),
                           
                           'previous_2.year'    => ts('Previous 2 Years'),
                           'previous_2.quarter' => ts('Previous 2 Quarters'),
                           'previous_2.month'   => ts('Previous 2 Months'),
                           'previous_2.week'    => ts('Previous 2 Weeks'),
                           'previous_2.day'     => ts('Previous 2 Days'),

                           'earlier.year'    => ts('Earlier Year'),
                           'earlier.quarter' => ts('Earlier Quarter'),
                           'earlier.month'   => ts('Earlier Month'),
                           'earlier.week'    => ts('Earlier Week'),
                           'earlier.day'     => ts('Earlier Day'),

                           'greater.year'    => ts('Greater Year'),
                           'greater.quarter' => ts('Greater Quarter'),
                           'greater.month'   => ts('Greater Month'),
                           'greater.week'    => ts('Greater Week'),
                           'greater.day'     => ts('Greater Day')
                           );
        if( $addReportFilters ) {
            require_once 'CRM/Report/Form.php';
            $selector += CRM_Report_Form::getOperationPair( CRM_Report_FORM::OP_DATE );
        }
        $config =& CRM_Core_Config::singleton();
        //if fiscal year start on 1 jan then remove fiscal year task
        //form list
        if ( $config->fiscalYearStart['d'] == 1 & $config->fiscalYearStart['M'] == 1 ) {
            unset($selector['this.fiscal_year']);
            unset($selector['previous.fiscal_year']);
        }

        $form->add('select',
                   "{$fieldName}_relative",
                   ts('Relative Date Range'),
                   $selector,
                   $required,
                   array('onclick' => "showAbsoluteRange(this.value, '{$fieldName}_relative');"));
        
        $form->addDateRange($fieldName);
    }

}



