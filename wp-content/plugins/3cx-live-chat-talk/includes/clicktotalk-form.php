<?php

class WP3CXC2C_ClickToTalkForm
{

    const post_type = 'wp3cxc2c_c2c_form';

    private static $found_items = 0;
    private static $current = null;

    private $id;
    private $name;
    private $title;
    private $locale;
    private $properties = array();
    private $unit_tag;
    private $responses_count = 0;
    private $scanned_form_tags;
    private $shortcode_atts = array();

    public static function count()
    {
        return self::$found_items;
    }

    public static function get_current()
    {
        return self::$current;
    }

    public static function register_post_type()
    {
        register_post_type(self::post_type, array(
            'labels' => array(
                'name' => __('Live Chat & Talk items', '3cx-clicktotalk'),
                'singular_name' => __('Live Chat & Talk item', '3cx-clicktotalk'),
            ),
            'rewrite' => false,
            'query_var' => false,
        ));
    }

    public static function find($args = '')
    {
        $defaults = array(
            'post_status' => 'any',
            'posts_per_page' => -1,
            'offset' => 0,
            'orderby' => 'ID',
            'order' => 'ASC',
        );

        $args = wp_parse_args($args, $defaults);

        $args['post_type'] = self::post_type;

        $q = new WP_Query();
        $posts = $q->query($args);

        self::$found_items = $q->found_posts;

        $objs = array();

        foreach ((array)$posts as $post) {
            $objs[] = new self($post);
        }

        return $objs;
    }

    public static function get_template($args = '')
    {
        global $l10n;

        $defaults = array('locale' => null, 'title' => '');
        $args = wp_parse_args($args, $defaults);

        $locale = $args['locale'];
        $title = $args['title'];

        if ($locale) {
            $mo_orig = $l10n['3cx-clicktotalk'];
            wp3cxc2c_load_textdomain($locale);
        }

        self::$current = $clicktotalk_form = new self;
        $clicktotalk_form->title =
            ($title ? $title : __('Untitled', '3cx-clicktotalk'));
        $clicktotalk_form->locale = ($locale ? $locale : get_user_locale());

        $properties = $clicktotalk_form->get_properties();

        $clicktotalk_form->properties = $properties;

        $clicktotalk_form = apply_filters('wp3cxc2c_clicktotalk_form_default_pack',
            $clicktotalk_form, $args);

        if (isset($mo_orig)) {
            $l10n['3cx-clicktotalk'] = $mo_orig;
        }

        return $clicktotalk_form;
    }

    public static function get_instance($post)
    {
        $post = get_post($post);

        if (!$post || self::post_type != get_post_type($post)) {
            return false;
        }

        return self::$current = new self($post);
    }

    private static function get_unit_tag($id = 0)
    {
        static $global_count = 0;

        $global_count += 1;

        if (in_the_loop()) {
            $unit_tag = sprintf('wp3cxc2c-f%1$d-p%2$d-o%3$d',
                absint($id), get_the_ID(), $global_count);
        } else {
            $unit_tag = sprintf('wp3cxc2c-f%1$d-o%2$d',
                absint($id), $global_count);
        }

        return $unit_tag;
    }

    private function __construct($post = null)
    {
        $post = get_post($post);

        if ($post && self::post_type == get_post_type($post)) {
            $this->id = $post->ID;
            $this->name = $post->post_name;
            $this->title = $post->post_title;
            $this->locale = get_post_meta($post->ID, '_locale', true);

            $properties = $this->get_properties();

            foreach ($properties as $key => $value) {
                if (metadata_exists('post', $post->ID, '_' . $key)) {
                    $properties[$key] = get_post_meta($post->ID, '_' . $key, true);
                } elseif (metadata_exists('post', $post->ID, $key)) {
                    $properties[$key] = get_post_meta($post->ID, $key, true);
                }
            }

            $this->properties = $properties;
            $this->upgrade();
        }

        do_action('wp3cxc2c_clicktotalk_form', $this);
    }

    public function __get($name)
    {
        $message = __('<code>%1$s</code> property of a <code>WP3CXC2C_ClickToTalkForm</code> object is <strong>no longer accessible</strong>. Use <code>%2$s</code> method instead.', '3cx-clicktotalk');

        if ('id' == $name) {
            if (WP_DEBUG) {
                trigger_error(sprintf($message, 'id', 'id()'));
            }

            return $this->id;
        } elseif ('title' == $name) {
            if (WP_DEBUG) {
                trigger_error(sprintf($message, 'title', 'title()'));
            }

            return $this->title;
        } elseif ($prop = $this->prop($name)) {
            if (WP_DEBUG) {
                trigger_error(
                    sprintf($message, $name, 'prop(\'' . $name . '\')'));
            }

            return $prop;
        }
    }

    public function initial()
    {
        return empty($this->id);
    }

    public function prop($name)
    {
        $props = $this->get_properties();
        return isset($props[$name]) ? $props[$name] : null;
    }

