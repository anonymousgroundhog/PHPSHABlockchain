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
    <h1><u><font size="5">Generating a Hash</font></u></h1>
    
    <!-- FORM INPUT HERE -->
    <form action="Exercise1.php" method="post">
    Enter in Sentence: <textarea id="Sentence" name = "Sentence" rows="4" cols="50" title="Enter in a sentence here to calculate the hash."></textarea><br>
    </select>    
    <br>    
    <br><input type="submit">
    </form>    
    <?php 
    if (strLen(GetInputData())>=1){
        PrintoutToUser("red", "4", "Hash Value: " . GetNewHash(GetInputData(),"sha256"));
    }
    function GetInputData(){
        $Sentence = htmlspecialchars($_POST['Sentence']);
        return $Sentence;
    }
    function GetNewHash($DataToHash, $HashAlg){
        $HashValue = hash($HashAlg, $DataToHash);
        return $HashValue;
    }
    function PrintoutToUser($FontColor, $FontSize, $DataToPrintOut){
        echo "<font size='". $FontSize ."' color='". $FontColor ."'>" . $DataToPrintOut . "</font> <br />";
    }
    ?>
    <font size="6">
        <br><br><a href="index.html"><font color="red" size="5">Link Back Home</font></a>
    </font>
</body>
<title>Exercise 1</title>
</html>