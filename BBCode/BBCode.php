<?php
# Copyright (C) 2009 - 2012 Kirill Krasnov
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

require_once( config_get( 'class_path' ) . 'MantisFormattingPlugin.class.php' );

class BBCodePlugin extends MantisFormattingPlugin {

	/**
	 *  A method that populates the plugin information and minimum requirements.
	 * 
	 * @return  void
	 */
	public function register() {
		
		$this->name        = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page        = 'config';
		$this->version     = '1.3.3';
		$this->requires    = array(
			'MantisCore' => '1.2.0',
		);
		$this->uses = array(
			'MantisCoreFormatting' => '1.0a',
			'Highlight'            => '0.4.7',
		);
		$this->author  = 'Kirill Krasnov';
		$this->contact = 'krasnovforum@gmail.com';
		$this->url     = 'http://www.kraeg.ru';
	}
	
	public function install() {
		
	// helper_ensure_confirmed( plugin_lang_get( 'install_message' ), lang_get( 'plugin_install' ) );
	// config_set( 'plugin_MantisCoreFormatting_process_urls', OFF );
		
		return TRUE;
	}
	
	/**
	 * Default plugin configuration.
	 * 
	 * @return  array default settings
	 */
	public function config() {
		
		return array(
			'process_bbcode_text'  => ON,
			'process_bbcode_email' => ON,
			'process_bbcode_rss'   => ON,
		);
	}
	
	/**
	 * Filter string and format with bbcode
	 * 
	 * @param   string $p_string
	 * @return  string $p_string
	 */
	public function string_process_bbcode( $p_string ) {
		
		$t_change_quotes = FALSE;
		if ( ini_get_bool( 'magic_quotes_sybase' ) ) {
			$t_change_quotes = TRUE;
			ini_set( 'magic_quotes_sybase', FALSE );
		}
	
		$p_string = preg_replace( '/\[b\](.+)\[\/b\]/imsU', "<strong>\$1</strong>", $p_string );
		$p_string = preg_replace( '/\[u\](.+)\[\/u\]/imsU', "<span style=\"text-decoration:underline;\">\$1</span>", $p_string );
		$p_string = preg_replace( '/\[i\](.+)\[\/i\]/imsU', "<em>\$1</em>", $p_string );
		$p_string = preg_replace( '/\[del\](.+)\[\/del\]/imsU', "<span style=\"text-decoration:line-through;\">\$1</span>", $p_string );
		$p_string = preg_replace( '/\[sub\](.+)\[\/sub\]/imsU', "<sub>\$1</sub>", $p_string );
		$p_string = preg_replace( '/\[sup\](.+)\[\/sup\]/imsU', "<sup>\$1</sup>", $p_string );
		$p_string = preg_replace( '/\[tt\](.+)\[\/tt\]/imsU', "<tt>\$1</tt>", $p_string );
		$p_string = preg_replace( '/\[img\](.+)\[\/img\]/iU', "<img src=\"\$1\" />", $p_string );
		$p_string = preg_replace( '/\[img=(.+)\](.*)\[\/img\]/iU', "<img src=\"\$1\" title=\"\$2\" />", $p_string );
		$p_string = preg_replace( '/\[url\](.+)\[\/url\]/iU', "<a href=\"\$1\">\$1</a>", $p_string );
		$p_string = preg_replace( '/\[url=(.+)\](.*)\[\/url\]/imsU', "<a href=\"\$1\" title=\"\$2\">\$2</a>", $p_string );
		$p_string = preg_replace( '/\[left\](.+)\[\/left\]/imsU', "<div align=\"left\">\$1</div>", $p_string );
		$p_string = preg_replace( '/\[right\](.+)\[\/right\]/imsU', "<div align=\"right\">\$1</div>", $p_string );
		$p_string = preg_replace( '/\[center\](.+)\[\/center\]/imsU', "<center>\$1</center>", $p_string );
		$p_string = preg_replace( '/\[hr\]/iU', "<hr />", $p_string );
		$p_string = preg_replace( '/\[color=(.+)\](.+)\[\/color\]/imsU', "<span style=\"color:\$1;\">\$2</span>", $p_string );
                $p_string = preg_replace_callback('/\[code=(\w+)\](.+)\[\/code\]/imsU',
                                create_function('$mixed_array',
                                                'return "<pre><code class=\"" . $mixed_array[1] . 
                                                        "\">" . 
                                                        preg_replace( "/<br \/>/",
                                                                      "",
                                                                      $mixed_array[2]) . 
                                                        "</code></pre>";'),
                                $p_string );
		$p_string = preg_replace_callback('/\[code\](.+)\[\/code\]/imsU',
                                create_function('$mixed_array',
                                                'return "<pre><code>" . 
                                                        preg_replace( "/<br \/>/",
                                                                      "",
                                                                      $mixed_array[1]) . 
                                                        "</code></pre>";'),
                                $p_string );
		if ( $t_change_quotes )
			ini_set( 'magic_quotes_sybase', TRUE );
		
		//	$p_string = preg_replace( '/\b' . email_regex_simple() . '\b/i', '<a href="mailto:\0">\0</a>', $p_string );
		
		return $p_string;
	}
	
	/**
	 * Plain text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @param  boolean Multiline text
	 * @return multi Array with formatted text and multiline paramater
	 */
	public function text( $p_event, $p_string, $p_multiline = TRUE ) {
		
		if ( ON == plugin_config_get( 'process_bbcode_text' ) )
			$this->string_process_bbcode( $p_string );
		
		return $p_string;
	}
	
	/**
	 * RSS text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @return string Formatted text
	 */
	public function rss( $p_event, $p_string ) {
		
		if ( ON == plugin_config_get( 'process_bbcode_rss' ) )
			$p_string = $this->string_process_bbcode( $p_string );
		
		return $p_string;
	}

	/**
	 * Email text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @return string Formatted text
	 */
	public function email( $p_event, $p_string ) {
		
		$p_string = string_strip_hrefs( $p_string );
		$p_string = string_process_bug_link( $p_string, FALSE );
		$p_string = string_process_bugnote_link( $p_string, FALSE );
		$p_string = string_process_cvs_link( $p_string, FALSE );
		
		return $p_string;
	}
	
	/**
	 * Formatted text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @param  boolean Multiline text
	 * @return multi Array with formatted text and multiline parameter
	 */
	public function formatted( $p_event, $p_string, $p_multiline = TRUE ) {
		
		if ( ON == plugin_config_get( 'process_bbcode_text' ) )
			$p_string = $this->string_process_bbcode( $p_string );
		
		return $p_string;
	}
	
} // end class
