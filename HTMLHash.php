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
    <h1><u><font size="5">Results HASH Page</font></u></h1>
    <?php 
    //ini_set('max_execution_time', 300);
    SetRunningTime(30); //SETS Running Time Here
    $HashAlg = $_POST['SHA'];
    $CheckedHashAlg = str_replace("-", "", strtolower($HashAlg));
    $Sentence = htmlspecialchars($_POST['Sentence']);//$_POST["Sentence"];
    $HashValue = hash($CheckedHashAlg, $Sentence);
    $Nonce = rand();
    $TargetValue = "00000000000007";
    $TargetValueAsInteger = Str_HashToIntegerValue($TargetValue);
    $NumZeroAccountFor = $_POST['ZerosChoice'];
     //FUNCTION FOR HASH Value
    //MAY NOT USE BELOW CODE Str_HashToIntegerValue due to issues of not needing a integer value to check
    // Simply keep function for future use
    function PrintIntroHash($Sentence, $Nonce, $HashValue, $HashAlg, $NumZeroAccountFor){
        $NonceDisplay = number_format($Nonce);
        echo ("<h2 style='color:yellow;'><b><font size='5'>Your Sentence is </b> " .$Sentence . "</h2>");
        echo ("<h2 style='color:yellow;'><br><b>Your sentence length is: </b>" . strlen($Sentence) . "</h2>");
        echo ("<h2 style='color:yellow;'><br><b>Zeros to account for is: </b>" . $NumZeroAccountFor . "</h2>");
        echo ("<h2 style='color:yellow;'><br><b>Nonce value is: </b>" .$NonceDisplay . "</h2>");
        echo ("<h2 style='color:yellow;'> <br><b> The initial hash of the sentence without Nonce is: </b>" . $HashValue . "</h2>");
        echo ("<h2 style='color:yellow;'><br><b>You picked </b><u>" . $HashAlg . " </u><b>for the hash algorithm</b>" . "</h2></font>");
    }
    function PrintNewHash($Sentence, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger){
        $SentenceAndNonce = $_POST["Sentence"] . $Nonce;
        $HashValue = hash($CheckedHashAlg, $SentenceAndNonce);
        $NonceDisplay = number_format($Nonce);
        echo "<h2><br><b><font size='5'>Initial Hash Value with Nonce is:</b> " . $HashValue . "</h2></font>";        
        echo "<h2><br><b><font size='5'>Nonce value is:</b> " . $NonceDisplay . "</h2></font>";
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
    ?>
    
    <?php 
    PrintIntroHash($Sentence, $Nonce, $HashValue, $HashAlg, $NumZeroAccountFor);
    
    ///Start RUNNING Time Here
    $ExecutionStartTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    $PrevNonce = $Nonce;
    $ZeroToCheckFor = CheckForZero($HashValue, $NumZeroAccountFor);
    /* $ZeroOccurenceCheck =  CheckZeroOccurenceAndRunUntilMatchesZeros($ZeroToCheckFor, $Sentence, $NumZeroAccountFor, $CheckedHashAlg, $HashValue, $HashAlg, $Nonce, $TargetValue, $TargetValueAsInteger);
    $ZeroOccurenceCheck; */
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
    
    //END RUNNING TIME HERE
    $NumAttempts = $Nonce - $PrevNonce;
    $NumAttemptsDisplay = number_format($NumAttempts, 0);
     
    $HashRate = $NumAttempts / $CalcTime;
   
    /* 
    $HashRate = number_format($HashRate, 3); */
    /* $Nonce = number_format($Nonce, 0); */
    //$HashRate = int($HashRate);
    $HashRate = number_format($HashRate, 3);
    $NonceDisplay = number_format($Nonce);
    $NumZeroAccountForDisplay = number_format(pow(16,$NumZeroAccountFor), 0);
    echo("<hr size='10' color='white' ></hr>");
    echo ("<h3 style='color:red;'><br><b><u>Results</u>:</b></h3>");
    echo ("<h3 style='color:red;'><br><b>Hash Values for last occurence is:</b> $HashValue </h3>");
    echo ("<h3 style='color:red;'><br><b>Nonce value is:</b> $NonceDisplay </h3>");
    echo ("<h3 style='color:red;'><br><b>Total Number of attempts is:</b> $NumAttemptsDisplay </h3>");
    echo("<h3 style='color:red;'><br><b>The Expected number of attempts is:</b> $NumZeroAccountForDisplay </h3>");
    echo ("<h3 style='color:red;'><br><b>Start Time is:</b> $ExecutionStartTime Seconds</h3>");
    echo ("<h3 style='color:red;'><br><b>End Time is:</b> $ExecutionEndTime Seconds</h3>");
    echo ("<h3 style='color:red;'><br><b>Calculated Time Taken is:</b> $CalcTime Seconds</h3>");
    echo ("<h3 style='color:red;'><br><b>The Hash Rate is:</b> $HashRate </h3>");
    
    $ZeroToCheckFor;
    ?>
    

    <font size="6">
        <br><br><button onclick="goBack()">Go Back</button>
    </font>
</body>
<title>SHA HASH PROGRAM</title>
</html>