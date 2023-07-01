<?php

        $bdd = new PDO('mysql:host=database;dbname=mymap;charset=utf8', 'user', 's3cr3t');
        $bdd->exec("CREATE TABLE IF NOT EXISTS point (id INT KEY AUTO_INCREMENT, lon FLOAT, lat FLOAT);");

        switch ($_GET['action']) {
            case 'add':
                if (isset($_GET['lon']) && isset($_GET['lat'])){
                    $bdd->exec(sprintf("INSERT INTO point (lon, lat) values (%F , %F)", $_GET['lon'], $_GET['lat']));
                }
                exit;
            case 'delete':
                if (isset($_GET['id'])){
                    $bdd->exec(sprintf("DELETE FROM point where id = '%d'", $_GET['id']));
                }
                exit;
            case 'get':
                $result = $bdd->query("select * from point;");
                $features = array();
                while ($donnees = $result->fetch()){
                    $features[] = array(
                        'type' => 'Feature',
                        'properties' => array('id' => intval($donnees['id'])),
                        'geometry' => array(
                            'type' => 'Point',
                            'coordinates' => array(floatval($donnees['lon']), floatval($donnees['lat']))
                        )
                        );
                }
                $new_data = array(
                    'type' => "FeatureCollection",
                    'features' => $features,
                  );
                header("Content-type: application/json");
                echo json_encode($new_data, JSON_PRETTY_PRINT);
                exit;
        }
        ?>

        <!DOCTYPE html>
        <html>
        <head>

            <title>MyMap</title>

            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
            <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

            <style>
                html, body, #mapid {
                    margin: 0;
                    padding: 0;
                    width: 100%;
                    height: 100%;
                }
            </style>
        </head>
        <body>
        <div id="mapid"></div>
        <script>
        
            var mymap = L.map('mapid').setView([46, 0], 6);
            var datalayer;
            var needzoom = true;

            L.tileLayer('http://basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
                maxZoom: 21,
                attribution: "<?php echo 'Page générée à ' . date("H:i:s");?>"
            }).addTo(mymap);

            function getData(){
                if (datalayer){
                    datalayer.remove()
                }
                $.get('/?action=get',function(data){
                datalayer = L.geoJson(data,{
                    onEachFeature: function(feat, layer){
                        layer.on('click',function(e){
                            $.get('/?action=delete&id='+feat.properties.id,()=>getData());
                        })
                    }
                }).addTo(mymap)
                if (needzoom){
                    needzoom = false;
                    mymap.fitBounds(datalayer.getBounds())
                }
                })
            }

            mymap.on('click', function(e){
                $.get('/?action=add&lon='+e.latlng.lng+'&lat='+e.latlng.lat,()=>getData());
            })

            getData()
        </script>
        </body>
        </html>