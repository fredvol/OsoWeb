<!DOCTYPE html>
<html>
<!-- Api v1 -->

<head>
    <meta charset="utf-8" />
    <title>Oso Api V1</title>
</head>
    <body>

    <h1>OSO Api V1</h1>
    <p>
        OSO Database API add<br />
    </p>
    <?php
    // exemple url: http://192.168.1.56/oso_web/V1/add.php?u=26fp2112&datept=2016-12-21%2023:25:06&lat=45.4655&long=3.30528&alt=1520&bat=18


// CONNEXION TO DATABASE
        try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=oso_test;charset=utf8', 'root', 'mysqlfred', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }


// MANAGE GET REQUEST
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {   
            echo "<br>".'allParam :';
            if (allParam()) {
                    echo 'true '."<br>";
            }else{
                    echo 'false '."<br>";
            }

            echo "<br>".'allParam are valid:' . allParam()."<br>"."<br>";



            if (allParam()) // Check if  there is a all the parameters
                    {
                        try
                        {
                            echoParam();
                            echo "<br>".'----query-- '."<br>";
                            echo "<br>".addParaminquery()."<br>";
                            echo "<br>".'------ '."<br>";					

                            $req =$bdd->query(addParaminquery());

                            echo "<br>".'------ '."<br>";
                            echo "Number of row affected :" . $req->rowCount();
                            echo "<br>".'------ '."<br>";



                            $req->closeCursor(); // Termine le traitement de la requête
                        }
                        catch(Exception $e)
                        {
                                die('Erreur : '.$e->getMessage());
                        }
                    }
                    else // sql missing
                    {
                        echo 'Parameter not valid';
                    }


            function echoParam(){
                echo 'u '.($_GET['u'])."<br>";
                echo 'datept '.($_GET['datept'])."<br>";
                echo 'lat '.($_GET['lat'])."<br>";
                echo 'long '.($_GET['long'])."<br>";
                echo 'alt '.($_GET['alt'])."<br>";
                echo 'bat '.($_GET['bat'])."<br>";				
            }  	

            function addParaminquery(){
                $query='INSERT INTO oso_test.position (id, user, datept, latitude, longitude, altitude, battery) VALUES (NULL, \':user\', \':datept\', :lat, :long, :alt, :bat)';
                $assoc=array(
                ':user'=> $_GET['u'],
                ':datept'=> $_GET['datept'],
                ':lat'=> $_GET['lat'],
                ':long'=> $_GET['long'],
                ':alt'=> $_GET['alt'],
                ':bat'=> $_GET['bat']
                );
                $exQuery=str_replace(array_keys($assoc), array_values($assoc), $query);
                //http://192.168.1.56/oso_web/V1/add.php
                return $exQuery;
            }

            function allParam(){
                if (isset($_GET['u']) 
                    AND isset($_GET['datept'])
                    AND isset($_GET['lat'])
                    AND isset($_GET['long'])
                    AND isset($_GET['alt'])
                    AND isset($_GET['bat'])	)
                    {
                            return true;
                    } 
                            return false;
            }  

        }

    function debug_to_console( $data ) {
        if ( is_array( $data ) )
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
        echo $output;
    }


    function isValidJSON($str) {
       json_decode($str);
       return json_last_error() == JSON_ERROR_NONE;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $json_params = file_get_contents("php://input");

        if (strlen($json_params) > 0 && isValidJSON($json_params))
        {
            $decoded_params = json_decode($json_params);
            echo "<br>".'Post Valid'."<br>";
            //echo "<br>".var_dump($decoded_params)."<br>";
            $totalrowModif=0;
            foreach ($decoded_params as &$postPoints) {
                
                try
                    {		

                        $req =$bdd->query(addParaminPOSTquery($postPoints));

                        $totalrowModif=$totalrowModif+$req->rowCount();
                        $req->closeCursor(); // Termine le traitement de la requête
                    }
                    catch(Exception $e)
                    {
                            die('Erreur : '.$e->getMessage());
                    }
            }
          
        } else {
            echo "<br>".'POST Parameters not valid'."<br>";
        }
        
        
        echo "<br>".'TOTAL ROW AFFECTED: '.$totalrowModif."<br>";
        
        
    }
    
    // Function to make the query  from POST
    //TODO : check if paramter exist/
        function addParaminPOSTquery($postPoints){
                $query='INSERT INTO oso_test.position (id, user, datept, latitude, longitude, altitude, battery) VALUES (NULL, \':user\', \':datept\', :lat, :long, :alt, :bat)';
                $assoc=array(
                ':user'=> $postPoints->{"u"},
                ':datept'=> $postPoints->{"datept"},
                ':lat'=> $postPoints->{"lat"},
                ':long'=> $postPoints->{"long"},
                ':alt'=> $postPoints->{"alt"},
                ':bat'=> $postPoints->{"bat"},
                );
                $exQuery=str_replace(array_keys($assoc), array_values($assoc), $query);
                //http://192.168.0.52/oso_web/V1/add.php
                return $exQuery;
            }
    
    
    
    
    /*To generate random  on http://www.json-generator.com/
     * [
            '{{repeat(10, 20)}}',
            {
              index: '{{index()}}',
              u: 'zb829cn',
              datept: '{{date(new Date(2016,11, 30),new Date(2016,11, 30), "YYYY-MM-dd hh:mm:ss")}}',
              lat: '{{floating(42.400001, 42.600001)}}',
              long: '{{floating(1.700001, 2.400001)}}',
              alt: '{{integer(1500, 2000)}}',
              bat: '{{integer(15, 100)}}'
            }
          ]
     */

    ?>
    </body>
</html>