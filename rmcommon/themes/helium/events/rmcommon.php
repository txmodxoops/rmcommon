<?php
/**
 * Common Utilities framework for Xoops
 *
 * Copyright © 2015 Eduardo Cortés http://www.redmexico.com.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.redmexico.com.mx)
 * @license      GNU GPL 2
 * @package      rmcommon
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.redmexico.com.mx
 * @url          http://www.eduardocortes.mx
 */

class HeliumRmcommonPreload
{
    public function eventRmcommonXoopsCommonEnd(){

        $w = RMHttpRequest::get( 'twop6', 'string', '' );

        if ($w == '') return;

        if ($w == 'colortest') {

            include_once XOOPS_ROOT_PATH .'/include/cp_functions.php';

            RMTemplate::get()->header();

            require RMCPATH .'/themes/twop6/include/test-color.php';

            RMTemplate::get()->footer();
            die();
        }

        if ($w == 'about') {

            include_once XOOPS_ROOT_PATH .'/include/cp_functions.php';

            RMTemplate::get()->header();

            require RMCPATH .'/themes/twop6/include/about.php';

            RMTemplate::get()->footer();
            die();

        }

    }

    public function eventRmcommonAdditionalOptions( $settings ){

        $settings['categories']['helium'] = __('Control Panel', 'helium');

        $af_available = RMFunctions::plugin_installed('advform');

        $settings['config'][] = array(
            'name'          => 'helium_logo',
            'title'         => __( 'Logo to use', 'rmcommon' ),
            'description'   => __('You can specify a logo as bitmap but SVG is recommended. The logo will be resize to 29 pixels of height.', 'helium'),
            'formtype'      => $af_available ? 'image-url' : 'textbox',
            'valuetype'     => 'text',
            'default'       => RMCURL . '/themes/helium/images/logo-he.svg',
            'category'      => 'helium'
        );

        return $settings;

    }

    public function eventRmcommonIncludeCommonLanguage(){
        define('NO_XOOPS_SCRIPTS', true);
    }
}
