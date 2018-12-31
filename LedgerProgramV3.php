
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
    <form id = "myForm" action="LedgerProgramV3.php" method="post">
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
    </form>  
   
   
    <?php 
        session_start(); //KEEP FOR SESSION
        session_set_cookie_params(3600,"/"); //KEEP FOR 1 hour  
        $Amount = htmlspecialchars($_POST['Amount']);
        $Nonce = htmlspecialchars($_POST['Nonce']);
        $SenderAddr = htmlspecialchars($_POST['SenderAddr']);
        $ReceiverAddr = htmlspecialchars($_POST['ReceiverAddr']);    
        $FileName = htmlspecialchars($_POST['FileName']);      
        $html = '<div id="Block"><h1>Blockchain Data: </h1></div>';    
        echo ($html);
        echo('<br />');    
        if ($FileName != "" && $Amount != "" && $Nonce != "" && $SenderAddr != "" && $ReceiverAddr != ""){  
            $stringFileName = (string) $FileName;
            WriteToFile($stringFileName);
            $FileToOpen = $FileName.".txt";
            $lines=readfileToArray($FileToOpen); //ARRAY KEEP TRACK OF LINES
            //    echo "<font size='3' color='red'>Lines are: " . var_dump($lines) . "</font> <br />"; 
            printTransactions($lines, $stringFileName);           
        } 

    
        //USER DEFINED FUNCTION

        function PrintNewHash($Amount, $Nonce, $SenderAddr, $ReceiverAddr, $HashAlg){
            $SentenceAndNonce = $_POST["SenderAddr"] . " " . $_POST["Amount"] . " " . $_POST["ReceiverAddr"] . " " .  $_POST["Nonce"];
            $HashValue = hash($HashAlg, $SentenceAndNonce);
        $arraySentenceData[]= '$StringSentenceAndNonce';
            return $HashValue;
        }


        function WriteToFile($FileToOpen2){
                // echo "<font color='yellow'>FileName in Function is " . $FileToOpen2 . "</font><br />";
                $Amount = htmlspecialchars($_POST['Amount']);//$_POST["Sentence"];
                $Nonce = htmlspecialchars($_POST['Nonce']);
                $SenderAddr = htmlspecialchars($_POST['SenderAddr']);
                $ReceiverAddr = htmlspecialchars($_POST['ReceiverAddr']);    
                $FileName = htmlspecialchars($_POST['FileName']);
                $FileToOpen = $FileToOpen2.".txt";       
                $myfile = fopen($FileToOpen, 'a') or die("Unable to open file!");        
                $stringFileName = (string) $FileName;
                //WRITE REGULAR DATA
                $Date = date("Y/m/d");
                $Time = date("h:i:sa");
                $ArrayHashAlg = array("sha256");
                $ArrayLength = count($ArrayHashAlg);
                for($i = 0; $i < $ArrayLength; ++$i) {
                    $HashFinalValue = PrintnewHash($Amount, $Nonce, $SenderAddr, $ReceiverAddr, $ArrayHashAlg[$i]);      
                }  
                $Data = $SenderAddr . " " . $Amount . " " . $ReceiverAddr . " "  . $Nonce . " " . $HashFinalValue . " " . $Date . " @ " . $Time;           
                $Data = (string)($Data);
                fwrite($myfile, $Data."\n");
                fclose($myfile); 
                //WRITE AND OPEN FILE FOR HASHES
                $FileToOpenHash = $stringFileName."Hash.txt";       
                $myfileHash = fopen($FileToOpenHash, 'a') or die("Unable to open file!");
                $HashFinalValue = $HashFinalValue;     
                fwrite($myfileHash, $HashFinalValue."\n");
                fclose($myfileHash);                
        }

        function readfileToArray($fileName){
            $lines=array();
            $myfileRead = fopen($fileName, "r") or die("Unable to open file!");
            
            while(!feof($myfileRead)) {
                $line = fgets($myfileRead, 4096); //READ
                array_push($lines, $line); //ADD DATA TO ARRAY      
                }        
                fclose($myfileRead);
                return $lines;            
        }

        function printTransactions($ArrayName, $Name){
                $counter = 1;    
                $Block = 0;
                $HashesArray = readfileToArray($Name."Hash.txt");
                $grouped = array(); //NEED FOR GROUPING ARRAY ELEMENTS
                array_pop($HashesArray);  
                $HashAlg = "sha256";
                foreach ($ArrayName as $line){                  
                    echo "<font color='yellow'>Transaction " . $counter . "</font> " . $line."<br />";                         
                    If ($counter % 2 == 0) {              
                        //THIS IS WHERE THE ERROR IS OCCURING
                        //SYMPTOMS ARE A SPACE WAS ADDED AT THE END CAUSING INCORRECT HASH           
                        $Block = $Block + 1;
                        $ModulousVal = $counter % 3;        
                        if(isset($HashesArray[$counter -1])=="1" && isset($HashesArray[$counter -2])=="1" &&gettype($HashesArray[$counter -1])!="NULL"){
                            $HashValue = hash($HashAlg, $HashesArray[$counter -1].$HashesArray[$counter -2]);
                        }      
                        echo "<font size='3' color='red'>T2: " . isset($HashesArray[$counter -1]) . "</font> <br />";
                        echo "<font size='3' color='red'>T1: " . gettype($HashesArray[$counter -2]) . "</font> <br />";
                        if(isset($HashValue) == 0){
                            $HashValue="";
                              
                            echo "<font size='3' color='red'> Data Hashed " . $HashesArray[$counter -1].$HashesArray[$counter -2]."</font> <br /><br />";  
                        }  
                        echo "<font size='3' color='red'> End of Block " . $Block . ": $HashValue</font> <br /><br />";       
                                                
                    }            
                    $counter = $counter + 1;                     
                } 
            }

        function checkVariableNotEmpty($Var){
                return (isset($Var) && strlen($Var) != 0);
            }
    ?>     
<br><a href="index.html"><font color="red">Link Back Home</font></a>
   
</body>
<title>Ledger PROGRAM</title>
</html>