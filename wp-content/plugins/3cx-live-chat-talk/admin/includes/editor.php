<?php

class WP3CXC2C_Editor
{

    private $clicktotalk_form;
    private $panels = array();

    public function __construct(WP3CXC2C_ClickToTalkForm $clicktotalk_form)
    {
        $this->clicktotalk_form = $clicktotalk_form;
    }

    public function add_panel($id, $title, $callback)
    {
        if (wp3cxc2c_is_name($id)) {
            $this->panels[$id] = array(
                'title' => $title,
                'callback' => $callback,
            );
        }
    }


    public function display()
    {
        if (empty($this->panels)) {
            return;
        }

        echo '<ul id="clicktotalk-form-editor-tabs">';

        foreach ($this->panels as $id => $panel) {
            echo sprintf('<li id="%1$s-tab"><a href="#%1$s">%2$s</a></li>',
                esc_attr($id), esc_html($panel['title']));
        }

        echo '</ul>';

        foreach ($this->panels as $id => $panel) {
            echo sprintf('<div class="clicktotalk-form-editor-panel" id="%1$s">',
                esc_attr($id));

            if (is_callable($panel['callback'])) {
                $this->notice($id, $panel);
                call_user_func($panel['callback'], $this->clicktotalk_form);
            }

            echo '</div>';
        }
    }

    public function notice($id, $panel)
    {
        echo '<div class="config-error"></div>';
    }

    public function image_uploader($name, $width, $height)
    {

        // Set variables
        $options = get_option('RssFeedIcon_settings');
        $default_image = plugins_url('img/no-image.png', __FILE__);

        if (!empty($options[$name])) {
            $image_attributes = wp_get_attachment_image_src($options[$name], array($width, $height));
            $src = $image_attributes[0];
            $value = $options[$name];
        } else {
            $src = $default_image;
            $value = '';
        }

        $text = __('Upload', RSSFI_TEXT);

        // Print HTML field
        echo '
            <div class="upload">
                <img data-src="' . $default_image . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />
                <div>
                    <input type="hidden" name="RssFeedIcon_settings[' . $name . ']" id="RssFeedIcon_settings[' . $name . ']" value="' . $value . '" />
                    <button class="upload_image_button button">' . $text . '</button>
                    <button type="submit" class="remove_image_button button">&times;</button>
                </div>
            </div>
        ';
    }
}

function wp3cxc2c_editor_panel_config($post)
{
    wp3cxc2c_editor_box_config($post);
}

function wp3cxc2c_editor_panel_style($post)
{
    wp3cxc2c_editor_box_style($post);
}

