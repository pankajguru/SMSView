<?php

class mostimportant
{

    function render( $data, $ref, $number_of_tops = 5)
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['table.satisfaction.data'];
        $schoolname     = $data['schoolnaam'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $satisfaction_data  = json_decode($datastring)->{'importance'};
        $importance_categories = get_importance_categories($data);
        $headerStyle = array(
            'b' => 'double',
            'sz' => 10,
            'font' => 'Century Gothic',
        );
        
        $tableStyle = array(
            'font' => 'Century Gothic',
            'sz' => 10,
            'cell_color' => 'E6E6E6',         
            
        );


        $mostimportant_docx = new CreateDocx();
        
        $most_important_table = array();

        $column=215;
        $most_important_table[0][0] = ''; 
        $size_col = array(20);
        
        $peiling_top = array();
        $alle_scholen_top = array();
        usort($satisfaction_data->{'peiling'}, "cmp_reference_importance");
        usort($satisfaction_data->{'alle_scholen'}, "cmp_reference_importance");
        
        foreach($satisfaction_data->{'peiling'} as $key => $reference){
            //do not take categories wich are not ment to be categories:
            if (!in_array($reference[0], $importance_categories)){
                continue;
            }
            $peiling_top[] = $reference[1];
        }
        foreach($satisfaction_data->{'alle_scholen'} as $key => $reference){
            //do not take categories wich are not ment to be categories:
            if (!in_array($reference[0], $importance_categories)){
                continue;
            }
            $alle_scholen_top[] = $reference[1];
        }
        foreach($alle_scholen_top as $key=>$reference){
            if (!in_array($reference, $peiling_top)){
                $alle_scholen_top[$key] = '';
            }
            
        }
        $headerStyle['text'] = 'Onze school';
        $text = $mostimportant_docx->addElement('addText', array($headerStyle));
        $most_important_table[0][1] = $text; 
        $headerStyle['text'] = 'Alle scholen';
        $text = $mostimportant_docx->addElement('addText', array($headerStyle));
        $most_important_table[0][2] = $text; 
        $row=1;
        foreach($peiling_top as $top){
            if ($row > $number_of_tops){
                continue;
            }
            $tableStyle['cell_color'] = ($row&1)?'E6E6E6':'FFFFFF';
            $tableStyle['text'] = $row;
            $text = $mostimportant_docx->addElement('addText', array($tableStyle));
            $most_important_table[$row][0] = $text; 
            $tableStyle['text'] = $top;
            $text = $mostimportant_docx->addElement('addText', array($tableStyle));
            $most_important_table[$row][1] = $text ; 
            $row++;            
        }
        $row=1;
        foreach($alle_scholen_top as $top){
            if ($row > $number_of_tops){
                continue;
            }
            if ($top =='') {continue;};
            $tableStyle['cell_color'] = ($row&1)?'E6E6E6':'FFFFFF';
            $tableStyle['text'] = $top;
            $text = $mostimportant_docx->addElement('addText', array($tableStyle));
            $most_important_table[$row][2] = $text ; 
            $row++;            
        }
        $size_col = array(20, 4755, 4755);

        $paramsTable = array(
            'border' => 'none',
            'border_sz' => 20,
            'border_spacing' => 0,
            'border_color' => '000000',
            'jc' => 'left',
            'size_col' => $size_col
        );

        $mostimportant_docx->addTable($most_important_table, $paramsTable);

		$filename = $temp.'mostimportant'.randchars(12);
        $mostimportant_docx->createDocx($filename);
        unset($mostimportant_docx);
        return $filename.'.docx';
        
    }
    
    function process( $data, &$docx, $top = 5)
    {
        require_once("./features/utils.php");
        $datastring     = $data['table.satisfaction.data'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $satisfaction_data  = json_decode($datastring)->{'importance'};

/*        $headerStyle = array(
            'b' => 'double',
            'font' => 'Century Gothic',
        );
        
        $tableStyle = array(
            'font' => 'Century Gothic',
            'cell_color' => 'E6E6E6',         
            
        );

        $most_important_table = array();

        $column=215;
        $most_important_table[0][0] = ''; 
        $size_col = array(20);
        foreach($satisfaction_data as $key => $reference){
            usort($reference, "cmp_reference_importance");
            $headerStyle['text'] = $key;
            $text = $docx->addElement('addText', array($headerStyle));
            $most_important_table[0][$column] = $text; 
            $row=1;
            foreach($reference as $category){
                if ($row>$top) continue;
                $tableStyle['cell_color'] = ($row&1)?'E6E6E6':'FFFFFF';
                $tableStyle['text'] = $row;
                $text = $docx->addElement('addText', array($tableStyle));
                $most_important_table[$row][0] = $text; 
                $tableStyle['text'] = $category[1];
                $text = $docx->addElement('addText', array($tableStyle));
                $most_important_table[$row][$column] = $text ; 
                $row++;
            }
            $size_col[] = (9725-215)/count($satisfaction_data);
            $column++;
        }

        $paramsTable = array(
            'border' => 'none',
            'border_sz' => 20,
            'border_spacing' => 0,
            'border_color' => '000000',
            'jc' => 'left',
            'size_col' => $size_col
        );

//        $mostimportant_table = $docx->addTable($most_important_table, $paramsTable);
                $mostimportant_table = $docx->addElement('addTable', $most_important_table);
//            print $mostimportant_table->__toString();
var_dump($mostimportant_table->toXMLString());

        $docx -> addTemplateVariable('class:mostimportance', $mostimportant_table->__toString());
//        $docx -> addTemplateVariable('class:mostimportance', $mostimportant_table);
*/

        foreach($satisfaction_data as $key => $reference){
            usort($reference, "cmp_reference_importance");
            $row = 0;
            foreach($reference as $category){
                $mostimportant_data[$row][$key] = $category[1];
                $row++;
            }
        }
        if (isset($mostimportant_data)){
            for ($i = 0; $i <= 9; $i++) {
                if (!isset($mostimportant_data[$i]['peiling'])){
                    continue;
                }
                    $category_peiling = strtolower($mostimportant_data[$i]['peiling']);
                    $category_alle_scholen = strtolower($mostimportant_data[$i]['alle_scholen']);
                    $difference = ($category_peiling == $category_alle_scholen) ? 'Net als' : 'In tegenstelling tot';
                    $docx -> addTemplateVariable("class:mostimportantProperties:category:$i:peiling", strval($category_peiling));
                    $docx -> addTemplateVariable("class:mostimportantProperties:category:$i:allescholen", strval($category_peiling));
                    $docx -> addTemplateVariable("class:mostimportantProperties:difference:$i", strval($difference));
            }
        }

        return $docx;
        
    }

}

function cmp_reference_importance($a, $b)
{
    if ($a[2] == $b[2]) {
        return 0;
    }
    return ($a[2] > $b[2]) ? -1 : 1;
}