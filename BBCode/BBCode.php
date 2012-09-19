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

function string_process_bbcode( $p_string ) {
	
	$t_change_quotes = false;
	if( ini_get_bool( 'magic_quotes_sybase' ) ) {
		$t_change_quotes = true;
		ini_set( 'magic_quotes_sybase', false );
	}

	$p_string = preg_replace( '/\[b\](.+)\[\/b\]/imsU', "<strong>\$1</strong>", $p_string );
	$p_string = preg_replace( '/\[u\](.+)\[\/u\]/imsU', "<u>\$1</u>", $p_string );
	$p_string = preg_replace( '/\[del\](.+)\[\/del\]/imsU', "<s>\$1</s>", $p_string );
	$p_string = preg_replace( '/\[sub\](.+)\[\/sub\]/imsU', "<sub>\$1</sub>", $p_string );
	$p_string = preg_replace( '/\[sup\](.+)\[\/sup\]/imsU', "<sup>\$1</sup>", $p_string );
	$p_string = preg_replace( '/\[tt\](.+)\[\/tt\]/imsU', "<tt>\$1</tt>", $p_string );
	$p_string = preg_replace( '/\[img\](.+)\[\/img\]/iU', "<img src=\"\$1\" />", $p_string );
	$p_string = preg_replace( '/\[img=(.+)\](.*)\[\/img\]/iU', "<img src=\"\$1\" title=\"\$2\" />", $p_string );
	$p_string = preg_replace( '/\[url\](.+)\[\/url\]/iU', "<a href=\"\$1\">\$1</a>", $p_string );
	$p_string = preg_replace( '/\[url=(.+)\](.*)\[\/url\]/imsU', "<a href=\"\$1\" title=\"\$2\">\$2</a>", $p_string );
	$p_string = preg_replace( '/\[i\](.+)\[\/i\]/imsU', "<i>\$1</i>", $p_string );
	$p_string = preg_replace( '/\[left\](.+)\[\/left\]/imsU', "<div align=\"left\">\$1</div>", $p_string );
	$p_string = preg_replace( '/\[right\](.+)\[\/right\]/imsU', "<div align=\"right\">\$1</div>", $p_string );
	$p_string = preg_replace( '/\[center\](.+)\[\/center\]/imsU', "<center>\$1</center>", $p_string );
	$p_string = preg_replace( '/\[hr\]/iU', "<hr />", $p_string );
	$p_string = preg_replace( '/\[color=(.+)\](.+)\[\/color\]/imsU', "<span style=\"color:\$1;\">\$2</span>", $p_string );
	$p_string = preg_replace( '/\[code\](.+)\[\/code\]/imsU', "<pre><code>\$1</code></pre>", $p_string );
	
	if ( $t_change_quotes )
		ini_set( 'magic_quotes_sybase', true );
	
	//	$p_string = preg_replace( '/\b' . email_regex_simple() . '\b/i', '<a href="mailto:\0">\0</a>', $p_string );
	
	return $p_string;
}

class BBCodePlugin extends MantisFormattingPlugin {

	/**
	 *  A method that populates the plugin information and minimum requirements.
	 * 
	 * @return  void
	 */
	function register() {
		
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
	
	function install() {
		
	// helper_ensure_confirmed( plugin_lang_get( 'install_message' ), lang_get( 'plugin_install' ) );
	// config_set( 'plugin_MantisCoreFormatting_process_urls', OFF );
		
		return true;
	}
	
	/**
	 * Default plugin configuration.
	 * 
	 * @return  array default settings
	 */
	function config() {
		
		return array(
			'process_bbcode_text'  => ON,
			'process_bbcode_email' => ON,
			'process_bbcode_rss'   => ON,
		);
	}
	
	/**
	 * Plain text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @param  boolean Multiline text
	 * @return multi Array with formatted text and multiline paramater
	 */
	function text( $p_event, $p_string, $p_multiline = true ) {
		
		$t_string = $p_string;
		
		if ( ON == plugin_config_get( 'process_bbcode_text' ) )
			string_process_bbcode( $t_string );
		
		return $t_string;
	}
	
	/**
	 * RSS text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @return string Formatted text
	 */
	function rss( $p_event, $p_string ) {
		
		$t_string = $p_string;
		
		if ( ON == plugin_config_get( 'process_bbcode_rss' ) )
			$t_string = string_process_bbcode( $t_string );
		
		return $t_string;
	}

	/**
	 * Email text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @return string Formatted text
	 */
	function email( $p_event, $p_string ) {
		
		$t_string = $p_string;
		
		$t_string = string_strip_hrefs( $t_string );
		$t_string = string_process_bug_link( $t_string, false );
		$t_string = string_process_bugnote_link( $t_string, false );
		$t_string = string_process_cvs_link( $t_string, false );
		
		return $t_string;
	}
	
	/**
	 * Formatted text processing.
	 * 
	 * @param  string Event name
	 * @param  string Unformatted text
	 * @param  boolean Multiline text
	 * @return multi Array with formatted text and multiline parameter
	 */
	function formatted( $p_event, $p_string, $p_multiline = true ) {
		
		$t_string = $p_string;
		
		if ( ON == plugin_config_get( 'process_bbcode_text' ) )
			$t_string = string_process_bbcode( $t_string );

		return $t_string;
	}
	
}