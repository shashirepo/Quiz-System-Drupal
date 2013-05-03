<?php
// $Id: chatroll.drupal.inc,v 1.1 2010/05/09 14:19:59 chatroll Exp $

/**
 * Chatroll Live Chat extension for Drupal
 *
 * This class encapsulates Chatroll rendering logic and Single Sign On 
 * integration.
 *
 * Service interaction overview:
 *
 *  1) User creates a Chatroll on http://chatroll.com and obtains Shortcode
 *     (A Shortcode is akin to an API key that identifies the account)
 *
 *  2) User installs this Chatroll Drupal module, and enters the Shortcode 
 *     into the Chatroll Block configuration
 *
 *  3) This module parses the Shortcode and renders the IFrame HTML to 
 *     display Chatroll inside a Drupal block.
 *     - Single Sign On parameters are passed to chatroll.com via 
 *       GET params in the IFrame src URL.
 *
 *  4) Since Chatroll is a hosted service, it can be accessed outside 
 *     of this Drupal site, such as on Chatroll.com or any other 
 *     website / app that embeds this specific Chatroll. 
 *     (Thus the look-and-feel of Chatroll is configured on chatroll.com
 *     not locally themed for this Drupal.)
 */
class Chatroll
{
  // Default width/height
  public $width = '450';
  public $height = '350';

  // Chatroll shortcode regex pattern
  // The Shortcode is obtained from chatroll.com, when an account is created.
  // e.g. [chatroll width="500" height="400" name="" id="" apikey=""]
  protected static $shortcodePattern = '\[chatroll\b(.*?)(?:(\/))?\]';
  protected static $shortcodeAttsPattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';

  /**
   * Render Chatroll HTML from shortcode
   *
   * Render Chatroll HTML by validating and parsing the given 
   * Chatroll Shortcode value.
   *
   * @param $shortcode
   *   Chatroll Shortcode obtained from chatroll.com.
   *   e.g. [chatroll id="" name="" apikey=""]
   *
   * @return chatroll HTML
   */
  public function renderChatrollHtmlFromShortcode($shortcode) {
    return preg_replace_callback('/' . self::$shortcodePattern . '/s', array($this, 'doShortcodeTag'), $shortcode);
  }

  /**
   * Append platform default attributes
   * 
   * Append the attributes specific for the Drupal platform.
   * This includes extracting the $user object parameters for SSO integration.
   *
   * @param $attr
   *   Chatroll attributes array
   */
  protected function appendPlatformDefaultAttr($attr) {
    $attr['platform'] = 'drupal';

    // Values set via module params
    $attr['height'] = $this->height;
    $attr['width'] = $this->width;

    // Append logged in User info for SSO integration
    global $user;
    $attr['uid'] = $user->uid;
    $attr['uname'] = $user->name;

    // Moderation permission managed in site Permissions settings
    $attr['ismod'] = user_access('moderate chatroll') ? 1 : 0;

    // User picture may be enabled in site User Settings
    // Note: This URL is passed to Chatroll for rendering on Chatroll.com
    // (e.g. Users on Chatroll.com may see this picture, outside of this site.)
    if (!empty($user->picture)) {
      // We do not use ulr() here, because picture url is not a menu path.
      // (ie. it is always an absolute, 'clean url'.)
      global $base_url;
      $attr['upic'] = $base_url . '/' . $user->picture;
    }

    return $attr;
  }

