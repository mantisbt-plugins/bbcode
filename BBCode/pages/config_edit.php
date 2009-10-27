<?php
# Copyright (C) 2009	Kirill Krasnov
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

auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

$f_process_bbcode_text	= gpc_get_int( 'process_bbcode_text', ON );
$f_process_bbcode_rss 	= gpc_get_int( 'process_bbcode_rss', ON );
$f_process_bbcode_email = gpc_get_int( 'process_bbcode_email', ON );

if( plugin_config_get( 'process_bbcode_text' ) != $f_process_bbcode_text ) {
	plugin_config_set( 'process_bbcode_text', $f_process_bbcode_text );
}
if( plugin_config_get( 'process_bbcode_rss' ) != $f_process_bbcode_rss ) {
	plugin_config_set( 'process_bbcode_rss', $f_process_bbcode_rss );
}
if( plugin_config_get( 'process_bbcode_email' ) != $f_process_bbcode_email ) {
	plugin_config_set( 'process_bbcode_email', $f_process_bbcode_email );
}

print_successful_redirect( plugin_page( 'config', true ) );