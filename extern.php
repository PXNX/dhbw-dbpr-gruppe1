<html>
  <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Erfassungsformular</title>
  </head>
  <body>

<h1 style="text-align:center"><p>Erfassungsformular erstellen</p></h1>
<form action="bestellung_erfassen.php" method="post">
    <table>
  <tr>
    <td><label for="marktid"><b>Markt ID:</b></label></td>
    <td><input name="marktid" size="40" maxlength="60" value="" required/><br></td>
  </tr>
  <tr>
    <td><label for="anzahl"><b>Anzahl Bestellpositionen:</b></label> </td>
    <td><input name="anzahl" size="40" maxlength="5" value="" required/><br></td>
  </tr>
  </table><br>
    <button type="submit" name="erfassen">Erfassungsformular erstellen</button></br>
  </form>


 </body>
</html>