  /**
   * Render Chatroll HTML
   *
   * Render Chatroll HTML from attributes array
   *
   * @param $attr
   *   Chatroll attributes array, extracted from Shortcode
   *
   * @return rendered Chatroll HTML
   */
  protected function renderChatrollHtml($attr) {
    $defaults = array(
      'domain'        => 'chatroll.com',
      'platform'      => '',          // Reference platform
      'id'            => '',          // Chatroll ID
      'name'          => '',          // Chatroll name
      'apikey'        => '',          // API key for SSO
      'width'         => '450',       // Standard embed parameters
      'height'        => '350',
      'bgcolor'       => '',          // Appearance values (optional; overrides server-side config)
      'fgcolor'       => '',
      'textbgcolor'   => '',
      'textfgcolor'   => '',
      'border'        => '',
      'sound'         => '',
      'uid'           => '',          // SSO parameters (optional; overrides generated values)
      'uname'         => '',
      'upic'          => '',
      'ulink'         => '',
      'ismod'         => '',
    );
    // Generate default parameters, based on Drupal $user and block settings
    $defaults = $this->appendPlatformDefaultAttr($defaults);
    // Merge specified parameter values w/ defaults
    $attr = $this->shortcodeAtts($defaults, $attr);

    // Generate chatrollParam by adding relevant GET parameters
    $chatrollParam = array();
    $chatrollParam['platform'] = $attr['platform'];
    if (!empty($attr['id'])) {
      $chatrollParam['id'] = $attr['id'];
    }
    if (!empty($attr['fgcolor'])) {
      $chatrollParam['fgcolor'] = $attr['fgcolor'];
    }
    if (!empty($attr['bgcolor'])) {
      $chatrollParam['bgcolor'] = $attr['bgcolor'];
    }
    if (!empty($attr['textfgcolor'])) {
      $chatrollParam['textfgcolor'] = $attr['textfgcolor'];
    }
    if (!empty($attr['textbgcolor'])) {
      $chatrollParam['textbgcolor'] = $attr['textbgcolor'];
    }
    if (!empty($attr['sound']) || $attr['sound'] == '0') {
      $chatrollParam['sound'] = $attr['sound'];
    }
    if (!empty($attr['border']) || $attr['border'] == '0') {
      $chatrollParam['border'] = $attr['border'];
    }

    // Add SSO parameters
    // Always append uid to indicate SSO request; uid=0 indicates sign out
    $chatrollParam['uid'] = $attr['uid']; 
    if (!empty($attr['uname'])) {
      // Convert usernames to Chatroll compatible format:
      // 1) Trim whitespaces from begin/end
      // 2) Limit length to 64 
      $attr['uname'] = trim($attr['uname']);
      if (strlen($attr['uname']) > 64) {
        $attr['uname'] = substr($attr['uname'], 0, 64);
      }
      $chatrollParam['uname'] = $attr['uname'];
    }
    if (!empty($attr['ismod']) || $attr['ismod'] == '0') {
      $chatrollParam['ismod'] = $attr['ismod'];
    }
    if (!empty($attr['upic'])) {
      $chatrollParam['upic'] = $attr['upic'];
    }
    if (!empty($attr['ulink'])) {
      $chatrollParam['ulink'] = $attr['ulink'];
    }
    $chatrollParam['sig'] = md5($attr['uid'] . $attr['uname'] . $attr['ismod'] . $attr['apikey']);

    // Generate Chatroll IFrame to render Chatroll from chatroll.com
    // Note: The IFrame serves as a direct "window" to the Chatroll service.
    // Attributes such as marginheight, etc. are explicitly hardcoded to ensure 
    // it is consistent across all browsers.
    $iframeAttr = array();
    $iframeAttr['width'] = $attr['width'];
    $iframeAttr['height'] = $attr['height'];
    $iframeAttr['frameborder'] = '0';
    $iframeAttr['scrolling'] = 'no';
    $iframeAttr['marginheight'] = '0';
    $iframeAttr['marginwidth'] = '0';
    $iframeAttr['allowtransparency'] = 'true';
    $iframeAttr['src'] = url('http://' . $attr['domain'] . '/embed/chat/' . $attr['name'], array(
        'query' => $chatrollParam,
        'absolute' => TRUE,
        'external' => TRUE,
      )
    );
    $output = theme('chatroll_iframe', $iframeAttr);

    return $output;
  }


  /**
   * Parse shortcode attributes
   *
   * Parase shortcode and extract attributes into an array
   *
   * @param $text
   *   Shortcode string 
   *
   * @return An associative array of attributes
   */
  protected function shortcodeParseAtts($text) {
    $atts = array();
    $text = preg_replace('/[\x{00a0}\x{200b}]+/u', ' ', $text);
    if (preg_match_all(self::$shortcodeAttsPattern, $text, $match, PREG_SET_ORDER)) {
      foreach ($match as $m) {
        if (!empty($m[1]))
          $atts[strtolower($m[1])] = stripcslashes($m[2]);
        elseif (!empty($m[3]))
          $atts[strtolower($m[3])] = stripcslashes($m[4]);
        elseif (!empty($m[5]))
          $atts[strtolower($m[5])] = stripcslashes($m[6]);
        elseif (isset($m[7]) and strlen($m[7]))
          $atts[] = stripcslashes($m[7]);
        elseif (isset($m[8]))
          $atts[] = stripcslashes($m[8]);
      }
    }
    else {
      $atts = ltrim($text);
    }
    return $atts;
  }

  /**
   * Merge Shortcode attributes
   *
   * Merge the given sets of attributes, by taking the base
   * attributes and merging any attributes from the second set that
   * is not defined in the base set.
   *
   * @param $base
   *   Baseline attribute set
   * @param $atts
   *   Extending attributes to be merged into the Baseline
   *
   * @return The merged associtive array of attributes
   */
  protected function shortcodeAtts($base, $atts) {
    $atts = (array)$atts;
    $out = array();
    foreach($base as $name => $default) {
      if (array_key_exists($name, $atts))
        $out[$name] = $atts[$name];
      else
        $out[$name] = $default;
    }
    return $out;
  }

  /**
   * Interpret matched Shortcode
   *
   * Interpret the matched Shortcode regex output
   * and render the Chatroll HTML
   *
   * @param $m
   *   Matched regex output array
   *
   * @return rendered chatroll HTML
   */
  protected function doShortcodeTag($m) {
    $attr = $this->shortcodeParseAtts($m[1]);
    return $this->renderChatrollHtml($attr);
  }
}
