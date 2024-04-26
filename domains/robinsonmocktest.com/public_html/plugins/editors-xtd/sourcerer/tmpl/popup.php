<?php
/**
 * @package         Sourcerer
 * @version         10.0.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2023 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Editor\Editor as JEditor;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Form\Form as JForm;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;

$xmlfile = dirname(__FILE__, 2) . '/forms/popup.xml';

$form = new JForm('sourcerer');
$form->loadFile($xmlfile, 1, '//config');

$editor_plugin = JPluginHelper::getPlugin('editors', 'codemirror');

if (empty($editor_plugin))
{
    JFactory::getApplication()->enqueueMessage(JText::sprintf('RL_ERROR_CODEMIRROR_DISABLED', JText::_('SOURCERER'), '<a href="index.php?option=com_plugins&filter[folder]=editors&filter[search]=codemirror" target="_blank">', '</a>'), 'error');

    return '';
}

$user   = JFactory::getApplication()->getIdentity() ?: JFactory::getUser();
$editor = JEditor::getInstance('codemirror');
?>

<div class="container-fluid container-main">
    <form action="index.php" id="sourcererForm" method="post" style="width:99%">
        <input type="hidden" name="type" id="type" value="url">

        <?php echo JHtml::_('uitab.startTabSet', 'main', ['active' => 'code']); ?>

        <?php
        $tabs = [
            'code'     => 'SRC_CODE',
            'css'      => 'SRC_CSS',
            'js'       => 'SRC_JAVASCRIPT',
            'php'      => 'SRC_PHP',
            'settings' => 'SRC_TAG_SETTINGS',
        ];

        foreach ($tabs as $id => $title)
        {
            echo JHtml::_('uitab.addTab', 'main', $id, JText::_($title));
            echo $form->renderFieldset($id);
            echo JHtml::_('uitab.endTab');
        }
        ?>

        <?php echo JHtml::_('uitab.endTabSet'); ?>
    </form>
</div>
