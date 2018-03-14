<html>
    <body>
        <h1>Test</h1>
        <?php
            parse_str($_SERVER['QUERY_STRING'], $query);


            foreach($query as $key=>$value){
                echo "$key => $value<br/>";
            }
        ?>

        <?php 
            $servername = "localhost";
            $username = "test";
            $password = "test";

            try{
                $conn = new PDO('mysql:host=localhost;dbname=sys', $username, $password);

                // Insert new query string parameters not seen before
                foreach($query as $key=>$value){
                    $insertsql = "INSERT INTO sys.queryparams (queryparam) 
                    SELECT '$key' 
                    WHERE NOT EXISTS (SELECT * FROM sys.queryparams WHERE queryparam='$key');";
                    $conn->query($insertsql);
                }

                
                $sql = "SELECT queryparam FROM queryparams";
                foreach($conn->query($sql) as $row) {
                    echo $row['queryparam'] . "<br />";
                }
            }catch(PDOException $e){
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        ?>
    </body>
</html>