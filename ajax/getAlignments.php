<?
    /* 
     * This file is responsible for linking
     * the classes with the front-end GUI
     * 
     * by Pawel Borkowski 2011
     */
     //error_reporting(E_ERROR);
     include "./../algorithms/scoring/nucleotideScoring.class.php";
     include "./../algorithms/scoring/aminoacidScoring.class.php";
     include "./../algorithms/globalAlignment.class.php";
     include "./../algorithms/localAlignment.class.php";
     
     $gapPenalty    = $_POST["gapPenalty"];
     $scoringMatrix = $_POST["scoringMatrix"];
     $algorithm     = $_POST["algorithm"];
     $seq1          = $_POST["seq1"];
     $seq2          = $_POST["seq2"];
     
    /* $seq1= "ABABABABABBA";
       $seq2 ="ABABABABABAB";
    
       $gapPenalty = -1;*/
     
     /*
      * The difference between the two outputs is
      * that the global alignement returns only one result
      * whereas local alignement might have a few possible 
      * options that differ by score.
      */
   
     if($algorithm == "global"){
         $global = new globalAlignment();
         $output = $global->driver($seq1, $seq2, $scoringMatrix, $gapPenalty);
         print_r(json_encode($output));        
     } 
     
     if($algorithm == "local"){
         $local = new localAlignment();
         $output = $local->driver($seq1, $seq2, $scoringMatrix, $gapPenalty);
         print_r(json_encode($output));       
     }

?>