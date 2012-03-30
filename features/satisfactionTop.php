<?php

class satisfactionTop
{

    function render( &$data, $top = 5, $satisfaction = TRUE)
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['table.satisfaction.data'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $satisfaction_data  = json_decode($datastring)->{'importance'};

        $headerStyle = array(
            'b' => 'double',
            'font' => 'Century Gothic',
        );
        
        $tableStyle = array(
            'font' => 'Century Gothic',
            'cell_color' => 'E6E6E6',         
            
        );


        $mostimportant_docx = new CreateDocx();
        
        $most_important_table = array();

        $column=215;
        $most_important_table[0][0] = ''; 
        $size_col = array(20);
        foreach($satisfaction_data as $key => $reference){
            usort($reference, "cmp_reference_importance");
            $headerStyle['text'] = $key;
            $text = $mostimportant_docx->addElement('addText', array($headerStyle));
            $most_important_table[0][$column] = $text; 
            $row=1;
            foreach($reference as $category){
                if ($row>$top) continue;
                $tableStyle['cell_color'] = ($row&1)?'E6E6E6':'FFFFFF';
                $tableStyle['text'] = $row;
                $text = $mostimportant_docx->addElement('addText', array($tableStyle));
                $most_important_table[$row][0] = $text; 
                $tableStyle['text'] = $category[1];
                $text = $mostimportant_docx->addElement('addText', array($tableStyle));
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

        $mostimportant_docx->addTable($most_important_table, $paramsTable);

        $mostimportant_docx->createDocx($temp.'mostimportant');
        unset($mostimportant_docx);
        return $temp.'mostimportant.docx';
        
    }
    

}
