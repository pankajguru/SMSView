<?php

class satisfactionPerCategoryBestuur
{

    function render( &$data, $ref, $type='satisfaction')
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        require_once("./features/utils.php");
        $temp           = 'temp/';
        if (!isset($data["table.satisfaction.data.bestuur"])){
            return 0;
        }
        $datastring     = $data['table.satisfaction.data.bestuur'];
        $bestuur_name   = $data['bestuur.name'];
        if (!isset($data["question.type.$type.scalefactor"])){
            return '';
        }
        $scale_factor = $data["question.type.$type.scalefactor"];
        $importance_categories = get_importance_categories($data);
        $column_count   = 0;
        
        $paramsTextTableHeader = array(
            'color' => '000000',
            'font' => 'Century Gothic',
            'border_color'=>'000000',
            'sz' => 9.5,
        );        

        $paramsTextTable = array(
            'color' => '000000',
            'font' => 'Century Gothic',
            'border_color'=>'000000',
            'sz' => 9.5,
            'jc' => 'center'
        );        

        $widthTableCols = array(
            3200,
            700,
            700
        );

        $paramsTable = array(
            'border' => 'single',
            'border_sz' => 10,
            'border_color'=>'00A4E4',
            'size_col' => $widthTableCols,
            'textWrap'=>0,
        );

        $paramsTableEmpty = array(
            'border' => 'none',
            'size_col' => 100,
        );


        
        
        
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        //this bug is already made
        $datastring     = str_replace('peiling', 'bestuur', $datastring);
        $refs = json_decode($datastring)->{'refs'};
        $satisfaction_data  = json_decode($datastring)->{$type};

        //add graphic to docx
        $satisfactionPerCategoryBestuur_docx = new CreateDocx();
        
        //create new array with categorynumber as key
        $satisfaction_array = Array();
        $category_array = Array();
        foreach ($refs as $key=>$ref){
            $satisfaction_column = $satisfaction_data->{$ref} ;
            $satisfaction_average = Array();
            foreach ($satisfaction_column as $value) {
                $satisfaction_average[$value[0]] = $value[2];
                if (($ref == 'bestuur') || ($ref == 'peiling') ){
                    $category_array[$value[0]] = $value[1];
                }
            }
            $satisfaction_array[$key] = $satisfaction_average;
        }
        $satisfaction_table = array();
        $satisfaction_table[0][0] = '';
        $category_key_count = 1;
        foreach ($category_array as $category_key => $category_name){
            //start with legend
            $paramsTextTable['text'] = $category_name;
            $text_table = $satisfactionPerCategoryBestuur_docx->addElement('addText', array($paramsTextTable));
            $satisfaction_table[0][$category_key_count] = $text_table;
            $category_key_count ++;
        }
        $paramsTextTable['text'] = 'Totaal gemiddelde';
        $text_table = $satisfactionPerCategoryBestuur_docx->addElement('addText', array($paramsTextTable));
        $satisfaction_table[0][$category_key_count] = $text_table;
        $ref_count = 1;
        foreach ($refs as $key=>$ref){
            if (($ref == 'bestuur') || ($ref == 'peiling') ){
                $paramsTextTable['text'] = $bestuur_name;
            } else {
                $paramsTextTable['text'] = filter_text($ref);
            }
            $text_table = $satisfactionPerCategoryBestuur_docx->addElement('addText', array($paramsTextTable));
            $satisfaction_table[$ref_count][0] = $text_table;
            $category_key_count = 1;
            $total_value = 0;
            $total = 0;
            foreach ($category_array as $category_key => $category_name){
                $value = ' - ';
                if (isset($satisfaction_array[$key][$category_key])){
                    $value = number_format(Scale10($satisfaction_array[$key][$category_key],$scale_factor),1,'.','');
                    $basetype = $data['basetype'];
                    if ($basetype == '3') {
                        if ($value < 6.0){
                            $paramsTextTable['cell_color'] = 'FF5050';
                        } elseif ($value < 6.5) {
                            $paramsTextTable['cell_color'] = 'FFCC66';
                        } elseif ($value < 7.5) {
                            $paramsTextTable['cell_color'] = 'CCFF99';
                        } else {
                           $paramsTextTable['cell_color'] = '99CC00';
                        }
                    } else {
                        if ($value < 6.5){
                            $paramsTextTable['cell_color'] = 'FF5050';
                        } elseif ($value < 7) {
                            $paramsTextTable['cell_color'] = 'FFCC66';
                        } elseif ($value < 8) {
                            $paramsTextTable['cell_color'] = 'CCFF99';
                        } else {
                           $paramsTextTable['cell_color'] = '99CC00';
                        }
                    }
                    $paramsTextTable['text'] = $value;
                    $total_value += $value;
                    $total++;
                } else {
                    $paramsTextTable['text'] = '-';
                }
                $text_table = $satisfactionPerCategoryBestuur_docx->addElement('addText', array($paramsTextTable));
                $satisfaction_table[$ref_count][$category_key_count] = $text_table;
                $category_key_count++;
            }
            $value = number_format(($total_value/$total),1,'.','');
            $paramsTextTable['text'] = $value;
            $text_table = $satisfactionPerCategoryBestuur_docx->addElement('addText', array($paramsTextTable));
            $satisfaction_table[$ref_count][$category_key_count] = $text_table;
            $ref_count++;
        }

        $table3 = $satisfactionPerCategoryBestuur_docx->addTable($satisfaction_table);
 
		$filename = $temp.'satisfactionPerCategoryBestuur'.randchars(12);
        $satisfactionPerCategoryBestuur_docx->createDocx($filename);
		
        unset($satisfactionPerCategoryBestuur_docx);
        return $filename.'.docx';
        
    }
    

}
