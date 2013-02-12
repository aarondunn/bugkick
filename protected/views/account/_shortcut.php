<div class="shortcut-container" style="display:none;" id="shortcuts">
    <div class="shortcut-header">
        <span><?php echo Yii::t('main', 'Keyboard Shortcuts') ?></span> <a class="shortcut-close-link" onclick="$('#shortcuts').css('display', 'none');" href="#"><?php echo Yii::t('main', 'Close') ?></a>
    </div>
    <div class="shortcut-top">
        <?php echo Yii::t('main', 'Keyboard Shortcuts are') ?>
        <?php
          if (User::checkHotkeyPreference())
              echo Yii::t('main', 'enabled') .'. <a href="'.$this->createUrl('/settings/shortcutsState').'">'.  Yii::t('main', 'Disable') .'</a>';
          else
              echo Yii::t('main', 'disabled') .'. <a href="'.$this->createUrl('/settings/shortcutsState').'">'.  Yii::t('main', 'Enable') .'</a>';
        ?>
    </div>
    <div class="shortcut-body">
        <table>
                <td>
                    <table>
                        <tr>
                            <td class="short-key">
                            </td>
                            <td>
                                <h4><?php echo Yii::t('main', 'On All Pages') ?>:</h4>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                n
                            </td>
                            <td>
                                : <?php echo Yii::t('main', 'Create New Ticket') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                p
                            </td>
                            <td>
                               : <?php echo Yii::t('main', 'Go to Projects Page') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                h
                            </td>
                            <td>
                                : <?php echo Yii::t('main', 'Go to Dashboard Page') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                s
                            </td>
                            <td>
                                : <?php echo Yii::t('main', 'Go to Settings Page') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                Shift + /
                            </td>
                            <td>
                                  : <?php echo Yii::t('main', 'Open Shortcuts Help') ?>
                            </td>
                        </tr> 
                    </table>
                </td>
                <td>
                    <table>
                       <tr>
                            <td  class="short-key">
                            </td>
                            <td>
                               <h4><?php echo Yii::t('main', 'On the View Ticket Page') ?>:</h4>
                            </td>
                        </tr>
                        <tr>
                            <td  class="short-key">
                                e
                            </td>
                            <td>
                                : <?php echo Yii::t('main', 'Edit Ticket') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                c
                            </td>
                            <td>
                                : <?php echo Yii::t('main', 'Close Ticket') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="short-key">
                                d
                            </td>
                            <td>
                                 : <?php echo Yii::t('main', 'Delete Ticket') ?>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>
</div>