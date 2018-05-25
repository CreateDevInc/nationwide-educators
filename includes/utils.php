<?php

if ( !function_exists( "is_register_page" ) ) { 
  function is_register_page( $url ) {
    return preg_match( '/.*register(\/$|\?|$)/', $url );
  }
}