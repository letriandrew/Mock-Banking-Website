<?php
/**
 * @package         Regular Labs Library
 * @version         23.10.17780
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2023 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Router\Route as JRoute;
use Joomla\CMS\Uri\Uri as JUri;

class Version
{
    /**
     * Get the version of the given extension
     *
     * @param        $alias
     * @param string $type
     * @param string $folder
     *
     * @return string
     */
    public static function get($alias, $type = 'component', $folder = 'system')
    {
        return trim(Extension::getXmlValue('version', $alias, $type, $folder));
    }

    /**
     * Get the version of the given component
     *
     * @param $alias
     *
     * @return string
     */
    public static function getComponentVersion($alias)
    {
        return self::get($alias, 'component');
    }

    /**
     * Get the full footer
     *
     * @param     $name
     * @param int $copyright
     *
     * @return string
     */
    public static function getFooter($name, $copyright = true, $review = true)
    {
        $html = [];

        $html[] = '<div class="rl-footer-extension">' . self::getFooterName($name) . '</div>';
        $html[] = '<div class="rl-footer-documentation">' . self::getFooterDocumentationLink($name) . '</div>';

        if ($copyright && $review)
        {
            $html[] = '<div class="rl-footer-review">' . self::getFooterReview($name) . '</div>';
        }

        if ($copyright)
        {
            $html[] = '<div class="rl-footer-logo">' . self::getFooterLogo() . '</div>';
            $html[] = '<div class="rl-footer-copyright">' . self::getFooterCopyright() . '</div>';
        }

        return '<div class="rl-footer">' . implode('', $html) . '</div>';
    }

    /**
     * Get the major Joomla version
     *
     * @return int
     */
    public static function getMajorJoomlaVersion()
    {
        return (int) JVERSION == 5 ? 4 : (int) JVERSION;
    }

    /**
     * Get the version message
     *
     * @param $alias
     *
     * @return string
     */
    public static function getMessage($alias)
    {
        if ( ! $alias)
        {
            return '';
        }

        $name    = Extension::getNameByAlias($alias);
        $alias   = Extension::getAliasByName($alias);
        $version = self::get($alias);

        if ( ! $version)
        {
            return '';
        }

        return '<div class="alert alert-success" style="display:none;" id="regularlabs_version_' . $alias . '">'
            . self::getMessageText($alias, $name, $version)
            . '</div>';
    }

    /**
     * Get the version of the given module
     *
     * @param $alias
     *
     * @return string
     */
    public static function getModuleVersion($alias)
    {
        return self::get($alias, 'module');
    }

    /**
     * Get the version of the given plugin
     *
     * @param        $alias
     * @param string $folder
     *
     * @return string
     */
    public static function getPluginVersion($alias, $folder = 'system')
    {
        return self::get($alias, 'plugin', $folder);
    }

    /**
     * Get the copyright text for the footer
     *
     * @return string
     */
    private static function getFooterCopyright()
    {
        return JText::_('RL_COPYRIGHT') . ' &copy; ' . date('Y') . ' Regular Labs - ' . JText::_('RL_ALL_RIGHTS_RESERVED');
    }

    /**
     * Get the link to the documentation for the footer
     *
     * @param $name
     *
     * @return string
     */
    private static function getFooterDocumentationLink($name)
    {
        $alias = Extension::getAliasByName($name);

        return JText::sprintf('RL_GO_TO_DOCUMENTATION', '<span class="icon-book" aria-hidden="true"></span>', '<a href="https://docs4.regularlabs.com/' . $alias . '" target="_blank">', '</a>');
    }

    /**
     * Get the Regular Labs logo for the footer
     *
     * @return string
     */
    private static function getFooterLogo()
    {
        return JText::sprintf(
            'RL_POWERED_BY',
            '<a href="https://regularlabs.com" target="_blank">'
            . '<img src="' . JUri::root() . 'media/regularlabs/images/logo.svg" width="149" height="32" alt="Regular Labs">'
            . '</a>'
        );
    }

    /**
     * Get the extension name and version for the footer
     *
     * @param $name
     *
     * @return string
     */
    private static function getFooterName($name)
    {
        $name   = JText::_($name);
        $alias  = Extension::getAliasByName($name);
        $suffix = self::getVersionSuffix($alias);

        return '<a href="https://regularlabs.com/' . $alias . '" target="_blank">' . $name . '</a>' . $suffix;
    }

    /**
     * Get the review text for the footer
     *
     * @param $name
     *
     * @return string
     */
    private static function getFooterReview($name)
    {
        $alias = Extension::getAliasByName($name);

        $jed_url = 'http://regl.io/jed-' . $alias . '#reviews';

        return StringHelper::html_entity_decoder(
            JText::sprintf(
                'RL_JED_REVIEW',
                '<a href="' . $jed_url . '" target="_blank">',
                '</a>'
                . ' <a href="' . $jed_url . '" target="_blank" class="stars">'
                . str_repeat('<span class="icon-star"></span>', 5)
                . '</a>'
            )
        );
    }

    /**
     * Get the version message text
     *
     * @param $alias
     * @param $name
     * @param $version
     *
     * @return array|string
     */
    private static function getMessageText($alias, $name, $version)
    {
        [$url, $onclick] = self::getUpdateLink($alias, $version);

        $href    = $onclick ? '' : 'href="' . $url . '" target="_blank" ';
        $onclick = $onclick ? 'onclick="' . $onclick . '" ' : '';

        $is_pro  = str_contains($version, 'PRO');
        $version = str_replace(['FREE', 'PRO'], ['', ' <small>[PRO]</small>'], $version);

        $msg = '<div class="text-center">'
            . '<span class="ghosted">'
            . JText::sprintf('RL_NEW_VERSION_OF_AVAILABLE', JText::_($name))
            . '</span>'
            . '<br>'
            . '<a ' . $href . $onclick . ' class="btn btn-large btn-success">'
            . '<span class="icon-upload"></span> '
            . StringHelper::html_entity_decoder(JText::sprintf('RL_UPDATE_TO', '<span id="regularlabs_newversionnumber_' . $alias . '"></span>'))
            . '</a>';

        if ( ! $is_pro)
        {
            $msg .= ' <a href="https://regularlabs.com/purchase/cart/add/' . $alias . '" target="_blank" class="btn btn-large btn-primary">'
                . '<span class="icon-basket"></span> '
                . JText::_('RL_GO_PRO')
                . '</a>';
        }

        $msg .= '<br>'
            . '<span class="ghosted">'
            . '[ <a href="https://regularlabs.com/' . $alias . '/changelog" target="_blank">'
            . JText::_('RL_CHANGELOG')
            . '</a> ]'
            . '<br>'
            . JText::sprintf('RL_CURRENT_VERSION', $version)
            . '</span>'
            . '</div>';

        return StringHelper::html_entity_decoder($msg);
    }

    /**
     * Get the url and onclick function for the update link
     *
     * @param $alias
     * @param $version
     *
     * @return array
     */
    private static function getUpdateLink($alias, $version)
    {
        $jversion = self::getMajorJoomlaVersion();

        if ($jversion != 4)
        {
            return ['https://regularlabs.com/' . $alias . '/features', ''];
        }

        $is_pro = str_contains($version, 'PRO');

        if ( ! file_exists(JPATH_ADMINISTRATOR . '/components/com_regularlabsmanager/regularlabsmanager.xml')
            || ! JComponentHelper::isInstalled('com_regularlabsmanager')
            || ! JComponentHelper::isEnabled('com_regularlabsmanager')
        )
        {
            $url = $is_pro
                ? 'https://regularlabs.com/' . $alias . '/features'
                : JRoute::_('index.php?option=com_installer&view=update');

            return [$url, ''];
        }

        return ['index.php?option=com_regularlabsmanager', ''];
    }

    /**
     * Get the version for the footer name
     *
     * @param $alias
     *
     * @return string
     */
    private static function getVersionSuffix($alias)
    {
        $version = self::get($alias);

        if ( ! $version)
        {
            return '';
        }

        if (str_contains($version, 'PRO'))
        {
            return ' v' . str_replace('PRO', '', $version) . ' <small>[PRO]</small>';
        }

        if (str_contains($version, 'FREE'))
        {
            return ' v' . str_replace('FREE', '', $version) . ' <small>[FREE]</small>';
        }

        return ' v' . $version;
    }
}