    public function get_properties()
    {
        $properties = (array)$this->properties;

        $properties = wp_parse_args($properties, array(
            'form' => '',
            'config' => array(),
            'style' => array()

        ));

        $properties = (array)apply_filters('wp3cxc2c_clicktotalk_form_properties',
            $properties, $this);

        return $properties;
    }

    public function set_properties($properties)
    {
        $defaults = $this->get_properties();

        $properties = wp_parse_args($properties, $defaults);
        $properties = array_intersect_key($properties, $defaults);

        $this->properties = $properties;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function title()
    {
        return $this->title;
    }

    public function set_title($title)
    {
        $title = strip_tags($title);
        $title = trim($title);

        if ('' === $title) {
            $title = __('Untitled', '3cx-clicktotalk');
        }

        $this->title = $title;
    }

    public function locale()
    {
        if (wp3cxc2c_is_valid_locale($this->locale)) {
            return $this->locale;
        } else {
            return '';
        }
    }

    public function set_locale($locale)
    {
        $locale = trim($locale);

        if (wp3cxc2c_is_valid_locale($locale)) {
            $this->locale = $locale;
        } else {
            $this->locale = 'en_US';
        }
    }

    public function shortcode_attr($name)
    {
        if (isset($this->shortcode_atts[$name])) {
            return (string)$this->shortcode_atts[$name];
        }
    }

    // Return true if this form is the same one as currently POSTed.
    public function is_posted()
    {

        if (empty($_POST['_wp3cxc2c_unit_tag'])) {
            return false;
        }

        return $this->unit_tag == $_POST['_wp3cxc2c_unit_tag'];
    }

    /* Generating item HTML */

    public function form_html($args = '')
    {
        $args = wp_parse_args($args, array(
            'html_id' => '',
            'html_name' => '',
            'html_class' => '',
            'output' => 'form',
        ));

        $this->shortcode_atts = $args;

        if ('raw_form' == $args['output']) {
            return '<pre class="wp3cxc2c-raw-form"><code>'
                . esc_html($this->prop('form')) . '</code></pre>';
        }

        $this->unit_tag = self::get_unit_tag($this->id);

        $lang_tag = str_replace('_', '-', $this->locale);

        if (preg_match('/^([a-z]+-[a-z]+)-/i', $lang_tag, $matches)) {
            $lang_tag = $matches[1];
        }

        $config = $this->properties['config'];
        $style = $this->properties['style'];
        //error_log(print_r($config,true));

        $pbxurl = esc_attr($config['pbxurl']);
        $pbxUrlFiltered = str_replace('/callus#', '/callus/#', $pbxurl);
        $p = explode('/callus/#', $pbxUrlFiltered . '/callus/#');
        $phonesystemurl = $p[0];
        $party = $p[1];
        $enablephone = ($config['aspect'] == 'both' || $config['aspect'] == 'phone') ? 'true' : 'false';
        $ignorequeueownership = "false";
        if ($config['aspect'] == 'bothignoreownership') {
            $ignorequeueownership = "true";
            $enablephone = "true";
        }

        if (wp_is_mobile()) {
            $minimized = "true";
            $popout = "false";
        } else {
            $minimized = ($style['minimized']) ? 'true' : 'false';
        }

        $enablevideo = ($config['enablevideo']) ? 'true' : 'false';
        $popout = ($config['popout']) ? 'true' : 'false';
        $allowsoundnotifications = ($config['allowsoundnotifications']) ? 'true' : 'false';
        $enableonmobile = ($config['enableonmobile']) ? 'true' : 'false';
        $showoperatoractualname = ($config['showoperatoractualname']) ? 'true' : 'false';
        $showtypingindicator = ($config['showtypingindicator']) ? 'true' : 'false';
        $autofocus = ($config['autofocus']) ? 'true' : 'false';
        $enablepoweredby = ($style['enablepoweredby']) ? 'true' : 'false';

        $animationstyle = ($style['animationstyle']);


        $tag = 'call-us';
        if ($config['aspect'] == 'phone') {
            $tag = 'call-us-phone';
        }

        $position = "right:8px;";
        if (isset($style['windowposition']) && $style['windowposition'] == 'right') {
            $position = "right:8px;";
        } elseif (isset($style['windowposition']) && $style['windowposition'] == 'left') {
            $position = "left:8px;";
        }

        $windowWidth = '250';
        if (isset($style['windowwidth']) && !empty($style['windowwidth'])) {
            $windowWidth = $style['windowwidth'];
        }

        $windowHeight = '470';
        if (isset($style['windowheight']) && !empty($style['windowheight'])) {
            $windowHeight = $style['windowheight'];
        }

        $bottom = "bottom:8px;";
        if ($style['minimizedstyle'] === 'tab') {
            $bottom = "bottom:0px;";
        }

        //This is where the plugin is being rendered
        $html = '<style>#callus-' . $this->id . '{--call-us-form-header-background:' . esc_attr($style['primarycolor']) . '; --call-us-header-text-color:' . esc_attr($style['secondarycolor']) . '; --call-us-form-width:' . $windowWidth . 'px; ; --call-us-form-height:' . $windowHeight . 'px;
		}</style>
		
		<' . $tag . ' id="callus-' . $this->id . '" style="position: fixed; ' . $bottom . ' z-index:9999;' . $position . '"
         phonesystem-url="' . $phonesystemurl . '"
         party="' . $party . '"';
        if ($tag == 'call-us') {
            $html .= 'invite-message="' . esc_attr($config['welcomemessage']) . '"
		         allow-call="' . $enablephone . '"
		         operator-name="' . esc_attr($config['welcomemessagesender']) . '"
				 popout="' . $popout . '"
				 animation-style="' . $style['animationstyle'] . '"
				 enable-poweredby="' . $enablepoweredby . '"
				 facebook-integration-url="' . esc_attr($style['facebookintegrationurl']) . '"
				 twitter-integration-url="' . esc_attr($style['twitterintegrationurl']) . '"
				 email-integration-url="' . $style['emailintegrationurl'] . '"
				 allow-soundnotifications="' . $allowsoundnotifications . '"
				 ignore-queueownership="' . $ignorequeueownership . '"
				 enable-onmobile="' . $enableonmobile . '"
				 show-operator-actual-name="' . $showoperatoractualname . '"
				 show-typing-indicator="' . $showtypingindicator . '"
				 auto-focus="' . $autofocus .'"
                 minimized-style="' . $style['minimizedstyle'] . '"
				 allow-video="' . $enablevideo . '"
				 minimized="' . $minimized . '"
				 window-title="' . esc_attr($config['chatboxtitle']) . '"
				 authentication-message="' . esc_attr($config['authenticationmessage']) . '"
				 unavailable-message="' . esc_attr($config['unavailablemessage']) . '"
				 window-icon="' . esc_attr($config['chatboxwindowicon']) . '"
				 operator-icon="' . esc_attr($config['operatoricon']) . '"
                 authentication="' . $config['requireidentity'] . '"';
        } else {
            $html .= 'call-title="' . $config['phoneboxtitle'] . '"
                    enable-onmobile="' . $enableonmobile . '"
                    animation-style="' . $style['animationstyle'] . '"';
        }
        $html .= '></' . $tag . '>';


        return $html;
    }

