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
    <h1><u><font size="5">Results from Searching for a Nonce</font></u></h1>
    <?php 
    SetRunningTime(30);
    $HashAlg = $_POST['SHA'];
    $CheckedHashAlg = str_replace("-", "", strtolower($HashAlg));
    $Sentence = htmlspecialchars($_POST['Sentence']);
    $HashValue = hash($CheckedHashAlg, $Sentence);
    $Nonce = rand();
    $TargetValue = "00000000000007";
    $TargetValueAsInteger = Str_HashToIntegerValue($TargetValue);
    $NumZeroAccountFor = $_POST['ZerosChoice'];        
    PrintIntroHash($Sentence, $Nonce, $HashValue, $HashAlg, $NumZeroAccountFor);
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
    $CalcTime = $ExecutionEndTime - $ExecutionStartTime;    
    $NumAttempts = $Nonce - $PrevNonce;
    $NumAttemptsDisplay = number_format($NumAttempts, 0);
    if ($CalcTime != 0){
        $HashRate = $NumAttempts / $CalcTime;
    }else{
        $HashRate = 0;
    }
    $HashRate = number_format($HashRate, 3);
    $NonceDisplay = number_format($Nonce);
    $NumZeroAccountForDisplay = number_format(pow(16,$NumZeroAccountFor), 0);
    PrintoutToUser("","","<hr size='10' color='white' ></hr>");
    PrintoutToUser("red","5","<b><u>Results</u>:</b><br />");
    PrintoutToUser("red","5","<b>Hash Values for last occurence is:</b> $HashValue");
    PrintoutToUser("red","5","<b>Nonce value is:</b> $NonceDisplay");
    PrintoutToUser("red","5","<b>Total Number of attempts is:</b> $NumAttemptsDisplay ");
    PrintoutToUser("red","5","<b>The Expected number of attempts is:</b> $NumZeroAccountForDisplay");
    PrintoutToUser("red","5","<b>Start Time is:</b> $ExecutionStartTime Seconds");
    PrintoutToUser("red","5","<b>End Time is:</b> $ExecutionEndTime Seconds");
    PrintoutToUser("red","5","<b>Calculated Time Taken is:</b> $CalcTime Seconds");
    PrintoutToUser("red","5","<b>The Hash Rate is:</b> $HashRate");
    $ZeroToCheckFor;   
    function PrintIntroHash($Sentence, $Nonce, $HashValue, $HashAlg, $NumZeroAccountFor){
        $NonceDisplay = number_format($Nonce);
        PrintoutToUser("yellow","5","Your Sentence is: " .$Sentence);
        PrintoutToUser("yellow","5", "<b>Your sentence length is: </b>" . strlen($Sentence));
        PrintoutToUser("yellow","5","<b>Zeros to account for is: </b>" . $NumZeroAccountFor);
        PrintoutToUser("yellow","5","<b>Nonce value is: </b>" .$NonceDisplay);
        PrintoutToUser("yellow","5","<b>The initial hash of the sentence without Nonce is: </b>" . $HashValue);
        PrintoutToUser("yellow","5","<b>You picked </b><u>" . $HashAlg . " </u><b>for the hash algorithm</b>");
    }
    function PrintNewHash($Sentence, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger){
        $SentenceAndNonce = $_POST["Sentence"] . $Nonce;
        $HashValue = hash($CheckedHashAlg, $SentenceAndNonce);
        $NonceDisplay = number_format($Nonce);
        PrintoutToUser("white","5","<br /><b>Initial Hash Value with Nonce is:</b> " . $HashValue);
        PrintoutToUser("white","5","<b>Nonce value is:</b> " . $NonceDisplay);
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
            PrintoutToUser("red", "10", "<br>Hash Value " . $HashValue . " is less than Target Value and Integer value is " . $HashValueAsInteger);
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
    function PrintoutToUser($FontColor, $FontSize, $DataToPrintOut){
        echo "<font size='". $FontSize ."' color='". $FontColor ."'>" . $DataToPrintOut . "</font> <br />";
    } 
    ?>   
    <font size="6">
        <br><br><button onclick="goBack()">Go Back</button>
    </font>
</body>
<title>Exercise 2</title>
</html>