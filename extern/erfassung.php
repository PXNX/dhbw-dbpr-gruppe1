<?php
    //hier kommen die ganzen includes rein
    include_once 'includes/dbh.inc.php';
    

    //Autor Silas Blender

  $sql = "SELECT  marketname FROM market;";
  $marketnames = mysqli_query ($conn, $sql);
  session_start();

?>

<!DOCTYPE HTML>

<html>
    <HEAD>
        <meta charset="utf-8" />
        <title>Create order</title>
    </HEAD>
    <body>
    
    <?php

    // Es wird geschaut, ob der User etwas eingegeben hat. Falls ja wird dies in der Session gespeichert.
    
    if(isset($_POST['market']) && $_POST['market'] != ""){
         
    $_SESSION["market"] = $_POST['market'];
    $_SESSION["position"] = $_POST['PositionNumber'];
    
    }       

    /*    
    if(isset($_Session['Fehlermeldung2'])){
        for($i = 0; $i<$_SESSION["position"]; $i++) {
            echo $_Session['Fehlermeldung2'][$i];
        }
    }
    */

    // Dieser Abschnitt wird aktiviert, wenn eine Session gesetzt ist und der User hier zurück geleitet wird.
    if(isset($_SESSION['market'])){
     

?>
        <h1>Create order</h1>
        <div class="forma">
            <form method="POST" action="CreateOrder.php" name="marketname">
            <label for ="market"><h2> Select Market: </h2></label>
            <select id="market" name="market">
            <option value="">--- Markt auswählen ---</option>
            <?php

            while ($cat = mysqli_fetch_array(
                                $marketnames,MYSQLI_ASSOC)):;

                ?>
                    <option <?php if($_SESSION["market"] == $cat['marketname']){echo "selected";} ?> value="<?php echo $cat['marketname'];
                    ?>">
                               <?php echo $cat['marketname'];?>
                    </option>
                <?php
              endwhile;
                ?>
            </select>
                <table>
                    <tr>
                        <td><h2>Enter Number of Positions:</h2></td>
                        <td><input type="number" name="PositionNumber" value="<?php echo $_SESSION['position']; ?>"></td>
                    </tr>
                </table>
                
                <p> <input type="submit" name="CreateOrderPositions" value="Create order positions"> </p>
            </form>
        </div>
        <?php
            if(isset($_SESSION["market"]) && $_SESSION["market"] != "" && isset($_SESSION["position"]) && $_SESSION["position"] != ""){
                if($_SESSION["position"]>0 && $_SESSION["position"]<10) {
        ?>

 <h2>Order at <?php echo $_SESSION['market'];?></h2>
            <form method="post" action="CheckOrder.php?&market=<?php echo $_SESSION['market'];?>&pos=<?php echo $_SESSION['position'];?>">  
                <table> 
                    <tr> 
                        <th>Market</th>
                        <th>Select Beverage</th>
                        <th>Amount</th>
                    </tr>
                    <tr>
            <?php

            for($i = 0; $i<$_SESSION['position']; $i++) {
            ?>
                
                <tr>
                    <td><?php echo $_SESSION['market']; ?></td>
                    <td> 
                        <select id="getraenke" name="allegetraenke<?php echo $i;?>">
                        <option value="">--- Select ---</option>
                        <?php
                        // Query benutzen, welche mit dem Marktnamen die ID ermittelt und dann checkt, welche Getränke mit positivem Lagerbestand der Markt mit dieser ID anbietet
                        $sql2 = "Select beveragename from Storage where id = (SELECT id from market where marketname = '" . $_SESSION['market'] . "') and stock > 0;";
                        // Query ausführen
                        $allBeverages = mysqli_query ($conn, $sql2);
                        // Ergebnisse in dem Dropdown-Menü anzeigen lassen per while-schleife
                            while ($sus = mysqli_fetch_array(
                                $allBeverages,MYSQLI_ASSOC)):;
                        ?>
                        <option <?php if(isset($_SESSION['getraenke'])){if(count($_SESSION['getraenke']) > $i){if($_SESSION["getraenke"][$i] == $sus["beveragename"]) {echo "selected";} ?> value="<?php echo $sus['beveragename'];
                        }}?>">
                            <?php echo $sus['beveragename'];?>
                        </option>
                        <?php
                            endwhile;
                        ?>
                        </select>
                        </td>
                    <td><input type="number" name="amount<?php echo $i;?>" value="<?php if(isset($_SESSION['getraenke'])){if(count($_SESSION['getraenke']) > $i){echo $_SESSION['getraenkeAnzahl'][$i];}} ?>"></td>
                </tr>
                
                <?php  } ?>
                </tr>
            </table>
            <p> <button id="CheckOrder" action="submit" value="Check Order"> Check Order</button> </p>
        </form>
<?php
            if(isset($_SESSION["Fehlermeldung1"])){
                echo $_SESSION["Fehlermeldung1"];
            }
            if(isset($_SESSION['Fehlermeldung2'])){
                for($i = 0; $i<count($_SESSION['Fehlermeldung2']); $i++) {
                    echo $_SESSION['Fehlermeldung2'][$i];
                    ?>
                    <br></br>
                    <?php
                }
            }

                } else {
                    echo "<h2>Your number of positions is too large or too low</h2>";
                } 
            }   else {
                echo "<h2>Market or number of positions are not filled out</h2>";
            }
    }

    // Dieser Abschnitt wird aktiviert, wenn keine Session gesetzt ist. Dann soll es dem User möglich sein, seine Eingaben beim Markt und Anzahl an Bestellpositionen zu setzen.
     else {
         
    ?>
    
        <h1>Create order</h1>
        <div class="forma">
            <form method="POST" action="CreateOrder.php" name="marketname">
            <label for ="market"><h2> Select Market: </h2></label>
            <select id="market" name="market">
            <option value="">--- Select ---</option>
            <?php

            while ($cat = mysqli_fetch_array(
                                $marketnames,MYSQLI_ASSOC)):;

                ?>
                    <option value="<?php echo $cat['marketname'];
                    ?>">
                               <?php echo $cat['marketname'];?>
                    </option>
                <?php
              endwhile;
                ?>
            </select>
                <table>
                    <tr>
                        <td><h2>Enter Number of Positions:</h2></td>
                        <td><input type="number" name="PositionNumber" placeholder="Enter number"></td>
                    </tr>
                </table>
                
                <p> <input type="submit" name="CreateOrderPositions" value="Create order positions"> </p>
            </form>
        </div>
        <br><hr><br>

<?php
     }
   
?>
    
     <br><hr><br> 
    </body>
</html>
