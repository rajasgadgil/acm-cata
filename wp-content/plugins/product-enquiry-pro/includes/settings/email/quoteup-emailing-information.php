<?php

namespace Includes\Settings;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/*
 * This function is used to display email settings
 */
function quoteEmailInformationSection($form_data)
{
    ?>
    <fieldset>            
        <!--Emailing information-->
        <?php echo '<legend>'.__('Emailing Information', 'quoteup').'</legend>';
        recipientEmailID($form_data);
        sendMailToAdmin($form_data);
        sendMailToAuthor($form_data);
        defaultSubject($form_data);
    ?>
    </fieldset>
    <?php
}

/*
 * This function is used to display Recipient email id field
 */
function recipientEmailID($form_data)
{
    $userMail = !isset($form_data[ 'user_email' ]) ? '' : $form_data[ 'user_email' ];
    ?>
    <div class="fd">
            <div class='left_div'>
                <label for="wdm_user_email"> <?php _e('Recipient Email Addresses', 'quoteup') ?> </label>
            </div>
            <div class='right_div'>
                <?php
                $helptip = __('You can add multiple email addresses separated by comma', 'quoteup');
                echo \quoteupHelpTip($helptip, true);
    ?>
                <input type="text" class="wdm_wpi_input wdm_wpi_text email" name="wdm_form_data[user_email]" id="wdm_user_email" value="<?php echo $userMail;
    ?>"/>
                <span class='email_error'  style="vertical-align:top" > </span>
            </div>
            <div class='clear'></div>
        </div >
    <?php
}

/*
 * This function is used to display Send Mail to Admin field
 */
function sendMailToAdmin($form_data)
{
    ?>
    <div class="fd">
        <div class='left_div'>
            <label for="send-mail-to-admin"> <?php _e('Send mail to Admin', 'quoteup') ?> </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('When checked, sends the enquiry email to the Admin email address specified under Settings -> General', 'quoteup');
            echo \quoteupHelpTip($helptip, true);
    ?>
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox" value="1" <?php checked(1, isset($form_data[ 'send_mail_to_admin' ]) ? $form_data[ 'send_mail_to_admin' ] : 0);
    ?> id="send-mail-to-admin" /> 
            <input type="hidden" name="wdm_form_data[send_mail_to_admin]" value="<?php echo isset($form_data[ 'send_mail_to_admin' ]) && $form_data[ 'send_mail_to_admin' ] == 1 ? $form_data[ 'send_mail_to_admin' ] : 0 ?>" /> 

        </div>
        <div class='clear'></div>
    </div >
    <?php
}

/*
 * This function is used to display Send mail to Author field
 */
function sendMailToAuthor($form_data)
{
    ?>
    <div class="fd">
        <div class='left_div'>
            <label for="send-mail-to-product-author"> <?php _e('Send mail to Product Author', 'quoteup') ?> </label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('When checked, sends enquiry email to author/owner of the Product', 'quoteup');
            echo \quoteupHelpTip($helptip, true);
    ?>
            <input type="checkbox" class="wdm_wpi_input wdm_wpi_checkbox" value="1" <?php checked(1, isset($form_data[ 'send_mail_to_author' ]) ? $form_data[ 'send_mail_to_author' ] : 0);
    ?> id="send-mail-to-admin" /> 
            <input type="hidden" name="wdm_form_data[send_mail_to_author]" value="<?php echo isset($form_data[ 'send_mail_to_author' ]) && $form_data[ 'send_mail_to_author' ] == 1 ? $form_data[ 'send_mail_to_author' ] : 0 ?>" />
        </div>
        <div class='clear'></div>
    </div >
    <?php
}

/*
 * This function is used to display Default Subject field
 */
function defaultSubject($form_data)
{
    ?>
    <div class="fd">
        <div class='left_div'>
            <label for="wdm_default_sub"> <?php _e('Default Subject', 'quoteup') ?></label>
        </div>
        <div class='right_div'>
            <?php
            $helptip = __('Subject to be used if customer does not enter a subject', 'quoteup');
            echo \quoteupHelpTip($helptip, true);
    ?>
            <input type="text" class="wdm_wpi_input wdm_wpi_text" name="wdm_form_data[default_sub]" id="wdm_default_sub"
                   value="<?php echo empty($form_data[ 'default_sub' ]) ? _e('Enquiry or Quote Request for Products from  ', 'quoteup').get_bloginfo('name') : $form_data[ 'default_sub' ];
    ?>"  />
        </div>
        <div class='clear'></div>
    </div>
    </fieldset>
    <?php
}
quoteEmailInformationSection($form_data);
