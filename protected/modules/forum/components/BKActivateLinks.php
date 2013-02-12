<?php
/**
 * This is a wrapper for WordPress 3.4 url for url to links conversion
 * Date: 04.07.12
 * Time: 15:00
 */
class BKActivateLinks
{

/*    Old Code to Delete*/

//    protected static function _link_www($matches)
//    {
//        $url = $matches[2];
//        $url = ActivateLinks::cleanURL($url);
//        if (empty($url)) {
//            return $matches[0];
//        }
//
//        return "{$matches[1]}<a href='{$url}' target='_blank'>{$url}</a>";
//    }

//    public static function cleanURL($url)
//    {
//        if ($url == '') {
//            return $url;
//        }
//
//        $url = preg_replace("|[^a-z0-9-~+_.?#=!&;,/:%@$*'()x80-xff]|i", '', $url);
//        $url = str_replace(array("%0d", "%0a"), '', $url);
//        $url = str_replace(";//", "://", $url);
//
//        /* If the URL doesn't appear to contain a scheme, we
//        * presume it needs http:// appended (unless a relative
//        * link starting with / or a php file).
//        */
//        if (
//            strpos($url, ":") === false
//            && substr($url, 0, 1) != "/"
//            && !preg_match("|^[a-z0-9-]+?.php|i", $url)
//        ) {
//            $url = "http://{$url}";
//        }
//
//        // Replace ampersans and single quotes
//        $url = preg_replace("|&([^#])(?![a-z]{2,8};)|", "&#038;$1", $url);
//        $url = str_replace("'", "&#039;", $url);
//
//        return $url;
//    }

/*    public static function perform($text)
    {
        $text = " {$text}";

        $text = preg_replace_callback(
            '#(?<=[\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#$%&~/\-=?@\[\](+]|[.,;:](?![\s<])|(?(1)\)(?![\s<])|\)))*)#is',
            array('ActivateLinks', '_link_www'),
            $text
        );

        $text = preg_replace('#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', "$1$3</a>", $text);
        $text = trim($text);

        return $text;
    }*/

/*    END of Old Code to Delete*/





    /**
     * Callback to convert URI match to HTML A element.
     *
     * This function was backported from 2.5.0 to 2.3.2. Regex callback for {@link
     * make_clickable()}.
     *
     * @since 2.3.2
     * @access private
     *
     * @param array $matches Single Regex Match.
     * @return string HTML A element with URI address.
     */
    protected static function _make_url_clickable_cb($matches) {
    	$url = $matches[2];

    	if ( ')' == $matches[3] && strpos( $url, '(' ) ) {
    		// If the trailing character is a closing parethesis, and the URL has an opening parenthesis in it, add the closing parenthesis to the URL.
    		// Then we can let the parenthesis balancer do its thing below.
    		$url .= $matches[3];
    		$suffix = '';
    	} else {
    		$suffix = $matches[3];
    	}

    	// Include parentheses in the URL only if paired
    	while ( substr_count( $url, '(' ) < substr_count( $url, ')' ) ) {
    		$suffix = strrchr( $url, ')' ) . $suffix;
    		$url = substr( $url, 0, strrpos( $url, ')' ) );
    	}

    	$url = self::esc_url($url);
    	if ( empty($url) )
    		return $matches[0];

    	return $matches[1] . "<a href=\"$url\" rel=\"nofollow\" target=\"_blank\">$url</a>" . $suffix;
    }

    /**
     * Callback to convert URL match to HTML A element.
     *
     * This function was backported from 2.5.0 to 2.3.2. Regex callback for {@link
     * make_clickable()}.
     *
     * @since 2.3.2
     * @access private
     *
     * @param array $matches Single Regex Match.
     * @return string HTML A element with URL address.
     */
    protected static function _make_web_ftp_clickable_cb($matches) {
    	$ret = '';
    	$dest = $matches[2];
    	$dest = 'http://' . $dest;
    	$dest = self::esc_url($dest);
    	if ( empty($dest) )
    		return $matches[0];

    	// removed trailing [.,;:)] from URL
    	if ( in_array( substr($dest, -1), array('.', ',', ';', ':', ')') ) === true ) {
    		$ret = substr($dest, -1);
    		$dest = substr($dest, 0, strlen($dest)-1);
    	}
    	return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\" target=\"_blank\">$dest</a>$ret";
    }

