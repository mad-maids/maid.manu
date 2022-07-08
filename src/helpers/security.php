<?php

if ( ! function_exists( 'xss_clean' ) ) {
    function xss_clean( $html ) {
        return  htmlentities(
            xss_do(
                preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $html )
            )
        );

    }
}

if ( ! function_exists( 'hexEntToLetter' ) ) {
    function hexEntToLetter( $ord ) {

        $ord = $ord[1];

        if( preg_match('/^x([0-9a-f]+)$/i', $ord, $match) ) {
            $ord = hexdec($match[1]);
        }else {
            $ord = intval($ord);
        }

        $no_bytes = 0;
        $byte = [];

        if ($ord < 128) {
            return chr($ord);
        } else if ($ord < 2048) {
            $no_bytes = 2;
        } else if ($ord < 65536) {
            $no_bytes = 3;
        } else if ($ord < 1114112) {
            $no_bytes = 4;
        } else {
            return ''; 
        }

        switch( $no_bytes ) {
            case 2: 
                $prefix = [ 31, 192 ];
                break;
            case 3: 
                $prefix = [ 15, 224 ];
                break;
            case 4: 
                $prefix = [ 7, 240 ];
        }

        for( $i = 0; $i < $no_bytes; $i++ ) $byte[ $no_bytes - $i - 1 ] = (($ord & (63 * pow(2, 6 * $i))) / pow(2, 6 * $i)) & 63 | 128;
        $byte[0] = ( $byte[0] & $prefix[0] ) | $prefix[1];
        $ret = '';
        for ($i = 0; $i < $no_bytes; $i++) $ret .= chr($byte[$i]);

        return $ret;

    }
}

if ( ! function_exists( 'hex_to_symbols' ) ) {
    function hex_to_symbols( $s ) {
        //return html_entity_decode($s, ENT_XML1, 'UTF-8');
        return preg_replace_callback('/&#([0-9a-fx]+);?/mi', 'hexEntToLetter', preg_replace('/\\\\u?{?([a-f0-9]{4,}?)}?/mi', '&#x$1;', urldecode($s)));
    }
}


if ( ! function_exists( 'xss_escape' ) ) {
    function xss_escape( $s ) {
        preg_match_all( '/data:\w+\/([a-zA-Z]*);base64,(?!_#_#_)([^)\'"]*)/mi', $s, $b64, PREG_OFFSET_CAPTURE );

        if( count( array_filter( $b64 ) ) > 0 ) {
            $xclean = xss_do(
                urldecode(
                    base64_decode( $b64[ 2 ][ 0 ][ 0 ] )
                )
            );
            return substr_replace(
                $s,
                '_#_#_'. base64_encode( $xclean ),
                $b64[ 2 ][ 0 ][ 1 ],
                strlen( $b64[ 2 ][ 0 ][ 0 ] )
            );

        }else {
            return $s;
        }
    }
}

if ( ! function_exists( 'xss_do' ) ) {
    function xss_do( $s='' ) {
        $st = xss_escape( $s );
        return preg_replace([

                '/\\\\u?{?([a-f0-9]{4,}?)}?/mi',
                '/\*\w*\*/mi',

                '/:?e[\s]*x[\s]*p[\s]*r[\s]*e[\s]*s[\s]*s[\s]*i[\s]*o[\s]*n[\s]*(:|;|,)?\w*/mi',
                '/l[\s]*i[\s]*v[\s]*e[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi',
                '/j[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi',
                '/j[\s]*a[\s]*v[\s]*a[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi',
                '/b[\s]*e[\s]*h[\s]*a[\s]*v[\s]*i[\s]*o[\s]*r[\s]*(:|;|,)?\w*/mi',
                '/v[\s]*b[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t[\s]*(:|;|,)?\w*/mi',
                '/v[\s]*b[\s]*s[\s]*(:|;|,)?\w*/mi',
                '/e[\s]*c[\s]*m[\s]*a[\s]*s[\s]*c[\s]*r[\s]*i[\s]*p[\s]*t*(:|;|,)?\w*/mi',
                '/b[\s]*i[\s]*n[\s]*d[\s]*i[\s]*n[\s]*g*(:|;|,)?\w*/mi',
                '/\+\/v(8|9|\+|\/)?/mi',

                '/&{\w*}\w*/mi',
                '/&#\d+;?/m',

                '/x0{0,5}?3c;?/mi',
                '/x0{0,5}?60;?/mi',
                '/&lt;?/mi',
                '/</m',
                '/%3c/mi',
                '/\/?>/mi',

                '/\¼\/?\w*\¾\w*/mi',
                '/\+ADw-\/?\w*\+AD4-\w*/mi',

                '/_#_#_/mi',
             
            ],

            ['&#x$1;', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],

            str_ireplace(

                ['\u0', '&colon;', '&tab;', '&newline;'],
                ['\0', ':', '', ''],

            hex_to_symbols( $st ))

        );
    }
}
