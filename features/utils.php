<?php

//some handy features
function Scale10( $var, $base = 4, $zero = '-' )
{
    # we scale 1-4, etc to 1-10
    return ( $var > 0 )
            ? ( $var - 1 ) * 9 / ( $base - 1 ) + 1
            : $zero;
}

function filter_text($text){
    $patterns = array();
    $replacements = array();
    $patterns[] = '/_SPACE_/';
    $replacements[] = ' ';
    $patterns[] = '/_COLON_/';
    $replacements[] = ':';
    $patterns[] = '/_iuml_/'; 
    $replacements[] = '&iuml;';
    $patterns[] = '/^\d+\.\s/';  
    $replacements[] = '';
    $patterns[] = '/_euml_/'; 
    $replacements[] = '&euml;';
    $patterns[] = '/_eacute_/'; 
    $replacements[] = '&eacute;';
    $patterns[] = '/_QUOTE_/'; 
    $replacements[] = '\'';
    $patterns[] = '/&#039;/'; 
    $replacements[] = '\'';
    
    $text = preg_replace($patterns, $replacements, $text);  
    $text = html_entity_decode($text, null, 'UTF-8');  
    return $text;
}

function get_importance_categories($data){
    $datastring     = $data['get_all_question_props'];
        //konqord JSON is false becuse escape character on '
    $tevreden       = str_replace('\\\'', '',$data['question.type.satisfaction']);
    $belangrijk     = str_replace('\\\'', '',$data['question.type.importance']);
    $datastring     = str_replace('\\\'', '\'', $datastring);
    $all_questions  = json_decode($datastring);
	$tevreden_array = preg_split('/,/', $tevreden);
	$belangrijk_array = preg_split('/,/', $belangrijk);

    $categories = array();
    $importance_categories = array();
    foreach($all_questions as $question_number=>$question){
        if (in_array($question->{'question_type'}[0][1], $tevreden_array)){
            $categories[$question->{'group_id'}]['satisfaction'][] = $question_number;
        }
        if (in_array($question->{'question_type'}[0][1], $belangrijk_array)){
            $categories[$question->{'group_id'}]['importance'][] = $question_number;
        }
    };
    foreach($categories as $category_id => $category){
        if ( (count($category['satisfaction']) > 2) and (count($category['importance']) > 0) ){
            $importance_categories[] = $category_id;
        }
    }
    return $importance_categories;    
}

/**
 * Function: sanitize
 * Returns a sanitized string, typically for URLs.
 *
 * Parameters:
 *     $string - The string to sanitize.
 *     $force_lowercase - Force the string to lowercase?
 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
 */
function sanitize_filename($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

/**
 * Returns a random set of characters for filenames
 */
function randchars($num, $charset = 'abcdefghijkmnopqrstuvwxyz0123456789'){
    $charrange = strlen($charset)-1;
    $string = '';
    for($i=1; $i<=$num; $i++){
        $string .= $charset[mt_rand(0, $charrange)];
        }
    return $string;
    }


