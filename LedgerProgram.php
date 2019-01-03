<html>
<head>
<link rel="stylesheet" href="CSS/Default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" type="text/javascript"></script>

</head>

<body>
    <h1><u><font size="5">Ledger Program</font></u></h1>
     <table border="1">
    <tr>
        <td>
           <p>
           Information:
            <br>This is a program designed to aid in the understanding of how you add to the blockchain ledger.
            </p>
       
       </td>
   </tr>
   </table>
   <br><br>
    <!-- FORM INPUT HERE -->
    
    <form id = "myForm" action="LedgerProgram.php" method="post">
    Blockchain Name: <textarea id="FileName" name = "FileName" rows="1" cols="10" title="Enter in a File Name Here."><?php if(isset($_POST['FileName'])) { 
         echo htmlentities ($_POST['FileName']); }?></textarea><br>
     Sender: <textarea id="SenderAddr" name = "SenderAddr" rows="1" cols="10" title="Enter in sender Name."><?php if(isset($_POST['SenderAddr'])) { 
         echo htmlentities ($_POST['SenderAddr']); }?></textarea><br>
     Amount: <textarea id="Amount" name = "Amount" rows="1" cols="3" title="Enter in a Amount." ><?php if(isset($_POST['Amount'])) { 
         echo htmlentities ($_POST['Amount']); }?></textarea><br>
     Receiver: <textarea id="ReceiverAddr" name = "ReceiverAddr" rows="1" cols="10" title="Enter in a Receiver Name." ><?php if(isset($_POST['ReceiverAddr'])) { 
         echo htmlentities ($_POST['ReceiverAddr']); }?></textarea><br>
     
    Nonce: <textarea id="Nonce" name = "Nonce" rows="1" cols="3" title="Enter in a nonce value here to calculate the hash."><?php if(isset($_POST['Nonce'])) { 
         echo htmlentities ($_POST['Nonce']); }?></textarea><br>    
    
    </select>
    
    <br>  
   
    <br><input type="submit">  
   <!--<input type="submit" name="select" value="select" onclick="select()" /> -->
    </form>  
   
   
    <?php 
    session_start(); //KEEP FOR SESSION
    session_set_cookie_params(3600,"/"); //KEEP FOR 1 hour  
    $Amount = htmlspecialchars($_POST['Amount']);//$_POST["Sentence"];
    $Nonce = htmlspecialchars($_POST['Nonce']);
    $SenderAddr = htmlspecialchars($_POST['SenderAddr']);
    $ReceiverAddr = htmlspecialchars($_POST['ReceiverAddr']);    
    $FileName = htmlspecialchars($_POST['FileName']);
    function PrintNewHash($Amount, $Nonce, $SenderAddr, $ReceiverAddr, $HashAlg){
        $SentenceAndNonce = $_POST["SenderAddr"] . " " . $_POST["Amount"] . " " . $_POST["ReceiverAddr"] . " " .  $_POST["Nonce"];
     
        $HashValue = hash($HashAlg, $SentenceAndNonce);
       
       $arraySentenceData[]= '$StringSentenceAndNonce';
        return $HashValue;
    }
    $ArrayHashAlg = array("sha256");
    $ArrayLength = count($ArrayHashAlg);
    
    $HashAlg = "sha256";
    
    for($i = 0; $i < $ArrayLength; ++$i) {
        $HashFinalValue = PrintnewHash($Amount, $Nonce, $SenderAddr, $ReceiverAddr, $ArrayHashAlg[$i]);      
    }       
    $html = '<div id="Block"><h1>Blockchain Data: </h1></div>';    
    echo ($html);
    echo('<br />');    
   if ($FileName != "" && $Amount != "" && $Nonce != "" && $SenderAddr != "" && $ReceiverAddr != ""){
       //WRITE AND OPEN FILE
       $FileToOpen = $FileName.".txt";       
       $myfile = fopen($FileToOpen, 'a') or die("Unable to open file!");
       
       //WRITE AND OPEN FILE FOR HASHES
       $FileToOpenHash = $FileName.'Hash'.".txt";       
       $myfileHash = fopen($FileToOpenHash, 'a') or die("Unable to open file!");
       //GET DATE AND TIME TO ADD TO Blockchain
       $Date = date("Y/m/d");
       $Time = date("h:i:sa");
       $Data = $SenderAddr . " " . $Amount . " " . $ReceiverAddr . " "  . $Nonce . " " . $HashFinalValue . " " . $Date . " @ " . $Time;

       fwrite($myfileHash, $HashFinalValue."\n");
       fclose($myfileHash); 

       fwrite($myfile, $Data."\n");
       fclose($myfile);
       
       $counter = 1;
       $ArrayHashesLength = 0;
       $lines=array(); //ARRAY KEEP TRACK OF LINES 
       $grouped = array(); //NEED FOR GROUPING ARRAY ELEMENTS
       
       $myfileRead = fopen($FileToOpen, "r") or die("Unable to open file!");
       
       while(!feof($myfileRead)) {
          $line = fgets($myfileRead, 4096); //READ
          array_push($lines, $line); //ADD DATA TO ARRAY      
        }

        //OPEN AND READ HASH DATA
        $myfileRead2 = fopen($FileToOpenHash, "r") or die("Unable to open file!");
       $HashesArray = array();
       while(!feof($myfileRead2)) {
          $line2 = fgets($myfileRead2, 4096); //READ
          array_push($HashesArray, $line2); //ADD DATA TO ARRAY      
        }

        $Block = 0;  
        array_pop($HashesArray);      
        foreach ($lines as $line){          
                echo "<font color='yellow'>Transaction " . $counter . "</font> " . $line . "<br />";               
                
            If ($counter % 2 == 0) {              
                 //THIS IS WHERE THE ERROR IS OCCURING
                 //SYMPTOMS ARE A SPACE WAS ADDED AT THE END CAUSING INCORRECT HASH              
                
                $T2 = (string)$HashesArray[$counter -1];
                $T1 = (string)$HashesArray[$counter - 2];                
                
                $Block = $Block + 1;
                $ModulousVal = $counter % 3;  
             
                echo "<font size='3' color='red'>Data To Hash: " . $T1 . $T2 . "</font> <br />"; 
                
                $DataToHashNow = $T1 . $T2;
                $DataToHashNow = (string)$DataToHashNow;
                echo "<font size='3' color='red'> DataToHashNow is a " . gettype($DataToHashNow) . "</font> <br />";
                echo "<font size='3' color='red'> T1 is a " . gettype($T1) . "</font> <br />";
                echo "<font size='3' color='red'> T2 is a " . gettype($T2) . "</font> <br />";
                $HashValue = hash($HashAlg, $DataToHashNow);
                echo "<font size='3' color='red'>Data To Hash: " . $DataToHashNow . "</font> <br />";
                // $encodedValue = base64_encode(hash('sha256', $T1 . $T2, TRUE)); 
                    echo "<font size='3' color='red'> End of Block " . $Block . ": $HashValue</font> <br /><br />";  
            }            
                $counter = $counter + 1;               
        }         
        fclose($myfileRead);
        fclose($myfileRead2);
   } 
    ?>     
        <br><a href="index.html"><font color="red">Link Back Home</font></a>
   
</body>
<title>Ledger PROGRAM</title>
</html>