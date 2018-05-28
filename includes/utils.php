<?php

if ( !function_exists( "is_register_page" ) ) { 
  function is_register_page( $url ) {
    return preg_match( '/.*register(\/$|\?|$)/', $url );
  }
}

if ( !function_exists( "is_account_page" ) ) {
  function is_account_page( $url ) {
    return preg_match( '/.*\/account(\/$|\?|$)/', $url );
  }
}