    /* Upgrade */

    private function upgrade()
    {
        $config = $this->prop('config');
        $style = $this->prop('style');
        $this->properties['config'] = $config;
        $this->properties['style'] = $style;
    }

    /* Save */

    public function save()
    {
        $props = $this->get_properties();
        $post_content = implode("\n", wp3cxc2c_array_flatten($props));

        if ($this->initial()) {
            $post = array(
                'post_type' => self::post_type,
                'post_status' => 'publish',
                'post_title' => $this->title,
                'post_content' => trim($post_content),
            );
            $post_id = wp_insert_post($post);
        } else {
            $post = array(
                'ID' => (int)$this->id,
                'post_status' => 'publish',
                'post_title' => $this->title,
                'post_content' => trim($post_content),
            );
            $post_id = wp_update_post($post);
        }

        if ($post_id) {
            foreach ($props as $prop => $value) {
                update_post_meta($post_id, '_' . $prop,
                    wp3cxc2c_normalize_newline_deep($value));
            }

            if (wp3cxc2c_is_valid_locale($this->locale)) {
                update_post_meta($post_id, '_locale', $this->locale);
            }

            if ($this->initial()) {
                $this->id = $post_id;
                do_action('wp3cxc2c_after_create', $this);
            } else {
                do_action('wp3cxc2c_after_update', $this);
            }

            do_action('wp3cxc2c_after_save', $this);
        }

        return $post_id;
    }

    public function copy()
    {
        $new = new self;
        $new->title = $this->title . '_copy';
        $new->locale = $this->locale;
        $new->properties = $this->properties;

        return apply_filters('wp3cxc2c_copy', $new, $this);
    }

    public function delete()
    {
        if ($this->initial()) {
            return;
        }

        if (wp_delete_post($this->id, true)) {
            $this->id = 0;
            return true;
        }

        return false;
    }

    public function shortcode($args = '')
    {
        $args = wp_parse_args($args, array(
            'use_old_format' => false));

        $title = str_replace(array('"', '[', ']'), '', $this->title);

        if ($args['use_old_format']) {
            $old_unit_id = (int)get_post_meta($this->id, '_old_cf7_unit_id', true);

            if ($old_unit_id) {
                $shortcode = sprintf('[clicktotalk-form %1$d "%2$s"]', $old_unit_id, $title);
            } else {
                $shortcode = '';
            }
        } else {
            $shortcode = sprintf('[3cx-clicktotalk id="%1$d" title="%2$s"]',
                $this->id, $title);
        }

        return apply_filters('wp3cxc2c_clicktotalk_form_shortcode', $shortcode, $args, $this);
    }
}
