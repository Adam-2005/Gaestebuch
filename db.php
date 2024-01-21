<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styleform.css" type="text/css" >
</head>
<body>
    <form action="./db.php" method="post">
        <input type="text" name="nname" placeholder="Nachname" size="7"><br>
        <input type="text" name="vname" placeholder="Vorname" size="7"><br>
        <input type="text" name="email" placeholder="Email" size="7"><br>
        <input type="text" name="kommentar" placeholder="Kommentar" size="7"><br><br>
        <input type="submit" value="speichern" name="Speichern"> <br>
        <input type="submit" value="Gästebuch anzeigen" name="anzeigen"><br><br>
        <input type="text" name="löschen" placeholder="ID zum Löschen angeben" size="19"><br>
        <input type="submit" value="ID Löschen" name="ID_Löschen"><br>
        <input type="submit" value="Seite leeren" name="nichts"> 
    </form>

    <?php
    ##-----dim-Block----------------
     $nname=$_POST["nname"];
     $vname=$_POST["vname"];
     $email=$_POST["email"];
     $COMMENT=$_POST["kommentar"];
     $ID = $_POST["löschen"];
     
    ##-----BTN-Ausfürung----------------                
    if(isset($_POST['Speichern'])){
        speichern($nname,$vname,$email,$COMMENT);
    }if(isset($_POST['anzeigen'])){
        anzeigen();
    }if(isset($_POST['ID_Löschen'])){
        idLöschen($ID);   
    }

    #-----function----------------
    function speichern($nname,$vname,$email,$COMMENT){  # Speichern von Name,Vorname,E-mail und einem Kommentar
        echo "Sie haben folgende Eingaben getätigt";
        echo "<br>Nachname: ";
        echo $nname;
        echo"<br> Vorname: ";
        echo $vname;
        echo "<br>email: ";
        echo $email;
        echo"<br> Kommentar: ";
        echo $COMMENT;

        PDO($pdo);
        $statement= $pdo->prepare ("INSERT INTO gaestebuch (nname,vname,email,COMMENT) VALUES ( ?,?,?,?)");
        $statement->execute(array($nname, $vname, $email, $COMMENT));
        $neue_ID = $pdo->lastInsertID();
        echo "ihre neue ID ist $neue_ID";
    }
    function anzeigen(){                                # Ausgabe der Daten 
        
        PDO($pdo);
        $sql = "SELECT * FROM gaestebuch";
        foreach ($pdo->query($sql) as $row){
            echo $row['id']." ".$row['nname']." ".$row['vname']." ".$row['email']." ".$row['COMMENT']."<br>";
        }
    }
    function idLöschen($ID){                            # Löschen von Daten mit der ID
        try{
            PDO($pdo);
            $sql=$pdo->prepare("DELETE FROM gaestebuch WHERE id = ($ID)");
            $sql->execute();
            echo" der Benutzer mit der ID: $ID wurde gelöscht";
        }catch(Exception $ID){
            echo "Erro: ID muss angegeben werden";
        }
         
    }
    function PDO(&$pdo){                                # Verbindung zur Datenbank herstellen
        include("./dbinc.inc");                 
        $pdo =new PDO("mysql:host=$host; dbname=$dbName" ,"$user","$passwort");
    }
    ?>
</body>
</html>