    /**
     * Callback to convert email address match to HTML A element.
     *
     * This function was backported from 2.5.0 to 2.3.2. Regex callback for {@link
     * make_clickable()}.
     *
     * @since 2.3.2
     * @access private
     *
     * @param array $matches Single Regex Match.
     * @return string HTML A element with email address.
     */
    protected static function _make_email_clickable_cb($matches) {
    	$email = $matches[2] . '@' . $matches[3];
    	return $matches[1] . "<a href=\"mailto:$email\" target=\"_blank\">$email</a>";
    }

    /**
     * Convert plaintext URI to HTML links.
     *
     * Converts URI, www and ftp, and email addresses. Finishes by fixing links
     * within links.
     *
     * @since 0.71
     *
     * @param string $text Content to convert URIs.
     * @return string Content with converted URIs.
     */
    public static function perform( $text ) {
    	$r = '';
    	$textarr = preg_split( '/(<[^<>]+>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE ); // split out HTML tags
    	foreach ( $textarr as $piece ) {
    		if ( empty( $piece ) || ( $piece[0] == '<' && ! preg_match('|^<\s*[\w]{1,20}+://|', $piece) ) ) {
    			$r .= $piece;
    			continue;
    		}

    		// Long strings might contain expensive edge cases ...
    		if ( 10000 < strlen( $piece ) ) {
    			// ... break it up
    			foreach ( self::_split_str_by_whitespace( $piece, 2100 ) as $chunk ) { // 2100: Extra room for scheme and leading and trailing paretheses
    				if ( 2101 < strlen( $chunk ) ) {
    					$r .= $chunk; // Too big, no whitespace: bail.
    				} else {
    					$r .= self::perform( $chunk );
    				}
    			}
    		} else {
    			$ret = " $piece "; // Pad with whitespace to simplify the regexes

    			$url_clickable = '~
    				([\\s(<.,;:!?])                                        # 1: Leading whitespace, or punctuation
    				(                                                      # 2: URL
    					[\\w]{1,20}+://                                # Scheme and hier-part prefix
    					(?=\S{1,2000}\s)                               # Limit to URLs less than about 2000 characters long
    					[\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]*+         # Non-punctuation URL character
    					(?:                                            # Unroll the Loop: Only allow puctuation URL character if followed by a non-punctuation URL character
    						[\'.,;:!?)]                            # Punctuation URL character
    						[\\w\\x80-\\xff#%\\~/@\\[\\]*(+=&$-]++ # Non-punctuation URL character
    					)*
    				)
    				(\)?)                                                  # 3: Trailing closing parenthesis (for parethesis balancing post processing)
    			~xS'; // The regex is a non-anchored pattern and does not have a single fixed starting character.
    			      // Tell PCRE to spend more time optimizing since, when used on a page load, it will probably be used several times.

    			$ret = preg_replace_callback( $url_clickable, array('BKActivateLinks', '_make_url_clickable_cb'), $ret );

    			$ret = preg_replace_callback( '#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]+)#is', array('BKActivateLinks', '_make_web_ftp_clickable_cb'), $ret );
    			$ret = preg_replace_callback( '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', array('BKActivateLinks', '_make_email_clickable_cb'), $ret );

    			$ret = substr( $ret, 1, -1 ); // Remove our whitespace padding.
    			$r .= $ret;
    		}
    	}

    	// Cleanup of accidental links within links
    	$r = preg_replace( '#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', "$1$3</a>", $r );
    	return $r;
    }

    /**
     * Checks and cleans a URL.
     *
     * A number of characters are removed from the URL. If the URL is for displaying
     * (the default behaviour) ampersands are also replaced. The 'clean_url' filter
     * is applied to the returned cleaned URL.
     *
     * @since 2.8.0
     * @uses wp_kses_bad_protocol() To only permit protocols in the URL set
     *		via $protocols or the common ones set in the function.
     *
     * @param string $url The URL to be cleaned.
     * @param array $protocols Optional. An array of acceptable protocols.
     *		Defaults to 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn' if not set.
     * @param string $_context Private. Use esc_url_raw() for database usage.
     * @return string The cleaned $url after the 'clean_url' filter is applied.
     */
    public static function esc_url( $url, $protocols = null, $_context = 'display' ) {
    	$original_url = $url;

    	if ( '' == $url )
    		return $url;
    	$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    	$strip = array('%0d', '%0a', '%0D', '%0A');
    	$url = self::_deep_replace($strip, $url);
    	$url = str_replace(';//', '://', $url);
    	/* If the URL doesn't appear to contain a scheme, we
    	 * presume it needs http:// appended (unless a relative
    	 * link starting with /, # or ? or a php file).
    	 */
    	if ( strpos($url, ':') === false && ! in_array( $url[0], array( '/', '#', '?' ) ) &&
    		! preg_match('/^[a-z0-9-]+?\.php/i', $url) )
    		$url = 'http://' . $url;

    	// Replace ampersands and single quotes only when displaying.
    	if ( 'display' == $_context ) {
    		$url = self::wp_kses_normalize_entities( $url );
    		$url = str_replace( '&amp;', '&#038;', $url );
    		$url = str_replace( "'", '&#039;', $url );
    	}

    	if ( ! is_array( $protocols ) )
    		$protocols = self::wp_allowed_protocols();
    	if ( self::wp_kses_bad_protocol( $url, $protocols ) != $url )
    		return '';

    	return self::apply_filters('clean_url', $url, $original_url, $_context);
    }




/**
    * Perform a deep string replace operation to ensure the values in $search are no longer present
    *
    * Repeats the replacement operation until it no longer replaces anything so as to remove "nested" values
    * e.g. $subject = '%0%0%0DDD', $search ='%0D', $result ='' rather than the '%0%0DD' that
    * str_replace would return
    *
    * @since 2.8.1
    * @access private
    *
    * @param string|array $search
    * @param string $subject
    * @return string The processed string
    */
   protected static function _deep_replace( $search, $subject ) {
   	$found = true;
   	$subject = (string) $subject;
   	while ( $found ) {
   		$found = false;
   		foreach ( (array) $search as $val ) {
   			while ( strpos( $subject, $val ) !== false ) {
   				$found = true;
   				$subject = str_replace( $val, '', $subject );
   			}
   		}
   	}

   	return $subject;
   }


    /**
    * Converts and fixes HTML entities.
    *
    * This function normalizes HTML entities. It will convert "AT&T" to the correct
    * "AT&amp;T", "&#00058;" to "&#58;", "&#XYZZY;" to "&amp;#XYZZY;" and so on.
    *
    * @since 1.0.0
    *
    * @param string $string Content to normalize entities
    * @return string Content with normalized entities
    */
   public static function wp_kses_normalize_entities($string) {
   	# Disarm all entities by converting & to &amp;

   	$string = str_replace('&', '&amp;', $string);

   	# Change back the allowed entities in our entity whitelist

   	$string = preg_replace_callback('/&amp;([A-Za-z]{2,8});/', array('BKActivateLinks', 'wp_kses_named_entities'), $string);
   	$string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', array('BKActivateLinks', 'wp_kses_normalize_entities2'), $string);
   	$string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', array('BKActivateLinks', 'wp_kses_normalize_entities3'), $string);

   	return $string;
   }

    /**
     * Callback for wp_kses_normalize_entities() regular expression.
     *
     * This function helps wp_kses_normalize_entities() to only accept 16-bit values
     * and nothing more for &#number; entities.
     *
     * @access private
     * @since 1.0.0
     *
     * @param array $matches preg_replace_callback() matches array
     * @return string Correctly encoded entity
     */
    public static function wp_kses_normalize_entities2($matches) {
    	if ( empty($matches[1]) )
    		return '';

    	$i = $matches[1];
    	if (self::valid_unicode($i)) {
    		$i = str_pad(ltrim($i,'0'), 3, '0', STR_PAD_LEFT);
    		$i = "&#$i;";
    	} else {
    		$i = "&amp;#$i;";
    	}

    	return $i;
    }

    /**
     * Callback for wp_kses_normalize_entities() for regular expression.
     *
     * This function helps wp_kses_normalize_entities() to only accept valid Unicode
     * numeric entities in hex form.
     *
     * @access private
     *
     * @param array $matches preg_replace_callback() matches array
     * @return string Correctly encoded entity
     */
    public static function wp_kses_normalize_entities3($matches) {
    	if ( empty($matches[1]) )
    		return '';

    	$hexchars = $matches[1];
    	return ( ( ! self::valid_unicode(hexdec($hexchars)) ) ? "&amp;#x$hexchars;" : '&#x'.ltrim($hexchars,'0').';' );
    }


    /**
    * Helper function to determine if a Unicode value is valid.
    *
    * @param int $i Unicode value
    * @return bool True if the value was a valid Unicode number
    */
   public static function valid_unicode($i) {
   	return ( $i == 0x9 || $i == 0xa || $i == 0xd ||
   			($i >= 0x20 && $i <= 0xd7ff) ||
   			($i >= 0xe000 && $i <= 0xfffd) ||
   			($i >= 0x10000 && $i <= 0x10ffff) );
   }

    /**
     * Breaks a string into chunks by splitting at whitespace characters.
     * The length of each returned chunk is as close to the specified length goal as possible,
     * with the caveat that each chunk includes its trailing delimiter.
     * Chunks longer than the goal are guaranteed to not have any inner whitespace.
     *
     * Joining the returned chunks with empty delimiters reconstructs the input string losslessly.
     *
     * Input string must have no null characters (or eventual transformations on output chunks must not care about null characters)
     *
     * <code>
     * _split_str_by_whitespace( "1234 67890 1234 67890a cd 1234   890 123456789 1234567890a    45678   1 3 5 7 90 ", 10 ) ==
     * array (
     *   0 => '1234 67890 ',  // 11 characters: Perfect split
     *   1 => '1234 ',        //  5 characters: '1234 67890a' was too long
     *   2 => '67890a cd ',   // 10 characters: '67890a cd 1234' was too long
     *   3 => '1234   890 ',  // 11 characters: Perfect split
     *   4 => '123456789 ',   // 10 characters: '123456789 1234567890a' was too long
     *   5 => '1234567890a ', // 12 characters: Too long, but no inner whitespace on which to split
     *   6 => '   45678   ',  // 11 characters: Perfect split
     *   7 => '1 3 5 7 9',    //  9 characters: End of $string
     * );
     * </code>
     *
     * @since 3.4.0
     * @access private
     *
     * @param string $string The string to split
     * @param    int $goal   The desired chunk length.
     * @return array Numeric array of chunks.
     */
    protected static function _split_str_by_whitespace( $string, $goal ) {
    	$chunks = array();

    	$string_nullspace = strtr( $string, "\r\n\t\v\f ", "\000\000\000\000\000\000" );

    	while ( $goal < strlen( $string_nullspace ) ) {
    		$pos = strrpos( substr( $string_nullspace, 0, $goal + 1 ), "\000" );

    		if ( false === $pos ) {
    			$pos = strpos( $string_nullspace, "\000", $goal + 1 );
    			if ( false === $pos ) {
    				break;
    			}
    		}

    		$chunks[] = substr( $string, 0, $pos + 1 );
    		$string = substr( $string, $pos + 1 );
    		$string_nullspace = substr( $string_nullspace, $pos + 1 );
    	}

    	if ( $string ) {
    		$chunks[] = $string;
    	}

    	return $chunks;
    }

    /**
     * Retrieve a list of protocols to allow in HTML attributes.
     *
     * @since 3.3.0
     * @see wp_kses()
     * @see esc_url()
     *
     * @return array Array of allowed protocols
     */
    public static function wp_allowed_protocols() {
    	static $protocols;

    	if ( empty( $protocols ) ) {
    		$protocols = array( 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn' );
    		$protocols = self::apply_filters( 'kses_allowed_protocols', $protocols );
    	}

    	return $protocols;
    }

    /**
     * Sanitize string from bad protocols.
     *
     * This function removes all non-allowed protocols from the beginning of
     * $string. It ignores whitespace and the case of the letters, and it does
     * understand HTML entities. It does its work in a while loop, so it won't be
     * fooled by a string like "javascript:javascript:alert(57)".
     *
     * @since 1.0.0
     *
     * @param string $string Content to filter bad protocols from
     * @param array $allowed_protocols Allowed protocols to keep
     * @return string Filtered content
     */
    public static function wp_kses_bad_protocol($string, $allowed_protocols) {
    	$string = self::wp_kses_no_null($string);
    	$iterations = 0;

    	do {
    		$original_string = $string;
    		$string = self::wp_kses_bad_protocol_once($string, $allowed_protocols);
    	} while ( $original_string != $string && ++$iterations < 6 );

    	if ( $original_string != $string )
    		return '';

    	return $string;
    }

    /**
     * Removes any null characters in $string.
     *
     * @since 1.0.0
     *
     * @param string $string
     * @return string
     */
    public static function wp_kses_no_null($string) {
    	$string = preg_replace('/\0+/', '', $string);
    	$string = preg_replace('/(\\\\0)+/', '', $string);

    	return $string;
    }

    /**
     * Sanitizes content from bad protocols and other characters.
     *
     * This function searches for URL protocols at the beginning of $string, while
     * handling whitespace and HTML entities.
     *
     * @since 1.0.0
     *
     * @param string $string Content to check for bad protocols
     * @param string $allowed_protocols Allowed protocols
     * @return string Sanitized content
     */
    public static function wp_kses_bad_protocol_once($string, $allowed_protocols, $count = 1 ) {
    	$string2 = preg_split( '/:|&#0*58;|&#x0*3a;/i', $string, 2 );
    	if ( isset($string2[1]) && ! preg_match('%/\?%', $string2[0]) ) {
    		$string = trim( $string2[1] );
    		$protocol = self::wp_kses_bad_protocol_once2( $string2[0], $allowed_protocols );
    		if ( 'feed:' == $protocol ) {
    			if ( $count > 2 )
    				return '';
    			$string = self::wp_kses_bad_protocol_once( $string, $allowed_protocols, ++$count );
    			if ( empty( $string ) )
    				return $string;
    		}
    		$string = $protocol . $string;
    	}

    	return $string;
    }

    /**
     * Callback for wp_kses_bad_protocol_once() regular expression.
     *
     * This function processes URL protocols, checks to see if they're in the
     * whitelist or not, and returns different data depending on the answer.
     *
     * @access private
     * @since 1.0.0
     *
     * @param string $string URI scheme to check against the whitelist
     * @param string $allowed_protocols Allowed protocols
     * @return string Sanitized content
     */
    public static function wp_kses_bad_protocol_once2( $string, $allowed_protocols ) {
    	$string2 = self::wp_kses_decode_entities($string);
    	$string2 = preg_replace('/\s/', '', $string2);
    	$string2 = self::wp_kses_no_null($string2);
    	$string2 = strtolower($string2);

    	$allowed = false;
    	foreach ( (array) $allowed_protocols as $one_protocol )
    		if ( strtolower($one_protocol) == $string2 ) {
    			$allowed = true;
    			break;
    		}

    	if ($allowed)
    		return "$string2:";
    	else
    		return '';
    }

    /**
     * Call the functions added to a filter hook.
     *
     * The callback functions attached to filter hook $tag are invoked by calling
     * this function. This function can be used to create a new filter hook by
     * simply calling this function with the name of the new hook specified using
     * the $tag parameter.
     *
     * The function allows for additional arguments to be added and passed to hooks.
     * <code>
     * function example_hook($string, $arg1, $arg2)
     * {
     *		//Do stuff
     *		return $string;
     * }
     * $value = apply_filters('example_filter', 'filter me', 'arg1', 'arg2');
     * </code>
     *
     * @package WordPress
     * @subpackage Plugin
     * @since 0.71
     * @global array $wp_filter Stores all of the filters
     * @global array $merged_filters Merges the filter hooks using this function.
     * @global array $wp_current_filter stores the list of current filters with the current one last
     *
     * @param string $tag The name of the filter hook.
     * @param mixed $value The value on which the filters hooked to <tt>$tag</tt> are applied on.
     * @param mixed $var,... Additional variables passed to the functions hooked to <tt>$tag</tt>.
     * @return mixed The filtered value after all hooked functions are applied to it.
     */
    public static function apply_filters($tag, $value) {
    	global $wp_filter, $merged_filters, $wp_current_filter;

    	$args = array();

    	// Do 'all' actions first
    	if ( isset($wp_filter['all']) ) {
    		$wp_current_filter[] = $tag;
    		$args = func_get_args();
    		self::_wp_call_all_hook($args);
    	}

    	if ( !isset($wp_filter[$tag]) ) {
    		if ( isset($wp_filter['all']) )
    			array_pop($wp_current_filter);
    		return $value;
    	}

    	if ( !isset($wp_filter['all']) )
    		$wp_current_filter[] = $tag;

    	// Sort
    	if ( !isset( $merged_filters[ $tag ] ) ) {
    		ksort($wp_filter[$tag]);
    		$merged_filters[ $tag ] = true;
    	}

    	reset( $wp_filter[ $tag ] );

    	if ( empty($args) )
    		$args = func_get_args();

    	do {
    		foreach( (array) current($wp_filter[$tag]) as $the_ )
    			if ( !is_null($the_['function']) ){
    				$args[1] = $value;
    				$value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
    			}

    	} while ( next($wp_filter[$tag]) !== false );

    	array_pop( $wp_current_filter );

    	return $value;
    }

    /**
     * Convert all entities to their character counterparts.
     *
     * This function decodes numeric HTML entities (&#65; and &#x41;). It doesn't do
     * anything with other entities like &auml;, but we don't need them in the URL
     * protocol whitelisting system anyway.
     *
     * @since 1.0.0
     *
     * @param string $string Content to change entities
     * @return string Content after decoded entities
     */
    public static function wp_kses_decode_entities($string) {
    	$string = preg_replace_callback('/&#([0-9]+);/', array('BKActivateLinks', '_wp_kses_decode_entities_chr'), $string);
    	$string = preg_replace_callback('/&#[Xx]([0-9A-Fa-f]+);/', array('BKActivateLinks', '_wp_kses_decode_entities_chr_hexdec'), $string);

    	return $string;
    }

    /**
     * Regex callback for wp_kses_decode_entities()
     *
     * @param array $match preg match
     * @return string
     */
    protected static function _wp_kses_decode_entities_chr( $match ) {
    	return chr( $match[1] );
    }

    /**
     * Regex callback for wp_kses_decode_entities()
     *
     * @param array $match preg match
     * @return string
     */
    protected static function _wp_kses_decode_entities_chr_hexdec( $match ) {
    	return chr( hexdec( $match[1] ) );
    }

    /**
     * Calls the 'all' hook, which will process the functions hooked into it.
     *
     * The 'all' hook passes all of the arguments or parameters that were used for
     * the hook, which this function was called for.
     *
     * This function is used internally for apply_filters(), do_action(), and
     * do_action_ref_array() and is not meant to be used from outside those
     * functions. This function does not check for the existence of the all hook, so
     * it will fail unless the all hook exists prior to this function call.
     *
     * @package WordPress
     * @subpackage Plugin
     * @since 2.5
     * @access private
     *
     * @uses $wp_filter Used to process all of the functions in the 'all' hook
     *
     * @param array $args The collected parameters from the hook that was called.
     * @param string $hook Optional. The hook name that was used to call the 'all' hook.
     */
    protected static function _wp_call_all_hook($args) {
    	global $wp_filter;

    	reset( $wp_filter['all'] );
    	do {
    		foreach( (array) current($wp_filter['all']) as $the_ )
    			if ( !is_null($the_['function']) )
    				call_user_func_array($the_['function'], $args);

    	} while ( next($wp_filter['all']) !== false );
    }

    protected static $allowedentitynames = array(
        'nbsp',    'iexcl',  'cent',    'pound',  'curren', 'yen',
        'brvbar',  'sect',   'uml',     'copy',   'ordf',   'laquo',
        'not',     'shy',    'reg',     'macr',   'deg',    'plusmn',
        'acute',   'micro',  'para',    'middot', 'cedil',  'ordm',
        'raquo',   'iquest', 'Agrave',  'Aacute', 'Acirc',  'Atilde',
        'Auml',    'Aring',  'AElig',   'Ccedil', 'Egrave', 'Eacute',
        'Ecirc',   'Euml',   'Igrave',  'Iacute', 'Icirc',  'Iuml',
        'ETH',     'Ntilde', 'Ograve',  'Oacute', 'Ocirc',  'Otilde',
        'Ouml',    'times',  'Oslash',  'Ugrave', 'Uacute', 'Ucirc',
        'Uuml',    'Yacute', 'THORN',   'szlig',  'agrave', 'aacute',
        'acirc',   'atilde', 'auml',    'aring',  'aelig',  'ccedil',
        'egrave',  'eacute', 'ecirc',   'euml',   'igrave', 'iacute',
        'icirc',   'iuml',   'eth',     'ntilde', 'ograve', 'oacute',
        'ocirc',   'otilde', 'ouml',    'divide', 'oslash', 'ugrave',
        'uacute',  'ucirc',  'uuml',    'yacute', 'thorn',  'yuml',
        'quot',    'amp',    'lt',      'gt',     'apos',   'OElig',
        'oelig',   'Scaron', 'scaron',  'Yuml',   'circ',   'tilde',
        'ensp',    'emsp',   'thinsp',  'zwnj',   'zwj',    'lrm',
        'rlm',     'ndash',  'mdash',   'lsquo',  'rsquo',  'sbquo',
        'ldquo',   'rdquo',  'bdquo',   'dagger', 'Dagger', 'permil',
        'lsaquo',  'rsaquo', 'euro',    'fnof',   'Alpha',  'Beta',
        'Gamma',   'Delta',  'Epsilon', 'Zeta',   'Eta',    'Theta',
        'Iota',    'Kappa',  'Lambda',  'Mu',     'Nu',     'Xi',
        'Omicron', 'Pi',     'Rho',     'Sigma',  'Tau',    'Upsilon',
        'Phi',     'Chi',    'Psi',     'Omega',  'alpha',  'beta',
        'gamma',   'delta',  'epsilon', 'zeta',   'eta',    'theta',
        'iota',    'kappa',  'lambda',  'mu',     'nu',     'xi',
        'omicron', 'pi',     'rho',     'sigmaf', 'sigma',  'tau',
        'upsilon', 'phi',    'chi',     'psi',    'omega',  'thetasym',
        'upsih',   'piv',    'bull',    'hellip', 'prime',  'Prime',
        'oline',   'frasl',  'weierp',  'image',  'real',   'trade',
        'alefsym', 'larr',   'uarr',    'rarr',   'darr',   'harr',
        'crarr',   'lArr',   'uArr',    'rArr',   'dArr',   'hArr',
        'forall',  'part',   'exist',   'empty',  'nabla',  'isin',
        'notin',   'ni',     'prod',    'sum',    'minus',  'lowast',
        'radic',   'prop',   'infin',   'ang',    'and',    'or',
        'cap',     'cup',    'int',     'sim',    'cong',   'asymp',
        'ne',      'equiv',  'le',      'ge',     'sub',    'sup',
        'nsub',    'sube',   'supe',    'oplus',  'otimes', 'perp',
        'sdot',    'lceil',  'rceil',   'lfloor', 'rfloor', 'lang',
        'rang',    'loz',    'spades',  'clubs',  'hearts', 'diams',
    );

    /**
     * Callback for wp_kses_normalize_entities() regular expression.
     *
     * This function only accepts valid named entity references, which are finite,
     * case-sensitive, and highly scrutinized by HTML and XML validators.
     *
     * @since 3.0.0
     *
     * @param array $matches preg_replace_callback() matches array
     * @return string Correctly encoded entity
     */
    public static function wp_kses_named_entities($matches) {
    	if ( empty($matches[1]))
    		return '';

    	$i = $matches[1];
    	return ( ( ! in_array($i, self::$allowedentitynames) ) ? "&amp;$i;" : "&$i;" );
    }
}