<?php

class satisfaction
{

    function render( &$data)
    {
        require_once("./pChart/class/pData.class.php");
        require_once("./pChart/class/pDraw.class.php");
        require_once("./pChart/class/pImage.class.php");
        require_once("./features/utils.php");
        $temp           = 'temp/';
        $datastring     = $data['table.satisfaction.data'];
        $column_count   = 0;
        
        $paramsTextTitles = array(
            'b' => 'single',
            'font' => 'Century Gothic',
            'jc'    => 'right'
        );        
        
        $paramsTextTable = array(
            'color' => 'FFA100',
            'font' => 'Century Gothic',
            'jc'    => 'center'
        );        
        
        $paramsTextTableReference = array(
            'color' => '336EFF',
            'font' => 'Century Gothic',
            'jc'    => 'center'
        );        
        
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $satisfaction_data  = json_decode($datastring)->{'satisfaction'};
        
        //add graphic to docx
        $satisfaction_docx = new CreateDocx();
        
        $satisfaction_table = array();
        for ($i=0 ; $i < 10 ; $i++){
            $count = 0;
            $satisfaction_table[$i][$count++] = $i; //number, will be changed after sort
            $paramsTextTitles['text'] = $satisfaction_data->{'alle_scholen'}[$i][1];
            $text = $satisfaction_docx->addElement('addText', array($paramsTextTitles));
            $satisfaction_table[$i][$count++] = $text; //title
            foreach ($satisfaction_data as $key => $satisfaction_column){
                if ($key == 'alle_scholen'){
                    $paramsTextTableReference['text'] = Scale10($satisfaction_column[$i][2], 4);
                    $text = $satisfaction_docx->addElement('addText', array($paramsTextTableReference));
                } else {
                    $paramsTextTable['text'] = Scale10($satisfaction_column[$i][2], 4);
                    $text = $satisfaction_docx->addElement('addText', array($paramsTextTable));
                }
                $satisfaction_table[$i][$count++] = $text;
            }
            
        }
        
        $satisfaction_header = array(array('',''));
        var_dump($satisfaction_data);
        
        foreach ($satisfaction_data as $key => $satisfaction_column){
                $column_count++;
                if ($key == 'alle_scholen'){
                    $paramsTextTableReference['text'] = $key;
                    $text = $satisfaction_docx->addElement('addText', array($paramsTextTableReference));
                    $satisfaction_header[0][] = $text;
                } else {
                    $paramsTextTable['text'] = $key;
                    $text = $satisfaction_docx->addElement('addText', array($paramsTextTable));
                    $satisfaction_header[0][] = $text;
                }
        }
        function cmp_peiling($a, $b)
        {
            if ($a[2]->{'_embeddedText'}[0]["text"] == $b[2]->{'_embeddedText'}[0]["text"]) {
                return 0;
            }
            return ($a[2]->{'_embeddedText'}[0]["text"] < $b[2]->{'_embeddedText'}[0]["text"]) ? 1 : -1;
        }
        
        usort($satisfaction_table, 'cmp_peiling');
        
        
        
        //set right number
        $count = 1;
        for ($i = 0 ; $i < count($satisfaction_table) ; $i++){
            $satisfaction_table[$i][0] = $count++.'.';
            //now do the formatting of the number (should be done AFTER sort)
            var_dump( $satisfaction_table[$i][2]->{'_embeddedText'}[0]["text"]);
            for ($j=0 ; $j < $column_count ; $j++){
                $satisfaction_table[$i][$j+2]->{'_embeddedText'}[0]["text"] = sprintf("%01.1f", $satisfaction_table[$i][$j+2]->{'_embeddedText'}[0]["text"]);
            }
        }
        
        $paramsTable = array(
            'border' => 'single',
            'border_sz' => 20,
            'jc'    => 'center',
            'TBLWtype' => 'center'
        );


        $paramsText = array(
            'b' => 'single',
            'font' => 'Arial'
        );        
        
        //var_dump($satisfaction_header);
        $satisfaction_docx->addTable($satisfaction_header, $paramsTable);
//        $satisfaction_docx->addText('dadas', $paramsText);
        $satisfaction_docx->addTable($satisfaction_table, $paramsTable);

//        var_dump($satisfaction_table);
        

        $satisfaction_docx->createDocx($temp.'satisfaction');
        unset($satisfaction_docx);
        return $temp.'scores.docx';
        
    }
    

}
