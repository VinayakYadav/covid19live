<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>

    <?php
    
    
    $url =  'https://covid19.mathdro.id/api/countries';
    $type = "GET";

   $data = HitCurl($url,[],$type);

   $url2 = 'https://covid19.mathdro.id/api';
   $data2 = HitCurl($url2,[],$type);
   $res2 = (array) json_decode($data2);
   
   $date = new DateTime($res2['lastUpdate']);


   

 $decData = json_decode($data);
 
//  echo "<pre>";
//  print_r ($decData->countries);
//  echo "</pre>";
 
?>

<div class="container">
<center>
<select class="browser-default custom-select country" name="countryname" id="countrynameid" style=" width: 27%; background-color: aqua; ">
  <option selected>Open this select menu</option>
  <?php
  
  foreach($decData->countries as $value){
  ?>
    <option value="<?= $value->name?>"><?= $value->name ?></option>
  <?php }
  ?>
</select></center>

</div>
<div class="container " id="livedata">
    <div class="row">
        <div class="col-md-6">
                <center><h1><u> <?= $date->format('Y-m-d'); ?></u></h1>
                        <h3 style="color:red"><span id="confirmed"></span></h3>
                        <h3 style="color:green"><span id="recovered"></span></h3>
                        <h3 style="color:yelow"><span id="deaths"></span></h3>
                </center>

        </div>
        <div class="col-md-6">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
    <div class= 'container'>
        <center><h1><u> World Coronavirus Update (Live) <?= $date->format('Y-m-d'); ?></u></h1>
        <h3 style="color:red">Confirm Case = <span><?= $res2['confirmed']->value ?></span></h3>
        <h3 style="color:green">Recovered Case = <span><?= $res2['recovered']->value ?></span></h3>
        <h3 style="color:yelow">Total Deaths Case = <span><?= $res2['deaths']->value ?></span></h3>
        </center>

    </div>

</body>
</html>

<script>

$(document).ready(function(){
    $("#livedata").hide();
    $("select.country").change(function(){
        
        var selectedCountry = $(this).children("option:selected").val();
        $.getJSON("https://covid19.mathdro.id/api/countries/" + selectedCountry, function(json) {
            // var json = Array.isArray(json);
            console.log(json);
            // alert(data)
        //    alert(json.confirmed.value);
        //    return
            // var json = json.slice(-1)[0];
            // console.log(json);
            if(json != undefined){
            var countryname = selectedCountry;
            var Confirmed = json.confirmed.value;
            var Recovered = json.recovered.value;
            var Deaths = json.deaths.value;
            document.getElementById("confirmed").innerHTML = "Confirm Case ="+Confirmed;
            document.getElementById("recovered").innerHTML = "Recovered Case = "+Recovered;
            document.getElementById("deaths").innerHTML = "Deaths Case = "+Deaths;
            var date = new Date(json.lastUpdate);
            date = date.toLocaleDateString();
            }else{
                var countryname = 'NO DATA';
            var Confirmed = 0;
            var Recovered = 0;
            var Deaths = 0;
            date = '0000-00-00';
            // var Active = 0;
            }

            let myChart = document.getElementById('myChart').getContext('2d');

            //global options
            Chart.defaults.global.defaultFontFamily = 'Lato';
            Chart.defaults.global.defaultFontSize = 10;
            Chart.defaults.global.defaultFontColor = '#777';
            let massPpoChart = new Chart(myChart, {
                type: 'bar',//bar,horizontalBar,pie,line,doughnut,radar,polarArea
                data:{
                    labels:['Confirmed','Recovered','Deaths'],
                    datasets:[{
                        label:countryname,
                        data:[
                            Confirmed,
                            Recovered,
                            Deaths,
                        ],
                        // backgroundColor :'red'
                        backgroundColor:[
                        '#FF3342',
                        '#3FFF33',
                        '#17202A',
                        ],

                        borderWidth:1,
                        borderColor:'#777',
                        hoverBorderWidth:3,
                        hoverBorderColor:'#000'
                    }]
                },
                options:{
                    title:{
                        display:true,
                        text:"Coronavirus Details,  ("+ date +") , "+ countryname+" ",
                        fontSize :50
                    },
                    legend:{
                        display:true,
                        position : 'right',
                        labels:{
                            fontColor: '#000'
                        }
                    },
                    layout:{
                        padding:{
                            left:50,
                            right:0,
                            bottom:0,
                            top:0
                        }
                    },
                    tooltips:{
                        enabled:true,
                    }
                }
            });






        });
        $("#livedata").show();
        
    });


});

</script>

<?php

    
    
     function HitCurl($url,$data=[],$type="POST"){

        $Fields = http_build_query($data);

        $config = array(

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => TRUE,

            

        );

        if($type == "POST"){

            $config[CURLOPT_POST] = TRUE;

            $config[CURLOPT_POSTFIELDS] = $Fields;

        }else{

            $config[CURLOPT_URL] = "{$url}?{$Fields}";

        }

        $ch = curl_init();

        curl_setopt_array($ch,$config);

        $Result = curl_exec($ch);

        curl_close($ch);                               

        return $Result;

    }
    
?>