function wp3cxc2c_editor_box_config($post, $args = '')
{
    $args = wp_parse_args($args, array(
        'id' => 'wp3cxc2c-config',
        'name' => 'config',
        'title' => __('Configuration', '3cx-clicktotalk'),
        'use' => null,
    ));

    $id = esc_attr($args['id']);

    $config = wp_parse_args($post->prop($args['name']), array(
        'active' => false,
        'pbxurl' => '',
        'aspect' => 'both',
        'enablevideo' => false,
        'requireidentity' => 'none',
        'chatboxtitle' => 'Let\'s chat',
        'phoneboxtitle' => 'Call Us',
        'welcomemessage' => 'Hello, how can we help?',
        'welcomemessagesender' => 'Support',
        'authenticationmessage' => '',
        'unavailablemessage' => '',
        'chatboxwindowicon' => '',
        'operatoricon' => '',
        'popout' => true,
        'enableonmobile' => true,
        'allowsoundnotifications' => false,
        'showtypingindicator' => true,
        'autofocus' => true,
        'showoperatoractualname' => false,
    ));

    ?>
    <div class="clicktotalk-form-editor-box-config" id="<?php echo $id; ?>">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script src="<?php echo wp3cxc2c_plugin_url('admin/js/color.js'); ?>"></script>
        <script src="<?php echo wp3cxc2c_plugin_url('admin/js/iris.js'); ?>"></script>

        <fieldset>
            <legend>
                <?php
                $desc_link = wp3cxc2c_link(
                    __('https://www.3cx.com/setting-up-mail/', '3cx-clicktotalk'),
                    __('Setting Up Mail', '3cx-clicktotalk'));
                ?>
            </legend>
            <div class="mainContainer">
                <a class="button-secondary" style="float:right;"
                   href="https://www.3cx.com/docs/live-chat-talk-wordpress-plugin/" target="_blank">
                    <img src="<?php echo wp3cxc2c_plugin_url('images/info.png'); ?>" alt="info" class="info"
                         title="Help"/> Help
                </a>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-pbxurl"><?php echo esc_html(__('3CX Click2Talk URL', '3cx-clicktotalk')); ?>
                            &nbsp;
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="url" id="<?php echo $id; ?>-pbxurl" name="<?php echo $id; ?>[pbxurl]"
                               class="inputSize large-text code" value="<?php echo esc_attr($config['pbxurl']); ?>"
                               data-config-field="<?php echo sprintf('%s.pbxurl', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-aspect"><?php echo esc_html(__('Mode', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <select id="<?php echo $id; ?>-aspect" name="<?php echo $id; ?>[aspect]"

                                class="customSelect large-text code" value="<?php echo esc_attr($config['aspect']); ?>"
                                data-config-field="<?php echo sprintf('%s.aspect', esc_attr($args['name'])); ?>">
                            <option value="both"<?php if ($config['aspect'] == 'both') {
                                echo " selected";
                            } ?>>Chat and Phone
                            </option>
                            <option value="chat"<?php if ($config['aspect'] == 'chat') {
                                echo " selected";
                            } ?>>Chat only
                            </option>
                            <option value="phone"<?php if ($config['aspect'] == 'phone') {
                                echo " selected";
                            } ?>>Phone only
                            </option>
                            <option value="bothignoreownership"<?php if ($config['aspect'] == 'bothignoreownership') {
                                echo " selected";
                            } ?>>Chat and Phone - ignore queue ownership
                            </option>
                        </select>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input style="margin-rigth: 10px;" type="checkbox" id="<?php echo $id; ?>-enablevideo"
                               name="<?php echo $id; ?>[enablevideo]"
                               class="code" <?php if ($config['enablevideo']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.enablevideo', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-enablevideo"><?php echo esc_html(__('Enable video call', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-chatboxtitle"><?php echo esc_html(__('Chat box window title', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" maxlength="50" id="<?php echo $id; ?>-chatboxtitle"
                               name="<?php echo $id; ?>[chatboxtitle]" class="inputSize large-text code"
                               value="<?php echo esc_attr($config['chatboxtitle']); ?>"
                               data-config-field="<?php echo sprintf('%s.chatboxtitle', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-chatboxwindowicon"><?php echo esc_html(__('Chat box window icon (Dimension (H): 32px)', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input style="width: 87%!important" type="text" id="chatboxwindowicon"
                               oninput="checkImageUrl('chatboxwindowicon')"
                               name="<?php echo $id; ?>[chatboxwindowicon]"
                               class="large-text code" size="70"
                               value="<?php echo esc_attr($config['chatboxwindowicon']); ?>"
                               data-config-field="<?php echo sprintf('%s.chatboxwindowicon', esc_attr($args['name'])); ?>"/>
                        <button style="width: 88px!important;  line-height: 30px!important;" type="button"
                                title="Upload Image"
                                name="window-title-upload-btn"
                                id="window-title-upload-btn" class="button-secondary">
                            <img src="<?php echo wp3cxc2c_plugin_url('images/upload.png'); ?>" class="tcxhint"
                                 title="Upload Image"/>Upload
                        </button>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input onclick="checkOperatorName();" type="checkbox"
                               id="<?php echo $id; ?>-showoperatoractualname"
                               name="<?php echo $id; ?>[showoperatoractualname]"
                               class="code" <?php if ($config['showoperatoractualname']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.showoperatoractualname', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-showoperatoractualname"><?php echo esc_html(__('Show Operator Name', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-welcomemessagesender"><?php echo esc_html(__('Operator Static Name', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" maxlength="50" id="<?php echo $id; ?>-welcomemessagesender"
                               name="<?php echo $id; ?>[welcomemessagesender]" <?php if ($config['showoperatoractualname']) echo 'readonly'; ?>
                               class="inputSize large-text code"
                               value="<?php echo esc_attr($config['welcomemessagesender']); ?>"
                               data-config-field="<?php echo sprintf('%s.welcomemessagesender', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-operatoricon"><?php echo esc_html(__('Operator image (Dimensions (H x W): 50px 50px)', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input style="width: 87%!important; line-height: 30px!important;" type="text"
                               oninput="checkImageUrl('operatoricon')"
                               id="operatoricon" name="<?php echo $id; ?>[operatoricon]" class="large-text code"
                               size="70" value="<?php echo esc_attr($config['operatoricon']); ?>"
                               data-config-field="<?php echo sprintf('%s.operatoricon', esc_attr($args['name'])); ?>"/>
                        <button style="width: 88px!important;  line-height: 30px!important;" type="button"
                                title="Upload Image"
                                name="woperator-icon-upload-btn"
                                id="operator-icon-upload-btn" class="button-secondary">
                            <img src="<?php echo wp3cxc2c_plugin_url('images/upload.png'); ?>" class="tcxhint"
                                 title="Upload Image"/>Upload
                        </button>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-welcomemessage"><?php echo esc_html(__('Welcome message', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" maxlength="250" id="<?php echo $id; ?>-welcomemessage"
                               name="<?php echo $id; ?>[welcomemessage]" class="inputSize large-text code"
                               value="<?php echo esc_attr($config['welcomemessage']); ?>"
                               data-config-field="<?php echo sprintf('%s.welcomemessage', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-phoneboxtitle"><?php echo esc_html(__('Phone call hover text', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" maxlength="50" id="<?php echo $id; ?>-phoneboxtitle"
                               name="<?php echo $id; ?>[phoneboxtitle]" class="inputSize large-text code"
                               value="<?php echo esc_attr($config['phoneboxtitle']); ?>"
                               data-config-field="<?php echo sprintf('%s.phoneboxtitle', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-requireidentity"><?php echo esc_html(__('User identification form', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <select id="<?php echo $id; ?>-requireidentity" name="<?php echo $id; ?>[requireidentity]"
                                class="customSelect large-text code"
                                value="<?php echo esc_attr($config['requireidentity']); ?>"
                                data-config-field="<?php echo sprintf('%s.requireidentity', esc_attr($args['name'])); ?>">
                            <option value="none"<?php if ($config['requireidentity'] == 'none') {
                                echo " selected";
                            } ?>>None
                            </option>
                            <option value="name"<?php if ($config['requireidentity'] == 'name') {
                                echo " selected";
                            } ?>>Name only
                            </option>
                            <option value="email"<?php if ($config['requireidentity'] == 'email') {
                                echo " selected";
                            } ?>>Email only
                            </option>
                            <option value="both"<?php if ($config['requireidentity'] == 'both') {
                                echo " selected";
                            } ?>>Name and Email
                            </option>
                        </select>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-authenticationmessage"><?php echo esc_html(__('User Identification Message', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" maxlength="250" id="<?php echo $id; ?>-authenticationmessage"
                               name="<?php echo $id; ?>[authenticationmessage]" class="inputSize large-text code"
                               value="<?php echo esc_attr($config['authenticationmessage']); ?>"
                               data-config-field="<?php echo sprintf('%s.authenticationmessage', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-unavailablemessage"><?php echo esc_html(__('Unavailable Message', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" maxlength="250" id="<?php echo $id; ?>-unavailablemessage"
                               name="<?php echo $id; ?>[unavailablemessage]" class="inputSize large-text code"
                               value="<?php echo esc_attr($config['unavailablemessage']); ?>"
                               data-config-field="<?php echo sprintf('%s.unavailablemessage', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-popout" name="<?php echo $id; ?>[popout]"
                               class="code" <?php if ($config['popout']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.popout', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-popout"><?php echo esc_html(__('Pop-out', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-enableonmobile"
                               name="<?php echo $id; ?>[enableonmobile]"
                               class="code" <?php if ($config['enableonmobile']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.enableonmobile', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-enableonmobile"><?php echo esc_html(__('Enable on mobile', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-allowsoundnotifications"
                               name="<?php echo $id; ?>[allowsoundnotifications]"
                               class="code" <?php if ($config['allowsoundnotifications']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.allowsoundnotifications', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-allowsoundnotifications"><?php echo esc_html(__('Sound notifications', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-showtypingindicator"
                               name="<?php echo $id; ?>[showtypingindicator]"
                               class="code" <?php if ($config['showtypingindicator']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.showtypingindicator', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-showtypingindicator"><?php echo esc_html(__('Show typing indicator', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-autofocus"
                               name="<?php echo $id; ?>[autofocus]"
                               class="code" <?php if ($config['autofocus']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.autofocus', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-autofocus"><?php echo esc_html(__('Auto focus', '3cx-clicktotalk')); ?>
                    </div>
                </div>
            </div>


            <!--    Script for loading the Media Uploader-->
            <script type="text/javascript">

                function checkImageUrl(uniqueId) {
                    var e_input = document.getElementById(uniqueId);
                    var e_input_low = e_input.value.toLowerCase();
                    if (!e_input.value) {
                        e_input.setCustomValidity("");
                    } else if (!(/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9.-]+([\-\.]{1})[a-z0-9]{1,5}(:[0-9]{1,5})?(\/[a-zA-Z0-9-._~:\/?#@!$&*=;+%()']*)?$/i.test(e_input_low))) {
                        e_input.setCustomValidity("Invalid URL");
                    } else {
                        e_input.setCustomValidity("");
                    }
                }

                function checkOperatorName() {
                    var uniqueID = "<?php echo $id; ?>" + "-showoperatoractualname";
                    var displayUniqueID = "<?php echo $id; ?>" + "-welcomemessagesender";
                    var e_input = document.getElementById(uniqueID);
                    var d_input = document.getElementById(displayUniqueID);
                    if (e_input.checked == true) {
                        d_input.readOnly = true;
                    } else {
                        d_input.readOnly = false;
                    }
                }

                function setDefaultOption() {
                    $("#<?php echo $id?>" + "-popout").prop("checked", true);
                    $("#<?php echo $id?>" + "-showtypingindicator").prop("checked", true);
                }

                function checkOptions(value, status) {
                    if (value === 'phone') {
                        $("#<?php echo $id?>" + "-enablevideo").prop("disabled", true);
                        $("#<?php echo $id?>" + "-chatboxtitle").prop("readonly", true);
                        $("#<?php echo $id?>" + "-authenticationmessage").prop("readonly", true);
                        $("#<?php echo $id?>" + "-unavailablemessage").prop("readonly", true);
                        $("#chatboxwindowicon").prop("readonly", true);
                        $("#window-title-upload-btn").prop("disabled", true);
                        $("#<?php echo $id?>" + "-showoperatoractualname").prop("disabled", true);
                        $("#<?php echo $id?>" + "-welcomemessagesender").prop("readonly", true);
                        $("#operatoricon").prop("readonly", true);
                        $("#operator-icon-upload-btn").prop("disabled", true);
                        $("#<?php echo $id?>" + "-welcomemessage").prop("readonly", true);
                        $("#<?php echo $id?>" + "-phoneboxtitle").prop("readonly", false);
                        $("#<?php echo $id?>" + "-requireidentity").prop("disabled", true);
                        $("#<?php echo $id?>" + "-popout").prop("disabled", true);
                        $("#<?php echo $id?>" + "-enableonmobile").prop("disabled", false);
                        $("#<?php echo $id?>" + "-allowsoundnotifications").prop("disabled", true);
                        $("#<?php echo $id?>" + "-showtypingindicator").prop("disabled", true);
                        $("#<?php echo $id?>" + "-autofocus").prop("disabled", true);
                        $("#wp3cxc2c-style-windowwidth").prop("readonly", true);
                        $("#wp3cxc2c-style-windowheight").prop("readonly", true);
                        $("#wp3cxc2c-style-minimizedstyle").prop("disabled", true);
                        $("#wp3cxc2c-style-minimized").prop("disabled", true);
                        $("#wp3cxc2c-style-facebookintegrationurl").prop("readonly", true);
                        $("#wp3cxc2c-style-twitterintegrationurl").prop("readonly", true);
                        $("#wp3cxc2c-style-emailintegrationurl").prop("readonly", true);
                        $("#wp3cxc2c-style-enablepoweredby").prop("disabled", true);
                    } else if (value === 'chat') {
                        $("#<?php echo $id?>" + "-enablevideo").prop("disabled", true);
                        $("#<?php echo $id?>" + "-chatboxtitle").prop("readonly", false);
                        $("#<?php echo $id?>" + "-authenticationmessage").prop("readonly", false);
                        $("#<?php echo $id?>" + "-unavailablemessage").prop("readonly", false);
                        $("#chatboxwindowicon").prop("readonly", false);
                        $("#window-title-upload-btn").prop("disabled", false);
                        $("#<?php echo $id?>" + "-showoperatoractualname").prop("disabled", false);
                        if ($("#<?php echo $id?>" + "-showoperatoractualname").is(":checked")) {
                            $("#<?php echo $id?>" + "-welcomemessagesender").prop("readonly", true);
                        } else {
                            $("#<?php echo $id?>" + "-welcomemessagesender").prop("readonly", false);
                        }
                        $("#operatoricon").prop("readonly", false);
                        $("#operator-icon-upload-btn").prop("disabled", false);
                        $("#<?php echo $id?>" + "-welcomemessage").prop("readonly", false);
                        $("#<?php echo $id?>" + "-phoneboxtitle").prop("readonly", true);
                        $("#<?php echo $id?>" + "-requireidentity").prop("disabled", false);
                        $("#<?php echo $id?>" + "-popout").prop("disabled", false);
                        $("#<?php echo $id?>" + "-enableonmobile").prop("disabled", false);
                        $("#<?php echo $id?>" + "-allowsoundnotifications").prop("disabled", false);
                        $("#<?php echo $id?>" + "-showtypingindicator").prop("disabled", false);
                        $("#<?php echo $id?>" + "-autofocus").prop("disabled", false);
                        $("#wp3cxc2c-style-windowwidth").prop("readonly", false);
                        $("#wp3cxc2c-style-windowheight").prop("readonly", false);
                        $("#wp3cxc2c-style-minimizedstyle").prop("disabled", false);
                        $("#wp3cxc2c-style-minimized").prop("disabled", false);
                        $("#wp3cxc2c-style-facebookintegrationurl").prop("readonly", false);
                        $("#wp3cxc2c-style-twitterintegrationurl").prop("readonly", false);
                        $("#wp3cxc2c-style-emailintegrationurl").prop("readonly", false);
                        $("#wp3cxc2c-style-enablepoweredby").prop("disabled", false);
                    } else {
                        $("#<?php echo $id?>" + "-enablevideo").prop("disabled", false);
                        $("#<?php echo $id?>" + "-chatboxtitle").prop("readonly", false);
                        $("#<?php echo $id?>" + "-authenticationmessage").prop("readonly", false);
                        $("#<?php echo $id?>" + "-unavailablemessage").prop("readonly", false);
                        $("#chatboxwindowicon").prop("readonly", false);
                        $("#window-title-upload-btn").prop("disabled", false);
                        $("#<?php echo $id?>" + "-showoperatoractualname").prop("disabled", false);
                        if ($("#<?php echo $id?>" + "-showoperatoractualname").is(":checked")) {
                            $("#<?php echo $id?>" + "-welcomemessagesender").prop("readonly", true);
                        } else {
                            $("#<?php echo $id?>" + "-welcomemessagesender").prop("readonly", false);
                        }
                        $("#operatoricon").prop("readonly", false);
                        $("#operator-icon-upload-btn").prop("disabled", false);
                        $("#<?php echo $id?>" + "-welcomemessage").prop("readonly", false);
                        $("#<?php echo $id?>" + "-phoneboxtitle").prop("readonly", true);
                        $("#<?php echo $id?>" + "-requireidentity").prop("disabled", false);
                        $("#<?php echo $id?>" + "-popout").prop("disabled", false);
                        $("#<?php echo $id?>" + "-enableonmobile").prop("disabled", false);
                        $("#<?php echo $id?>" + "-allowsoundnotifications").prop("disabled", false);
                        $("#<?php echo $id?>" + "-showtypingindicator").prop("disabled", false);
                        $("#<?php echo $id?>" + "-autofocus").prop("disabled", false);
                        $("#wp3cxc2c-style-windowwidth").prop("readonly", false);
                        $("#wp3cxc2c-style-windowheight").prop("readonly", false);
                        $("#wp3cxc2c-style-minimizedstyle").prop("disabled", false);
                        $("#wp3cxc2c-style-minimized").prop("disabled", false);
                        $("#wp3cxc2c-style-facebookintegrationurl").prop("readonly", false);
                        $("#wp3cxc2c-style-twitterintegrationurl").prop("readonly", false);
                        $("#wp3cxc2c-style-emailintegrationurl").prop("readonly", false);
                        $("#wp3cxc2c-style-enablepoweredby").prop("disabled", false);
                    }
                    if (status === 'onchange' && value !== 'phone') {
                        setDefaultOption();
                    }
                }

                jQuery(document).ready(function () {
                    checkOptions(document.getElementById("<?php echo $id; ?>-aspect").value, 'onload');
                });


                jQuery(document).ready(function ($) {
                    $("#<?php echo $id; ?>-aspect").change(function () {
                        checkOptions(this.value, 'onchange');
                    });

                    $('#window-title-upload-btn').click(function (e) {
                        e.preventDefault();
                        var image = wp.media({
                            title: 'Upload Image',
                            multiple: false,
                            library: {
                                type: ['image']
                            }
                        }).open()
                            .on('select', function (e) {
                                // This will return the selected image from the Media Uploader, the result is an object
                                var uploaded_image = image.state().get('selection').first();
                                // We convert uploaded_image to a JSON object to make accessing it easier
                                // Output to the console uploaded_image
                                var image_url = uploaded_image.toJSON().url;
                                // Let's assign the url value to the input field
                                $('#chatboxwindowicon').val(image_url);


                            });
                    });
                    $('#operator-icon-upload-btn').click(function (e) {
                        e.preventDefault();
                        var image = wp.media({
                            title: 'Upload Image',
                            multiple: false,
                            library: {
                                type: ['image']
                            }
                        }).open()
                            .on('select', function (e) {
                                // This will return the selected image from the Media Uploader, the result is an object
                                var uploaded_image = image.state().get('selection').first();
                                // We convert uploaded_image to a JSON object to make accessing it easier
                                // Output to the console uploaded_image
                                var image_url = uploaded_image.toJSON().url;
                                // Let's assign the url value to the input field
                                $('#operatoricon').val(image_url);
                            });
                    });
                });
            </script>
        </fieldset>
    </div>
    <?php
}

//Style section begins

function wp3cxc2c_editor_box_style($post, $args = '')
{
    $args = wp_parse_args($args, array(
        'id' => 'wp3cxc2c-style',
        'name' => 'style',
        'title' => __('Style', '3cx-clicktotalk'),
        'use' => null,
    ));

    $id = esc_attr($args['id']);

    $style = wp_parse_args($post->prop($args['name']), array(
        'windowposition' => '',
        'primarycolor' => '#007bc7',
        'secondarycolor' => '#ffffff',
        'windowwidth' => '250',
        'windowheight' => '470',
        'minimized' => false,
        'minimizedstyle' => 'bubble',
        'animationstyle' => 'none',
        'enablepoweredby' => false,
        'emailintegrationurl' => '',
        'facebookintegrationurl' => '',
        'twitterintegrationurl' => '',

    ));

    ?>
    <div class="clicktotalk-form-editor-box-style" id="<?php echo $id; ?>">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
        <script src="<?php echo wp3cxc2c_plugin_url('admin/js/color.js'); ?>"></script>
        <script src="<?php echo wp3cxc2c_plugin_url('admin/js/iris.js'); ?>"></script>

        <fieldset>
            <legend>
            </legend>
            <div class="mainContainer">
                <a class="button-secondary" style="float:right;"
                   href="https://www.3cx.com/docs/live-chat-talk-wordpress-plugin/" target="_blank">
                    <img src="<?php echo wp3cxc2c_plugin_url('images/info.png'); ?>" alt="info" class="info"
                         title="Help"/> Help
                </a>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-primarycolor"><?php echo esc_html(__('Primary color (Chat Window title background color, Minimised/Phone only mode background color, Authentication button color, Text input line color)', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input style="width: 90%!important" oninput="updatePrimaryColorPicker()" type="text"
                               id="<?php echo $id; ?>-primarycolor"
                               name="<?php echo $id; ?>[primarycolor]" class="inputSize code"
                               value="<?php echo esc_attr($style['primarycolor']); ?>"
                               data-config-field="<?php echo sprintf('%s.primarycolor', esc_attr($args['name'])); ?>"/>
                        <input style="width: 8%!important" type="color" id="choosePrimaryColors"
                               oninput="updatePrimaryColor()"
                               name="<?php echo $id; ?>[primarycolor]"
                               value="<?php echo esc_attr($style['primarycolor']); ?>"
                               data-config-field="<?php echo sprintf('%s.primarycolor', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-secondarycolor"><?php echo esc_html(__('Secondary color (Chat window title text color, Minimised/Phone only mode icon color) ', '3cx-clicktotalk')); ?>
                    </div>

                    <div class="customInput extraSpaceTop">
                        <input style="width: 90%!important" type="text" oninput="updateSecondaryColorPicker()"
                               id="<?php echo $id; ?>-secondarycolor"
                               name="<?php echo $id; ?>[secondarycolor]" class="inputSize code"
                               value="<?php echo esc_attr($style['secondarycolor']); ?>"
                               data-config-field="<?php echo sprintf('%s.secondarycolor', esc_attr($args['name'])); ?>"/>
                        <input style="width: 8%!important" type="color" id="chooseSecondaryColors"
                               oninput="updateSecondaryColor()"
                               name="<?php echo $id; ?>[secondarycolor]" class="inputSize code"
                               value="<?php echo esc_attr($style['secondarycolor']); ?>"
                               data-config-field="<?php echo sprintf('%s.secondarycolor', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-windowwidth"><?php echo esc_html(__('Panel width (Chat window width in pixels)', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" oninput="checkIfNumber('windowwidth');" id="<?php echo $id; ?>-windowwidth"
                               name="<?php echo $id; ?>[windowwidth]" class="inputSize code"
                               value="<?php echo esc_attr($style['windowwidth']); ?>"
                               data-config-field="<?php echo sprintf('%s.windowwidth', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-windowheight"><?php echo esc_html(__('Panel height (Chat window height in pixels)', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="text" oninput="checkIfNumber('windowheight');" id="<?php echo $id; ?>-windowheight"
                               name="<?php echo $id; ?>[windowheight]" class="inputSize code"
                               value="<?php echo esc_attr($style['windowheight']); ?>"
                               data-config-field="<?php echo sprintf('%s.windowheight', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-windowposition"><?php echo esc_html(__('Position', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <select id="<?php echo $id; ?>-windowposition" name="<?php echo $id; ?>[windowposition]"
                                class="customSelect large-text code"
                                value="<?php echo esc_attr($style['windowposition']); ?>"
                                data-config-field="<?php echo sprintf('%s.windowposition', esc_attr($args['name'])); ?>">
                            <option value="right"<?php if ($style['windowposition'] == 'right') {
                                echo " selected";
                            } ?>>Bottom Right
                            </option>
                            <option value="left"<?php if ($style['windowposition'] == 'left') {
                                echo " selected";
                            } ?>>Bottom Left
                            </option>
                        </select>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-animationstyle"><?php echo esc_html(__('Animation style', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <select id="<?php echo $id; ?>-animationstyle" name="<?php echo $id; ?>[animationstyle]"
                                class="customSelect large-text code"
                                value="<?php echo esc_attr($style['animationstyle']); ?>"
                                data-config-field="<?php echo sprintf('%s.animationstyle', esc_attr($args['name'])); ?>">
                            <option value="none"<?php if ($style['animationstyle'] == 'none') {
                                echo " selected";
                            } ?>><?php echo esc_html(__('None', '3cx-clicktotalk')); ?></option>
                            <option value="slideup"<?php if ($style['animationstyle'] == 'slideup') {
                                echo " selected";
                            } ?>><?php echo esc_html(__('Slide up', '3cx-clicktotalk')); ?></option>
                            <option value="slideleft"<?php if ($style['animationstyle'] == 'slideleft') {
                                echo " selected";
                            } ?>><?php echo esc_html(__('Slide left', '3cx-clicktotalk')); ?></option>
                            <option value="fadein"<?php if ($style['animationstyle'] == 'fadein') {
                                echo " selected";
                            } ?>><?php echo esc_html(__('Fade in', '3cx-clicktotalk')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-minimizedstyle"><?php echo esc_html(__('Minimized style ', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <select id="<?php echo $id; ?>-minimizedstyle" name="<?php echo $id; ?>[minimizedstyle]"
                                class="customSelect large-text code"
                                value="<?php echo esc_attr($style['minimizedstyle']); ?>"
                                data-config-field="<?php echo sprintf('%s.minimizedstyle', esc_attr($args['name'])); ?>">
                            <option value="bubble"<?php if ($style['minimizedstyle'] == 'bubble') {
                                echo " selected";
                            } ?>><?php echo esc_html(__('Bubble', '3cx-clicktotalk')); ?></option>
                            <option value="tab"<?php if ($style['minimizedstyle'] == 'tab') {
                                echo " selected";
                            } ?>><?php echo esc_html(__('Tab', '3cx-clicktotalk')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-minimized" name="<?php echo $id; ?>[minimized]"
                               class="code" <?php if ($style['minimized']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.minimized', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-minimized"><?php echo esc_html(__('Load minimized', '3cx-clicktotalk')); ?>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-facebookintegrationurl"><?php echo esc_html(__('Facebook account URL', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="url" oninput="checkFacebookUrl();" autocomplete="off"
                               id="<?php echo $id; ?>-facebookintegrationurl"
                               name="<?php echo $id; ?>[facebookintegrationurl]" class="inputSize code"
                               value="<?php echo esc_attr($style['facebookintegrationurl']); ?>"
                               data-config-field="<?php echo sprintf('%s.facebookintegrationurl', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-twitterintegrationurl"><?php echo esc_html(__('Twitter account URL', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="url" oninput="checkTwitterUrl();" autocomplete="off"
                               id="<?php echo $id; ?>-twitterintegrationurl"
                               name="<?php echo $id; ?>[twitterintegrationurl]" class="inputSize code"
                               value="<?php echo esc_attr($style['twitterintegrationurl']); ?>"
                               data-config-field="<?php echo sprintf('%s.twitterintegrationurl', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <label for="<?php echo $id; ?>-emailintegrationurl"><?php echo esc_html(__('Email Address', '3cx-clicktotalk')); ?>
                    </div>
                    <div class="customInput extraSpaceTop">
                        <input type="email" oninput="checkEmailAddress();" autocomplete="off"
                               id="<?php echo $id; ?>-emailintegrationurl"
                               name="<?php echo $id; ?>[emailintegrationurl]" class="inputSize code"
                               value="<?php echo esc_attr($style['emailintegrationurl']); ?>"
                               data-config-field="<?php echo sprintf('%s.windowwidth', esc_attr($args['name'])); ?>"/>
                    </div>
                </div>
                <div class="divContainer">
                    <div class="customInput">
                        <input type="checkbox" id="<?php echo $id; ?>-enablepoweredby"
                               name="<?php echo $id; ?>[enablepoweredby]"
                               class="code" <?php if ($style['enablepoweredby']) echo ' checked'; ?>
                               data-config-field="<?php echo sprintf('%s.enablepoweredby', esc_attr($args['name'])); ?>"/>
                        <label for="<?php echo $id; ?>-enablepoweredby"><?php echo esc_html(__('Show "Powered By 3CX"', '3cx-clicktotalk')); ?>
                    </div>
                </div>
            </div>
            <!--    Scripts for Validations-->
            <script type="text/javascript">

                function updatePrimaryColor() {
                    var uniquePrimaryColorID = "#<?php echo $id; ?>" + "-primarycolor";
                    var uniquePrimaryColorPickerID = "choosePrimaryColors";
                    var primary_color = document.querySelector(uniquePrimaryColorID);
                    var primary_color_picker = document.getElementById(uniquePrimaryColorPickerID);
                    primary_color.value = primary_color_picker.value;
                }

                function updatePrimaryColorPicker() {
                    var uniquePrimaryColorID = "#<?php echo $id; ?>" + "-primarycolor";
                    var uniquePrimaryColorPickerID = "choosePrimaryColors";
                    var primary_color = document.querySelector(uniquePrimaryColorID);
                    var uniquePrimaryColorID = document.getElementById(uniquePrimaryColorPickerID);
                    uniquePrimaryColorID.value = primary_color.value;
                }

                function updateSecondaryColor() {
                    var uniqueSecondaryColorID = "#<?php echo $id; ?>" + "-secondarycolor";
                    var uniqueSecondaryColorPickerID = "chooseSecondaryColors";
                    var secondary_color = document.querySelector(uniqueSecondaryColorID);
                    var secondary_color_picker = document.getElementById(uniqueSecondaryColorPickerID);
                    secondary_color.value = secondary_color_picker.value;
                }

                function updateSecondaryColorPicker() {
                    var uniqueSecondaryColorID = "#<?php echo $id; ?>" + "-secondarycolor";
                    var uniqueSecondaryColorPickerID = "chooseSecondaryColors";
                    var secondary_color = document.querySelector(uniqueSecondaryColorID);
                    var secondary_color_picker = document.getElementById(uniqueSecondaryColorPickerID);
                    secondary_color_picker.value = secondary_color.value;
                }


                function checkEmailAddress() {
                    var uniqueEmailID = "#<?php echo $id; ?>" + "-emailintegrationurl";
                    var email_input = document.querySelector(uniqueEmailID);
                    if (!email_input.value) {
                        email_input.setCustomValidity("");
                    } else if (!(/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email_input.value))) {
                        email_input.setCustomValidity("Invalid Email Address");
                    } else {
                        email_input.setCustomValidity("");
                    }
                }

                function checkFacebookUrl() {
                    var uniqueFBUrlID = "#<?php echo $id; ?>" + "-facebookintegrationurl";
                    var fb_input = document.querySelector(uniqueFBUrlID);
                    var fb_input_low = fb_input.value.toLowerCase();
                    if (!fb_input.value) {
                        fb_input.setCustomValidity("");
                    } else if (!(/^(https?:\/\/)?((w{3}\.)?)facebook.com\/.*/i.test(fb_input_low))) {
                        fb_input.setCustomValidity("Invalid Facebook URL");
                    } else {
                        fb_input.setCustomValidity("");
                    }
                }

                function checkTwitterUrl() {
                    var uniqueTwitterUrlID = "#<?php echo $id; ?>" + "-twitterintegrationurl";
                    var twitter_input = document.querySelector(uniqueTwitterUrlID);
                    var twitter_input_low = twitter_input.value.toLowerCase();
                    if (!twitter_input.value) {
                        twitter_input.setCustomValidity("");
                    } else if (twitter_input.value && !(/^(https?:\/\/)?((w{3}\.)?)twitter.com\/.*/i.test(twitter_input_low))) {
                        twitter_input.setCustomValidity("Invalid Twitter URL");
                    } else {
                        twitter_input.setCustomValidity("");
                    }
                }

                function checkIfNumber(idName) {
                    var uniqueID = "#<?php echo $id; ?>" + "-" + idName;
                    var number_input = document.querySelector(uniqueID);
                    if (!number_input.value) {
                        number_input.setCustomValidity("");
                    } else if (isNaN(number_input.value)) {
                        number_input.setCustomValidity("Invalid Number");
                    } else {
                        number_input.setCustomValidity("");
                    }
                }
            </script>
        </fieldset>
    </div>
    <?php
}

