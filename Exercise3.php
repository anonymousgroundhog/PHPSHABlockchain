<html>
<head>
<link rel="stylesheet" href="CSS/Default.css">
<script>
function goBack() {
    window.history.back()
}
</script>
</head>
<body>
    <h1><u><font size="5">Results from Mining Simulation</font></u></h1>
    <?php     
    $ArrayHashRate = array("");
    $ArrayCalcTime = array("");
    $ArrayNumberAttempts = array("");
    SetRunningTime(30); //SETS Running Time Here
    $HashAlg = $_POST['SHA'];
    $CheckedHashAlg = str_replace("-", "", strtolower($HashAlg));
    $Sentence = htmlspecialchars($_POST['Sentence']);//$_POST["Sentence"];
    $IterationsChoice = htmlspecialchars($_POST['IterationsChoice']);
    $HashValue = hash($CheckedHashAlg, $Sentence);
    $Nonce = rand();
    $TargetValue = "00000000000007";
    $TargetValueAsInteger = Str_HashToIntegerValue($TargetValue);
    $NumZeroAccountFor = $_POST['ZerosChoice'];
    function PrintoutToUser($FontColor, $FontSize, $DataToPrintOut){
        echo "<font size='". $FontSize ."' color='". $FontColor ."'>" . $DataToPrintOut . "</font> <br />";
    } 
    function PrintIntroHash($Sentence, $Nonce, $HashValue, $HashAlg, $NumZeroAccountFor){
        $NonceDisplay = number_format($Nonce);
        PrintoutToUser("yellow", "5","<b><font size='5'>Your Sentence is: </b> " .$Sentence);
        PrintoutToUser("yellow", "5","<b>Your sentence length is: </b>" . strlen($Sentence));
        PrintoutToUser("yellow", "5","<b>Zeros to account for is: </b>" . $NumZeroAccountFor);
        PrintoutToUser("yellow", "5","<b>Nonce value is: </b>" .$NonceDisplay);
        PrintoutToUser("yellow", "5","<b> The initial hash of the sentence without Nonce is: </b>" . $HashValue);
        PrintoutToUser("yellow", "5","<b>You picked </b><u>" . $HashAlg . " </u><b>for the hash algorithm</b><br />");
    }
    function PrintNewHash($Sentence, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger){
        $SentenceAndNonce = $_POST["Sentence"] . $Nonce;
        $HashValue = hash($CheckedHashAlg, $SentenceAndNonce);
        $NonceDisplay = number_format($Nonce);
      
        return $HashValue;
    }
    function Str_HashToIntegerValue($str, $range=100){
        $number = crc32($str);
        return $number % $range;
    }    
    function CheckForZero($str, $intZero){
      $strSentence = str_split($str, 1);
      $tempArray = array();      
     
      for($i=0; $i<=$intZero - 1; $i++){
          
          if ($strSentence[$i] === "0"){
              array_push($tempArray, "$strSentence[$i]");
          }
      }      
      $string=implode(",",$tempArray);
      $intZeroCount = count($tempArray);         
        if ($intZeroCount == $intZero){ 
            return true;
        }
        else{
            return false;
        }         
    }
    function CheckTargetValLessThanHash($Nonce, $CheckedHashAlg, $HashValue, $TargetValueAsInteger){
         do{
            $Nonce = $Nonce + 1;
            $HashValue = hash($CheckedHashAlg, $_POST["Sentence"].$Nonce);
            $HashValueAsInteger = Str_HashToIntegerValue($HashValue);
            echo "<br>Hash Value " . $HashValue . " is less than Target Value and Integer value is " . $HashValueAsInteger;
        }while ($TargetValueAsInteger > $HashValueAsInteger);   
    }    
    function SetRunningTime($Time){
       ini_set('max_execution_time', $Time); 
    }    
    function CheckZeroOccurenceAndRunUntilMatchesZeros($ZeroToCheckFor, $Sentence, $NumZeroAccountFor, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger){
        $ZeroToCheckFor = CheckForZero($HashValue, $NumZeroAccountFor);    
        if($ZeroToCheckFor != true){
            $HashValue = PrintNewHash($Sentence, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger);   //Get Data Here
            do{
                $Nonce = $Nonce + 1;
                $SentenceAndNonce = $_POST["Sentence"] . $Nonce;
                $HashValue = hash($CheckedHashAlg, $SentenceAndNonce);
                $ZeroToCheckFor = CheckForZero($HashValue, $NumZeroAccountFor);        
            }while($ZeroToCheckFor != true);
        }
    }   
        PrintIntroHash($Sentence, $Nonce, $HashValue, $HashAlg, $NumZeroAccountFor);
         echo ("<table border='1' width='500px'>
            <tr>
                <td style='color:red;' width='100px'><center><b>Number of Attempts</b></center></td><td style='color:red;' width='100px'><center><b>Time in Seconds</b></center></td><td style='color:red;'><center><b>Hash Rate</b></center></td>
            </tr></table>");
        for( $i = 1; $i<=$IterationsChoice; $i++ ) {
        $ExecutionStartTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $PrevNonce = $Nonce;
        $ZeroToCheckFor = CheckForZero($HashValue, $NumZeroAccountFor);        
        if($ZeroToCheckFor != true){
         $HashValue = PrintNewHash($Sentence, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger);   //Get Data Here
            do{
                $Nonce = $Nonce + 1;
                $SentenceAndNonce = $_POST["Sentence"] . $Nonce;
                $HashValue = hash($CheckedHashAlg, $SentenceAndNonce);
                $ZeroToCheckFor = CheckForZero($HashValue, $NumZeroAccountFor);           
            }while($ZeroToCheckFor != true);
        }      
        $ExecutionEndTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
         $ExecutionStartTime = round($ExecutionStartTime, 3);
        $ExecutionEndTime = round($ExecutionEndTime, 3);
        $CalcTime = round($ExecutionEndTime - $ExecutionStartTime,2);
        array_push($ArrayCalcTime, $CalcTime);
        $ArrayCalcTimeAverage = array_sum($ArrayCalcTime)/$IterationsChoice;        
        $NumAttempts = $Nonce - $PrevNonce;
        $NumAttemptsDisplay = number_format($NumAttempts, 0);       
        array_push($ArrayNumberAttempts, $NumAttempts);
        $ArrayNumberAttemptsAverage = array_sum($ArrayNumberAttempts)/$IterationsChoice;
        $ArrayNumberAttemptsAverage = round($ArrayNumberAttemptsAverage,2);
        $ArrayNumberAttemptsAverage = number_format($ArrayNumberAttemptsAverage,0);
        if($CalcTime == 0){
            $HashRate = 0;
        }else{
            $HashRate = $NumAttempts / $CalcTime;
        }        
        array_push($ArrayHashRate, $HashRate);                  
        $ArrayHashRateAverage = array_sum($ArrayHashRate)/$IterationsChoice;
        $ArrayHashRateAverage = round($ArrayHashRateAverage, 2);
        $ArrayHashRateAverage = number_format($ArrayHashRateAverage,3);             
        $HashRate = number_format($HashRate, 3);        
        $NonceDisplay = number_format($Nonce);
        $NumZeroAccountForDisplay = number_format(pow(16,$NumZeroAccountFor), 0);           
        echo ("<table border='1' width='500px'>         
            <tr>
                <td style='color:red;' width='100px'><center>$NumAttemptsDisplay</center></td><td style='color:red;' width='100px'><center>$CalcTime</center></td><td style='color:red;'><center>$HashRate</center></td>
            </tr>
        </table>");       
        $ZeroToCheckFor;
        SetRunningTime(30); 
        $HashAlg = $_POST['SHA'];
        $CheckedHashAlg = str_replace("-", "", strtolower($HashAlg));
        $Sentence = htmlspecialchars($_POST['Sentence']);
        $HashValue = hash($CheckedHashAlg, $Sentence);
        $Nonce = rand();
        $TargetValue = "00000000000007";
        $TargetValueAsInteger = Str_HashToIntegerValue($TargetValue);
        $NumZeroAccountFor = $_POST['ZerosChoice'];
    }        
    echo ("<table border='1' width='500px'>            
        <tr>
            <td style='color:red;' width='100px'><center>Average Attempts</center></td><td style='color:red;' width='100px'><center>Average Time</center></td><td style='color:red;'><center>Average HashRate</center></td>
        </tr>
        <tr>
            <td style='color:red;' width='100px'><center>$ArrayNumberAttemptsAverage</center></td><td style='color:red;' width='100px'><center>$ArrayCalcTimeAverage</center></td><td style='color:red;'><center>$ArrayHashRateAverage</center></td>
        </tr>
    </table>");
    ?>   
    <font size="6">
        <br><br><button onclick="goBack()">Go Back</button>
    </font>
</body>
<title>Exercise 3</title>
</html>