<html>
  <head>
    <meta charset="UTF-8"> 
    <title>PHP Test</title>
  </head>
  <body>
    
    <?php echo '<h1 style="text-align:center"><p>Bestellung erfassen</p></h1>';

    echo '  <form method="post" action="http://vorlesungen.kirchbergnet.de/inhalte/DB-  PR/output_posted_vars.php" name="..." target="...">
    <table>
  <tr>
    <td><label for="marktid"><b>Markt ID:</b></label></td>
    <td><input name="marktid" size="40" maxlength="60" value="" /><br></td>
  </tr>
  <tr>
    <td><label for="anzahl"><b>Anzahl Bestellpositionen:</b></label> </td>
    <td><input name="anzahl" size="40" maxlength="5" value=""/><br></td>
  </tr>
  </table><br>
    <center><input type="submit" value="weiter"/></center>
  </form>';

?>
  </body>
</html>
