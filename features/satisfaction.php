<?php

class satisfaction
{

    function render( &$data, $type='satisfaction')
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
            'font' => 'Century Gothic'
        );        
        
        $paramsTextTable = array(
            'color' => 'FFA100',
            'font' => 'Century Gothic'
        );        
        
        $paramsTextTableReference = array(
            'color' => '336EFF',
            'font' => 'Century Gothic'
        );        
        
        //konqord JSON is false becuse escape character on '
        $datastring     = str_replace('\\\'', '\'', $datastring);
        $satisfaction_data  = json_decode($datastring)->{$type};
        
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
        
        usort($satisfaction_table, 'cmp_peiling');
        
        
        
        //set right number
        $count = 1;
        for ($i = 0 ; $i < count($satisfaction_table) ; $i++){
            $satisfaction_table[$i][0] = $count++.'.';
            //now do the formatting of the number (should be done AFTER sort)
            for ($j=0 ; $j < $column_count ; $j++){
                $satisfaction_table[$i][$j+2]->{'_embeddedText'}[0]["text"] = sprintf("%01.1f", $satisfaction_table[$i][$j+2]->{'_embeddedText'}[0]["text"]);
            }
        }
        
        $paramsTable = array(
            'border' => 'none',
            'border_sz' => 20
        );


        $paramsText = array(
            'b' => 'single',
            'font' => 'Arial'
        );        
        //$satisfaction_docx->addText($type, $paramsText);
        $satisfaction_docx->addTable($satisfaction_header, $paramsTable);
        $satisfaction_docx->addTable($satisfaction_table, $paramsTable);
        //$satisfaction_docx->addText('test', $paramsText);

        

        $satisfaction_docx->createDocx($temp.$type);
        unset($satisfaction_docx);
        return $temp.$type.'.docx';
        
    }
    

}
        function cmp_peiling($a, $b)
        {
            if ($a[2]->{'_embeddedText'}[0]["text"] == $b[2]->{'_embeddedText'}[0]["text"]) {
                return 0;
            }
            return ($a[2]->{'_embeddedText'}[0]["text"] < $b[2]->{'_embeddedText'}[0]["text"]) ? 1 : -1;
        }
