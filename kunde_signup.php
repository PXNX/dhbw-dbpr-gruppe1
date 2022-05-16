<html>
    <head>
    <meta charset="UTF-8"> 
    <meta author="Patricia Schäle">
    <title>Kunde Registrieren</title>
    </head>
    <body>

    <h1 style="text-align:center"><p>Schritt 3: Registrieren</p></h1>

<form method="post" action="kunde_signup.php">

<label for="e-mail">E-Mail Adresse:</label>
<input type="text" id="e-mail" name="e-mail" required>
<br><br>

<label for="kunde-pass">Passwort:</label>
<input  id="kunde-pass" name="kunde-pass" type="password" required>
<br><br>

<label for="kunde-name">Name:</label>
<input  id="kunde-name" name="kunde-name" required>
<br><br>

<label for="kunde-adresse">Adresse:</label>
<input  id="kunde-adresse" name="kunde-adresse" required>
<br><br>

<input  id="kunde-signup"  type="submit" value="Registrieren"> 
</form><br><br>

    </body>
</html>