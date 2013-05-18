<?


class localAlignment{
     var $seq1 = "X";
     var $seq2 = "X";
     
     var $length1;
     var $length2;
      
    var $scoring;
    function setScoring($scoring){
        $this->scoring = $scoring;
    }
    
    function setSeq($seq1, $seq2){
         $this->seq1 = $seq1;
         $this->seq2 = $seq2;
         
      
         $this->length1 = strlen($seq1)+1;
         $this->length2 = strlen($seq2)+1;
     }
     
     function matchFunction($char1, $char2){
       
       $scoringN = new nucleotideScoring();
       $scoringA = new aminoacidScoring();
       $matchScore=0;
       if($this->scoring == "identity"){
          if($char1 == $char2)  $matchScore =1; else $matchScore=0; //identity - both for proteins and DNA         
       } else if($this->scoring == "blast"){
           $matchScore = $scoringN->getBlastMatch($char1, $char2); //blast
       } else if($this->scoring == "tt"){
           $matchScore = $scoringN->getTTMatch($char1, $char2); //transition traversion matrix
       } else if($this->scoring == "pam250"){
           $matchScore = $scoringA->getMatch($char1, $char2); //PAM250
       
       }
       return $matchScore;
   }
   
     
     function createMatrix($gapPenalty){
      
        $length1 = $this->length1;
        $length2 = $this->length2;

        $table[$length1][$length2] = array();

        $gap = $gapPenalty;

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
                      // if($this->seq1{$i-1}==$this->seq2{$j-1})  $matchScore =2; else $matchScore=0; identity matrix only, old
                       $matchScore = $this->matchFunction($this->seq1{$i-1}, $this->seq2{$j-1});
               
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
     
     function backTrack($table){
       
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
       //print("max $currentMax at ($x, $y)");
       
     
       
       
       if($x>$y){
          $differenceX = $length1 - $x;
          $fillX = substr($this->seq1, $x, $differenceX);
          $fillY = str_repeat("_", $differenceX);
       } else {
          $differenceY = $length2 - $y;
          $fillY = substr($this->seq2, $y, $differenceY);
          $fillX = str_repeat("_", $differenceY);
       }
       $AlignmentA .= $fillX;
       $AlignmentB .= $fillY;
           
       
       /* change here */
       $counter = 0;
       while ($i > 0 && $j > 0){
            $score      = $table[$i][$j]; 
            $scoreDiag  = $table[$i-1][$j-1];
            $scoreUp    = $table[$i][$j-1];
            $scoreLeft  = $table[$i-1][$j];
            $value = $table[$i][$j];
           // print("<b>Iteration $counter</b><br>i = $i | j = $j<br>");
           // print("cell ($i $j)  score : $value "."<br>");
            
            $value = $table[$i][$j];
            $matchScore=0;
           // if($this->seq1{$i-1}==$this->seq2{$j-1})  $matchScore =2; else $matchScore=0; identity matrix only, old
           // print("scoreDiag = $scoreDiag | scoreUp = $scoreUp | scoreLeft = $scoreLeft | matchScore = $matchScore<br>");
            $matchScore = $this->matchFunction($this->seq1{$i-1}, $this->seq2{$j-1});
               
            if ($score == ($scoreDiag)+($matchScore)){
              $AlignmentA = $this->seq1{$i-1}.$AlignmentA; 
              $AlignmentB = $this->seq2{$j-1}.$AlignmentB;
           //   print("$score == ($scoreDiag)+($matchScore). Going diagonal. ");
              $i--; $j--;
            }

            else if ($score == $scoreLeft + $gap){

                $AlignmentA = $this->seq1{$i-1}.$AlignmentA;
                $AlignmentB = "_".$AlignmentB;
              //  print("$score == $scoreLeft + $gap. Going left. ");
                $i--;
            } else { 
         
              $AlignmentA = "_".$AlignmentA;
              $AlignmentB = $this->seq2{$j-1}.$AlignmentB;
            //  print("$score == $scoreUp + $gap. Going up.");
              
              
              $j--;

              }
         // print("Current alignments : <br>"); 
         // print(" - A : $AlignmentA<br>");
         // print(" - B : $AlignmentB");
      if($scoreDiag==0&&$scoreUp==0&&$scoreLeft==0){
         // print("<i>break</i>");
          
          break;
      }
     // print("<br><br>");
      $counter++;
    }
    
    /*
     * Fill up the remainings
     */
    
   
    while ($i > 0){
        $addChar = $this->seq1{$i-1};
        $AlignmentA = $addChar.$AlignmentA; 
        $AlignmentB = "_$AlignmentB";
        $i--;
    } 
    while ($j > 0){
        $addChar = $this->seq2{$j-1} + $AlignmentB;
        $AlignmentA = "_$AlignmentA";
        $AlignmentB = $addChar + $AlignmentB;
        $j--;
    }
   
    /*
     * Converting the result into output array
     * which later will be converted into JSON
     * format and passed through to GUI
     */
    
    $result1 = array();
    $result2 = array();
   
    for($i=0; $i<strlen($AlignmentA); $i++){
        $result1[] = $AlignmentA{$i};
    }
    
    for($i=0; $i<strlen($AlignmentB); $i++){
        $result2[] = $AlignmentB{$i};
    }
    $result = array();
    $result[] = $result1;
    $result[] = $result2;
    
    return $result;
    
   }  
   
     
      //printing out the matrix - optional, for tests
      function printMatrix($table){
        $length1 = $this->length1;
        $length2 = $this->length2;
        print("<h1>length $length1 $length2 </h1>");
        print("<table border=1 cellpadding=5>");
        for($j=0; $j<$length2; $j++){
             print("<tr>");
             for($i =0; $i<$length1; $i++)
                print("<td>".$table[$i][$j]."</td>");
             print("</tr>");

        }

        print("</table>");
      }
      
     /*
       * Just to make things nice I've created one function
       * that grabs all the parameters and returns desired result.
       */
      
      function driver($seq1, $seq2, $scoring_table, $gapPenalty){
          $nw = new localAlignment();
          $nw->setSeq($seq1, $seq2);
           $nw->setScoring($scoring_table);
          $matrix = $nw->createMatrix($gapPenalty);
          //$nw->printMatrix($matrix);
          
          $result = $nw->backtrack($matrix, $gapPenalty);
          return $result;
      }
      
      

}
/*
$alignment = new localAlignment();
$result = $alignment->driver("DUIPAKRETADUPA", "KRETA", "default", -1);
print(json_encode($result));*/

?>