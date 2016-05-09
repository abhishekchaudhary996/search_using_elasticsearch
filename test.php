<?php

if(isset($_GET['search']))
{
  $term = $_GET['search'];
  $search_host = '127.0.0.1';
  $search_port = '9200';
  $index = 'people';
  $doc_type = 'details';
  $method = "GET";
  $user = 'abhishek';
  $password = 'portea';

  $new_user = "abhishek";

  $queryData = array('q' => $term) ; // writing query...................
  $url = 
  'http://'.$search_host.':'.$search_port.'/'.$index.'/'.$doc_type.'/_search?'.http_build_query($queryData);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_PORT, 9200);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
  //curl_setopt($ch, CURLOPT_USERPWD, "$user:$password");
  $result = curl_exec($ch);
  curl_close($ch);
  $ary = json_decode($result,true);

  $name_string = ""; $phone_string = ""; $email_string = "";
  foreach($ary['hits']['hits'] as $i)
  {
    $name_string = $name_string . $i['_source']['name'];
    $name_string = $name_string."-";
    $phone_string = $phone_string . $i['_source']['phone'];
    $phone_string = $phone_string."-";
    $email_string = $email_string . $i['_source']['email'];
    $email_string = $email_string."-";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
      if(isset($_GET['search'])) echo "<title> You Searched for ". $_GET['search']."</title>";
      else echo "<title>Search | Portea</title>";
    ?>    
    <link href="bootstrap-3.3.6-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>
<body>

  <div class="container-fluid">
  <!-- starting first row (header) - - - - - - - - - - - - - - - - - - - - - - - - - - - -  -->
    <div class="row">

      <div class="col-md-6">  </div>

      <div class="col-md-1" id = "search-term">
        <a href="test.php" style="text-decoration:none"> portea </a>
      </div>

      <div class="col-md-5" id = "search-term">
        <div class="input-wrapper">
          <form action = "" method="get">
          <input name = "search" type="text" id="user" required>
          <label for="user">search </label>
          </form>
        </div>
      </div>

    </div>
    <!-- ending first row (header) - - - - - - - - - - - - - - - - - - - - - - - - - - - -  -->

  </div>

  <!-- starting second row (displaying results) -  - - - - - - - - - - - - - - - - - - - - - - - - - -  -->
  <div class="row" id = "menu">
    <div class = "col-md-4 col-md-offset-2" >
    <?php
      if(!isset($_GET['id']))
      {
        $total_result = sizeof($ary['hits']['hits']);
        echo "<font color ='#A4A4A4'> About ".$total_result." result found </font>";
      }
      else
      {
        echo "<font color ='#A4A4A4'> Page ".($_GET['id'])."</font>";
      }
    ?>
    </div>
  </div>
  <!-- ending second row (displaying results) - - - - - - - - - - - - - - - - - - - - - - - - - - - -  -->

  <!-- starting third row (body) - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  -->
  <div class="row">
    <div class = "col-md-4 col-md-offset-2" >
      <?php
      // function to convert string to array of string .................
      function parse($string)
      {
        $temp = "";
        $arr_tem = array();
        for($j = 0; $j < strlen($string); $j++)
        {
          if($string[$j] != '-') $temp = $temp.$string[$j];
          else
          {
            array_push($arr_tem,$temp);
            $temp = "";
          }
        }
        return $arr_tem;
      }

      $id = 0; $start=0; $limit=4; $total = 13;
      if(isset($_GET['id']))
      {
        $id=$_GET['id'];
        $tem = ($_GET["ex"]); $tem1 = ($_GET["ex1"]); $tem2 = ($_GET["ex2"]);
        $start=($id-1)*$limit;
        $arr = parse($tem); $arr1 = parse($tem1); $arr2 = parse($tem2);
        if($start > sizeof($arr)) echo "No results found";
        else
        for($i = $start; $i < $start + $limit; $i++) 
        {
          if($arr[$i] != "")
          {
            echo "<a href=''> Email: ".$arr2[$i]." Name :".$arr[$i]."</a> <br>";
            echo "Name : ".$arr[$i]."<br> Phone : ".$arr1[$i]."<br>Email : ".$arr2[$i]."<br><br>";
          }
        }
      }
      else
      {
        $tem = $name_string; // containing name in form of string 
        $tem1 = $phone_string; // containing phone in form of string
        $tem2 = $email_string; // containing email in form of string
        $dump_data = "";

        if($tem == "" && $tem1 == "" && $tem2 == "") echo "Sorry no result found for ".$term;
        $new = parse($tem); // parsing name string to array...
        $new1 = parse($tem1); // parsing phone string to array...
        $new2 = parse($tem2); // parsing email string to array...

        // displaying all the results ................................
        for($i = 0; $i < sizeof($new); $i++)
        {
          echo "<a href=''> Email: ".$new2[$i]." Name :".$new[$i]."</a> <br>";
          echo "Name : ".$new[$i]."<br>"."phone : ".$new1[$i]."<br>"."Email : ".$new2[$i]."<br><br>";
        }
        echo "<br> <br>";
      }    
      
      ?>
    </div>
  </div>
  <?php
    if($id>1) echo "<center> <a href='?id=".($id-1).'&ex2='.$tem2. "&ex1=".$tem1. "&ex=".($tem). " ' class='button'>PREVIOUS</a> </center>";

      if($id!=$total) echo "<center> <a href='?id=".($id+1). '&ex2='.$tem2.  '&ex1='.$tem1.  "&ex=".($tem)."' class='button'>NEXT</a> </center>";
  ?>
  <!-- ending third row (body) - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  -->
</body>
</html>
