<?
function parseInt($string) {
//	return intval($string);
	if(preg_match('/(\d+)/', $string, $array)) {
		return $array[1];
	} else {
		return 0;
	}
}

class nw{
     var $seq1 = "X";
     var $seq2 = "X";
     
     var $length1;
     var $length2;
     
     
     
     function setSeq($seq1, $seq2){
         $this->seq1 = $seq1;
         $this->seq2 = $seq2;
         
      
         $this->length1 = strlen($seq1)+1;
         $this->length2 = strlen($seq2)+1;
     }
     
     function createMatrix(){
        $length1 = $this->length1;
        $length2 = $this->length2;

        $table[$length1][$length2] = array();

        $gap = -1;

        $table[0][0]=0;

        for($i =1; $i<$length1; $i++){
             for($j=0; $j<$length2; $j++) $table[$i][$j]="#";
        }


        for($i =1; $i<$length1; $i++) $table[$i][0] = $i*$gap;
        for($j=1; $j<$length2; $j++)  $table[0][$j] = $j*$gap;
        



        
                  for($i=1; $i<$length1; $i++){
                      for($j=1; $j<$length2; $j++){
                      $matchScore =0;
                      // print($this->seq1{$i-1}.$this->seq2{$j-1}."<br>");
                       if($this->seq1{$i-1}==$this->seq2{$j-1})  $matchScore =1;
                       $match = $table[$i-1][$j-1] + $matchScore;
                       $delete = $table[$i-1][$j] + $gap;
                       $insert = $table[$i][$j-1] + $gap;

                       $value = max($match, $delete, $insert);
                       $table[$i][$j] = $value;
                  }
                }
                
      return $table;
  
   }
   
   function needleIt($table){
       $gap = -1;
       $AlignmentA = "";
       $AlignmentB = "";
       
       $length1 = $this->length1;
       $length2 = $this->length2;

       $i = $length1-1;
       $j = $length2-1;
       
       
       while ($i > 0 && $j > 0){
            $score = parseInt($table[$i][$j]); //Score ? F(i,j)
            $scoreDiag =  parseInt($table[$i-1][$j-1]); //ScoreDiag ? F(i - 1, j - 1)
            $scoreUp = parseInt($table[$i][$j-1]);
            $scoreLeft = parseInt($table[$i-1][$j]);
            
            $value = $table[$i][$j];
            $matchScore=0;
            if($this->seq1{$i-1}==$this->seq2{$j-1})  $matchScore =1;
            print("cell ($i $j)"."<br>");
            
            
            if ($score == ($scoreDiag)+($matchScore)){
              $AlignmentA = $this->seq1{$i-1}.$AlignmentA; //? Ai + AlignmentA
              $AlignmentB = $this->seq2{$j-1}.$AlignmentB;//? Bj + AlignmentB
              $i--; $j--;
            }

            else if ($score == $scoreLeft + $gap){//(Score == ScoreLeft + d)

                $AlignmentA = $this->seq1{$i-1}.$AlignmentA;
                $AlignmentB = "_".$AlignmentB;//AlignmentB ? "-" + AlignmentB
                $i--;
            } else { //   otherwise (Score == ScoreUp + d)
         
      $AlignmentA = "_".$AlignmentA; // AlignmentA ? "-" + AlignmentA
      $AlignmentB = $this->seq2{$j-1}.$AlignmentB;// AlignmentB ? Bj + AlignmentB
      $j--;
      }
    }
    
    
    
   
    while ($i > 0){
        $AlignementA = $this->seq1{$i-1} + $AlignementA; //AlignmentA ? Ai + AlignmentA
        $AlignmentB = "_".$AlignmentB;
        $i--;//i ? i - 1
    } 
    while ($j > 0){
        $AlignmentA = "_".$AlignmentA;
        $AlignmentB = $this->seq2{$j-1} + $AlignmentB;
        $j--;
    }
    print("<br>".$AlignmentA."<br>");
    print($AlignmentB);
   }
   
   
    function printMatrix($table){
        $length1 = $this->length1;
        $length2 = $this->length2;
       
      

        print("<table border=1 cellpadding=5>");
        for($j=0; $j<$length2; $j++){
             print("<tr>");
             for($i =0; $i<$length1; $i++)
                print("<td>".$table[$i][$j]."</td>");
             print("</tr>");

        }

        print("</table>");
      }
      
