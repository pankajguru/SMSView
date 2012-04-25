<?php

//some handy features
function Scale10( $var, $base = 4, $zero = '-' )
{
    # we scale 1-4, etc to 1-10
    return ($var > 0)
            ? ( $var - 1 ) * 9 / ( $base - 1 ) + 1
            : $zero;
}

function get_importance_categories($data){
    $datastring     = $data['get_all_question_props'];
        //konqord JSON is false becuse escape character on '
    $datastring     = str_replace('\\\'', '\'', $datastring);
    $all_questions  = json_decode($datastring);

    $categories = array();
    $importance_categories = array();
    foreach($all_questions as $question_number=>$question){
        if ($question->{'question_type'}[0][1] == 'TEVREDEN'){
            $categories[$question->{'group_id'}]['satisfaction'][] = $question_number;
        }
        if ($question->{'question_type'}[0][1] == 'BELANGRIJK'){
            $categories[$question->{'group_id'}]['importance'][] = $question_number;
        }
    };
    foreach($categories as $category_id => $category){
        if ( (count($category['satisfaction']) > 2) and (count($category['satisfaction']) > 0) ){
            $importance_categories[] = $category_id;
        }
    }
    return $importance_categories;    
}
