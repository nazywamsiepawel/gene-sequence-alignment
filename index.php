<html>
    <head>
        <title>Gene sequence alignement tool</title>
        <script src="jquery.js"></script>

        <style>
            /*savage reset*/
            html,body,div,ul,ol,li,dl,dt,dd,h1,h2,h3,h4,h5,h6,pre,form,p,blockquote,fieldset,input { margin: 0; padding: 0; }
            h1,h2,h3,h4,h5,h6,pre,code,address,caption,cite,code,em,strong,th { font-size: 1em; font-weight: normal; font-style: normal; }
            fieldset,iframe {border: none;}
            body{font-family: 'Myriad Pro', 'Arial', sans-serif; background-color:#eeeeeef;}
            h1{font-size: 20px;}
            h2{font-size:18px;}
            .clear{clear:both;}
            .header{width:100%; height:50px; color:#dedede; background-color:black;}
            .block{width:800px; padding:10px; margin:0 auto; text-align: left;}
            .seq_in{border:2px solid #dedede; padding:4px; font-size: 16px; color:gray; width:400px;}
            .mini{color:gray; font-family: 'Arial'; font-size: 11px; display:block; padding-bottom:5px;}
            
            #go{font-size:20px; display:block; padding:10px; text-decoration: none; color:black;}
            #go:hover{text-decoration: underline;}
            .listSequences td{padding:2px;}
            
            .cell{display:block; float:left; width:15px; padding-left:5px;}
            .break{margin-bottom:3px;}
        </style>
        
        <script>
            /*
             * AJAX function responsible for linking the HTML front-end
             * with the classess containing the algorithms.
             */

           
            function getResults(seq1, seq2, scoringMatrix, gapPenalty, algorithm){
                // alert(algorithm);
                    
                $("#results").append("");
                $.post('ajax/getAlignments.php', {seq1:seq1, seq2:seq2, algorithm:algorithm, scoringMatrix:scoringMatrix, gapPenalty:gapPenalty}, function(data) {
                    $(".listSequences").html('');
                    $.each(data, function(key, val) {
                        addResult(val);
                    });
                  
                 }, "json");  
            }
            
            function addResult(seq1){
                sequence = seq1;
                $(".listSequences").append("<tr>");
                for(i=0; i<sequence.length; i++){
                    border='';
                    
                    if (i==0) border ='style="border-left:1px solid gray;"';
                    $(".listSequences").append("<span class='cell'"+border+">"+sequence[i]+"</td>");
                }
                $(".listSequences").append("<div class='clear'></div>");
  
            }
            
             function isDNA(seq){
              var dna = "ACTG";
                 
                 for(var i=0; i<seq.length; i++){
                    
                     if(dna.indexOf(seq.charAt(i))==-1) return false;
                 }
                 
                 return true;
             }
             
              function isProtein(seq){
                 var protein = "ARNDCQEGHILKMFPSTWYV";
                 
                 for(var i=0; i<seq.length; i++){
                    
                     if(protein.indexOf(seq.charAt(i))==-1) return false;
                 }
                 
                 return true;
             }
             
             function recognize(){
                    seq1 = $("#seq1").val();
                    seq2 = $("#seq2").val();
                      
                    if(seq1.length==0||seq2.length==0) 
                        alert("Please enter the sequences."); else
                    if(isDNA(seq1)&&isDNA(seq2)){
                        alert("Recognized DNA");
                        $("#nucleotides").attr("checked", "true");
                    } else
                    if(isProtein(seq1)&&isProtein(seq2)){
                        alert("Recognized protein.");
                        $("#proteins").attr("checked", "true");
                        
                    }
                
                refresh();
            
            
             }
              
              
              function refresh(){
                 if($("#proteins").attr("checked")==true){
                     $("#selectNMatrix").hide();
                     $("#selectPMatrix").show();
                     
                 }else{
                     $("#selectPMatrix").hide();
                     $("#selectNMatrix").show();
                 }
              }
            
            $(document).ready(function() {
               
                //event for clicking the button 'compare'
                $("#go").click(function(){
                    seq1 = $("#seq1").val();
                    seq2 = $("#seq2").val();
                    
                    //check if the user wants to analyze nucleotides, protein or auto;
                    var toCompare = "nucleotides";
                    
                    if($("#nucleotides").attr("checked")==true)  
                        toCompare = "nucleotides";
                    if($("#proteins").attr("checked")==true) 
                        toCompare = "proteins";
                    if($("#auto").attr("checked")==true)
                        toCompare = "auto";
                    
                    
                    var error = false;
                    //show any errors if the entered sequence is invalid
                    if((!isDNA(seq1)||!isDNA(seq2))&&toCompare=="nucleotides"){ //if the user wants to compare nucleotides and provided the wrong ones 
                        alert("The nucleotide sequence is invalid");
                        error = true; //we cannot continue...
                    } 

                    if((!isProtein(seq1)||!isProtein(seq2))&&toCompare=="proteins"){
                       alert("The protein sequence is invalid"); 
                       error = true;
                    } 


                    
                    
                    //check which algorithm user wants to use
                    
                    var algorithm = "global"; //set global as the default one
            
                    if($("#local").attr("checked")==true)  
                        algorithm = "local";
                    if($("#global").attr("checked")==true) 
                        algorithm = "global";
                    if($("#semi").attr("checked")==true)   
                        algorithm = "semi";

                    gapPenalty = $("#gap").val();
                    
                    //checking what we want to compare
                    var selected = "nucletides";
                    if($("#nucleotides").attr("checked")==true)  
                        selected = "nucleotides";
                    if($("#proteins").attr("checked")==true) 
                        selected = "proteins";
                    
                    var matrix = "";
                    //checking what scoring matrix has been selected accordingly to the selected type
                    if(selected=="nucleotides"){
                        if($("#identityN").attr("checked")==true)  
                                matrix = "identity";
                         if($("#tt").attr("checked")==true)  
                                matrix = "tt";
                         if($("#blast").attr("checked")==true) 
                                matrix = "blast";
                      
                    }else{
                           if($("#identityP").attr("checked")==true)   
                                matrix = "identity";
                           if($("#pam250").attr("checked")==true)   
                                matrix = "pam250";
                    }
   
                    
                    
                    if(error == false)
                        getResults(seq1, seq2, matrix, gapPenalty, algorithm);
                
                })
                
                $("#recognize").click(function(){
                    recognize();
                });
                
                $("#proteins").click(function(){
                    refresh();
                });
                
                $("#nucleotides").click(function(){
                    refresh();
                });
                
                
            });
        </script>    
    </head>
    
    <body>
        <div class="header">
            <div class="block">
                <h1> Gene sequence alignement tool </h1>
            </div>
        </div>
        
        <div class="block" id="sequences" style="border-bottom:1px dotted #dedede;">
            <table>
                <tr><td><h2>Sequence 1 > </h2></td> <td><input type="text" name="seq1" id="seq1" class="seq_in"></td></tr>
                <tr><td><h2>Sequence 2 > </h2></td> <td><input type="text" name="seq2" id="seq2" class="seq_in"></td></tr>
                <tr><td><h2>Comparing :  </h2></td> <td style="padding:3px;"><input type="radio" name="pronuc" id="nucleotides" checked="true"> Nucleotides &nbsp;&nbsp; <input type="radio" name="pronuc" id="proteins"> Proteins &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" id="recognize">recognize</a></td></tr>
            </table>
        </div>
        
        <div class="block" id="info" style="border-bottom:1px dotted #dedede; padding:12px;">
            <div style="width:390px; border-right:1px dotted #dedede; float:left;">
                 <h2>Select algorithm</h2>
                 
                 <table style="padding:5px;">
                     <tr><td><input type="radio" name="algorithm" id="global" checked="true"></td><td>Global alignement</td></tr>
                     <tr><td></td><td><span class="mini">Needleman-wunsch</span></td></tr>
                     
                     <tr><td><input type="radio" name="algorithm" id="local"></td><td>Local alignement</td></tr>
                     <tr><td></td><td><span class="mini">Smith - waterman algorithm</span></td></tr>
                     
                     <tr><td><input type="radio" name="algorithm" id="semi"></td><td>Semi-global alignement</td></tr>
                     <tr><td></td><td><span class="mini">Modified needleman-wunsch algorithm</span></td></tr>
                     
                 </table>
            </div>
            
             <div style="width:390px;  float:right;">
                 <h2>Scoring variables</h2>
                 <table style="padding:10px;">
                     <tr><td>gap penalty</td><td><input type="text" name="gap" id="gap" value="-1"></td></tr>
                     
                 </table>
                 <div id="selectPMatrix" style="display:none;">
                     <h2 style="margin-top:5px;">Choose scoring matrix (Proteins)</h2>
                     <table style="padding:2px;">
                      <tr><td><input type="radio" name="scoringMatrixP" id="identityP" checked="true"></td><td>Identity matrix</td></tr>
                      <tr><td><input type="radio" name="scoringMatrixP" id="pam250"></td><td>PAM 250</td></tr>   
                 </table>
                 </div>
                 
                  <div id="selectNMatrix">
                     <h2 style="margin-top:5px;">Choose scoring matrix (DNA)</h2>
                     <table style="padding:2px;">
                      <tr><td><input type="radio" name="scoringMatrixN" id="identityN" checked="true"></td><td>Identity matrix</td></tr>
                      <tr><td><input type="radio" name="scoringMatrixN" id="blast"></td><td>BLAST</td></tr>   
                      <tr><td><input type="radio" name="scoringMatrixN" id="tt"></td><td>Transition/traversion</td></tr>   
                 </table>
                 </div>
            </div>
            
            <div class="clear"></div>
        </div>
        
        <div class="block">
            <div style="width:150px; border-right:1px dotted #dedede; float:left;">
                <a href="#" id="go">compare -></a>
            </div>
            
            <div style="width:600px; background:white; border:2px solid #dedede; float:right; padding:10px;" id="results">
                <table class='listSequences'>
                </table>
                
                <div class="clear"></div>
            </div>
            
            <div class="clear"></div>
            
        </div>
    </body>
</html>