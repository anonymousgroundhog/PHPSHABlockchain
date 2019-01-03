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
    <h1><u><font size="5">BART Mining Program</font></u></h1>
     <table border="1">
    <tr>
        <td>
           <p>
           Information:
            <br>This is a program designed to aid in the understanding of how hashing works.
            </p>       
       </td>
   </tr>
   </table>
   <br><br>
    <form action="SimpleHash2.php" method="post">    
     Enter in Sender Name: <textarea id="SenderAddr" name = "SenderAddr" rows="1" cols="10" title="Enter in sender Name." ><?php if(isset($_POST['SenderAddr'])) { 
         echo htmlentities ($_POST['SenderAddr']); }?></textarea><br>
     Enter in Amount: <textarea id="Amount" name = "Amount" rows="1" cols="3" title="Enter in a Amount." ><?php if(isset($_POST['Amount'])) { 
         echo htmlentities ($_POST['Amount']); }?></textarea><br>
     Enter in Receiver Name: <textarea id="ReceiverAddr" name = "ReceiverAddr" rows="1" cols="10" title="Enter in a Receiver Name." ><?php if(isset($_POST['ReceiverAddr'])) { 
         echo htmlentities ($_POST['ReceiverAddr']); }?></textarea><br>
    Enter in Nonce Value: <textarea id="Nonce" name = "Nonce" rows="1" cols="3" title="Enter in a nonce value here to calculate the hash."><?php if(isset($_POST['Nonce'])) { 
         echo htmlentities ($_POST['Nonce']); }?></textarea><br>
    </select>    
    <br>    
    <br><input type="submit">
    </form>    
    <?php 
    if (strLen(GetInputData())>=8){
        PrintoutToUser("red", "4", "Hash Value: " . GetNewHash(GetInputData(),"sha256"));
    }
    function GetInputData(){
        $Amount = htmlspecialchars($_POST['Amount']);
        $Nonce = htmlspecialchars($_POST['Nonce']);
        $SenderAddr = htmlspecialchars($_POST['SenderAddr']);
        $ReceiverAddr = htmlspecialchars($_POST['ReceiverAddr']);            
        $DataToReturn = $SenderAddr . " " . $Amount . " " . $ReceiverAddr . " "  . $Nonce;
        return $DataToReturn;
    }
    function GetNewHash($DataToHash, $HashAlg){
        $HashValue = hash($HashAlg, $DataToHash);
        return $HashValue;
    }
    function PrintoutToUser($FontColor, $FontSize, $DataToPrintOut){
        echo "<font size='". $FontSize ."' color='". $FontColor ."'>" . $DataToPrintOut . "</font> <br />";
    }
    ?>   
    <br><a href="index.html"><font color="red">Link Back Home</font></a> 
</body>
<title>BART PROGRAM</title>
</html>