<html>
  <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Erfassungsformular</title>
  </head>
  <body>
<h1 style="text-align:center"><p>Schritt 1: Erfassungsformular erstellen</p></h1>
<form action="erfassung.php" method="post">
    <table>
  <tr>
    <td><label for="mark"><b>Markt ID:</b></label></td>
    <td><input name="markt" size="40" maxlength="60" value="" required/><br></td>
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
