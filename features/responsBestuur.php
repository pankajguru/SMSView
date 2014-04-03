<?php

class responsBestuur
{

    function render( $data, $ref, $number_of_tops = 5)
    {
        require_once("./features/utils.php");
        $temp           = 'temp/';
        if (!isset($data['response.bestuur'])) return 0;
        $datastring     = $data['response.bestuur'];
        $schoolname     = $data['schoolnaam'];
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
       
        $response_bestuur = json_decode($datastring);
        $response_array =  $response_bestuur->{'response'};
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


        $response_bestuur_docx = new CreateDocx();
        $response_bestuur_table = array();
        
        $headerStyle['text'] = 'School';
        $text = $response_bestuur_docx->addElement('addText', array($headerStyle));
        $response_bestuur_table[0][1] = $text; 
        $headerStyle['text'] = 'Respons';
        $text = $response_bestuur_docx->addElement('addText', array($headerStyle));
        $response_bestuur_table[0][2] = $text; 
        $rowcount=1;
        foreach($response_array as $key => $row){
            $tableStyle['cell_color'] = ($rowcount&1)?'E6E6E6':'FFFFFF';
            $tableStyle['text'] = $row[0];
            $text = $response_bestuur_docx->addElement('addText', array($tableStyle));
            $response_bestuur_table[$rowcount][0] = $text ; 
            $tableStyle['text'] = $row[1].'%';
            $text = $response_bestuur_docx->addElement('addText', array($tableStyle));
            $response_bestuur_table[$rowcount][1] = $text ; 
            $rowcount++;            
        }
        $size_col = array(4755, 4755);

        $paramsTable = array(
            'border' => 'none',
            'border_sz' => 20,
            'border_spacing' => 0,
            'border_color' => '000000',
            'jc' => 'left',
            'size_col' => $size_col
        );

        $response_bestuur_docx->addTable($response_bestuur_table, $paramsTable);

		$filename = $temp.'response_bestuur'.randchars(12);
        $response_bestuur_docx->createDocx($filename);
        unset($response_bestuur_docx);
        return $filename.'.docx';
        
    }
    

}
