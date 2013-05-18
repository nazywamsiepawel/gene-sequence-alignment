<?

    class nucleotideScoring{
        
        var $attr   = array('A', 'C', 'T', 'G');
       
        var $blastMatrix = array(
            array("5", "-4", "-4", "-4"),
            array( "-4",  "5", "-4", "-4"),
            array("-4", "-4",  "5", "-4"),
            array("-4", "-4", "-4",  "5")
         );
        
        
        var $ttMatrix = array(
            array("1", "-5", "-5", "-1"),
            array("-5",  "1", "-1", "-5"),
            array("-5", "-1",  "1", "-5"),
            array("-1", "-5", "-5",  "1")
           
         );
        
        function getBlastMatch($attr1, $attr2){

            //find the position
            $pos1=0; $pos2 = 0;
            for($i=0; $i<sizeOf($this->attr); $i++){
                if($this->attr[$i] == $attr1) $pos1 = $i;
                if($this->attr[$i] == $attr2) $pos2 = $i; 
            }
            
            return $this->blastMatrix[$pos1][$pos2];
        }
        
         function getTTMatch($attr1, $attr2){

            //find the position
            $pos1=0; $pos2 = 0;
            for($i=0; $i<sizeOf($this->attr); $i++){
                if($this->attr[$i] == $attr1) $pos1 = $i;
                if($this->attr[$i] == $attr2) $pos2 = $i; 
            }
            
            return $this->ttMatrix[$pos1][$pos2];
        }
        
            
            /*
            );
    */
       
             
       function printPam250(){
           print("<pre>");
           print_r($this->pam250);
       }
    }
    

?>