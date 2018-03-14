<html>
<?php
    $servername = "localhost";
    $username = "test";
    $password = "test";
 ?>
    <body>
        <h1>Sending and saving values test</h1>

        <h2>Received values</h2>
        <?php
            parse_str($_SERVER['QUERY_STRING'], $query);

            foreach($query as $key=>$value){
                echo "You sent: $key = $value<br/>";
            }
        ?>

        <?php 
            function addValueToDb($type, $value){
                $servername = "localhost";
                $username = "test";
                $password = "test";

                try{
                    $conn = new PDO("mysql:host=$servername;dbname=sys", $username, $password);

                    // Insert new query string parameter if has not been seen before
                    $insertsql = "INSERT INTO sys.queryparams (queryparam) 
                                    SELECT '$type' 
                                    WHERE NOT EXISTS (SELECT * FROM sys.queryparams WHERE queryparam='$type');

                                  INSERT INTO sys.values (value, type) 
                                    SELECT '$value', id
                                    FROM sys.queryparams 
                                    WHERE queryparam='$type';
                                  ";
                    $conn->query($insertsql);

                }catch(PDOException $e){
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
            }
        ?>

        <h2>Known types in DB</h2>
        <?php 
            try{
                foreach($query as $key=>$value){
                    addValueToDb($key, $value);
                }

                $conn = new PDO("mysql:host=$servername;dbname=sys", $username, $password);
                $sql = "SELECT queryparam FROM queryparams";
                foreach($conn->query($sql) as $row) {
                    echo $row['queryparam'] . "<br />";
                }
            }catch(PDOException $e){
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        ?>

        <h2>Values in DB</h2>
        <?php
            try{
                $conn = new PDO("mysql:host=$servername;dbname=sys", $username, $password);
                $sql = "SELECT * FROM sys.values v INNER JOIN sys.queryparams q ON v.type=q.id ORDER BY v.id DESC";
                foreach($conn->query($sql) as $row){
                    echo $row['queryparam'] . " = " . $row['value'] . "<br />";
                }

            }catch(PDOExceptoin $e){
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        ?>
    </body>
</html>