   function createLocalAlignementMatrix(){
      
        $length1 = $this->length1;
        $length2 = $this->length2;

        $table[$length1][$length2] = array();

        $gap = -1;

        $table[0][0]=0;

        for($i =1; $i<$length1; $i++){
             for($j=0; $j<$length2; $j++) $table[$i][$j]="#";
        }


        
        for($i =0; $i<$length1; $i++) $table[$i][0] = 0;
        for($j=0; $j<$length2; $j++)  $table[0][$j] = 0;
        
        
                  for($i=1; $i<$length1; $i++){
                      for($j=1; $j<$length2; $j++){
                      $matchScore =0;
                      // print($this->seq1{$i-1}.$this->seq2{$j-1}."<br>");
                       if($this->seq1{$i-1}==$this->seq2{$j-1})  $matchScore =2; else $matchScore=-1;
                       $match = $table[$i-1][$j-1] + $matchScore;
                       $delete = $table[$i-1][$j] + $gap;
                       $insert = $table[$i][$j-1] + $gap;
                       
                       $value = max($match, $delete, $insert);
                       if(($match<0)&&($delete<0)&&($insert<0)) $value=0;
                       $table[$i][$j] = $value;
                  }
                }
                
      return $table;
  
   }
   
   
   
   function localAlignement($table){
       
       //initializing variables
       $length1 = $this->length1;
       $length2 = $this->length2;
       $gap = -1;
       $AlignmentA = "";
       $AlignmentB = "";
       
       //find the max in the array
       $x =0;  $y=0; $currentMax =0;
       for($i =0; $i<$length1; $i++)
         for($j=0; $j<$length2; $j++){
             if($table[$i][$j]>$currentMax){
                 $currentMax = $table[$i][$j];
                 $x = $i; $y = $j;
             }
         }
       $i = $x;
       $j = $y;
       print("max $currentMax at ($x, $y)");
       
       while ($i > 0 && $j > 0){
            $score = $table[$i][$j]; //Score ? F(i,j)
            $scoreDiag =  $table[$i-1][$j-1]; //ScoreDiag ? F(i - 1, j - 1)
            $scoreUp = $table[$i][$j-1];
            $scoreLeft = $table[$i-1][$j];
            print("cell ($i $j)"."<br>");
            
            $value = $table[$i][$j];
            $matchScore=0;
            if($this->seq1{$i-1}==$this->seq2{$j-1})  $matchScore =2;
            
            
            
            if ($score == ($scoreDiag)+($matchScore)){
              $AlignmentA = $this->seq1{$i-1}.$AlignmentA; //? Ai + AlignmentA
              $AlignmentB = $this->seq2{$j-1}.$AlignmentB;//? Bj + AlignmentB
              $i--; $j--;
            }

            else if ($score == $scoreLeft + $gap){//(Score == ScoreLeft + d)

                $AlignmentA = $this->seq1{$i-1}.$AlignmentA;
                $AlignmentB = "_".$AlignmentB;//AlignmentB ? "-" + AlignmentB
                $i--;
            } else { //   otherwise (Score == ScoreUp + d)
         
      $AlignmentA = "_".$AlignmentA; // AlignmentA ? "-" + AlignmentA
      $AlignmentB = $this->seq2{$j-1}.$AlignmentB;// AlignmentB ? Bj + AlignmentB
      $j--;
      
      }
      if($scoreDiag==0&&$scoreUp==0&&$scoreLeft==0) break;
    }
    
    
    
   
    while ($i > 0){
        $AlignementA = $this->seq1{$i-1} + $AlignementA; //AlignmentA ? Ai + AlignmentA
        $AlignmentB = "_".$AlignmentB;
        $i--;//i ? i - 1
    } 
    while ($j > 0){
        $AlignmentA = "_".$AlignmentA;
        $AlignmentB = $this->seq2{$j-1} + $AlignmentB;
        $j--;
    }
    print("<br>".$AlignmentA."<br>");
    print($AlignmentB);
    
     
       
   }
   
}
   
   $nw = new nw();
   $nw->setSeq("AACCTATAGCT", "GCGATATA");
  
   $matrix = $nw->createLocalAlignementMatrix();
   $nw->printMatrix($matrix);
   $nw->localAlignement($matrix);
   
    
?>