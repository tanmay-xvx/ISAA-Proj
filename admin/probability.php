<?php 
session_start();

$con = mysqli_connect("localhost","root","");

if (!$con)
    echo('Could not connect: ' . mysqli_error());
else {
    mysqli_select_db($con,"dataleakage" );

    $qry1="SELECT * from register";
    $result1=mysqli_query($con, $qry1);
     
    //leaked data set S
    $Set=["t2","t3","t0","t1","t4"];
    //$S="t1";
    $p=0.2; // most probable value of p

    $qry5="SELECT * from record";
    $result5=mysqli_query($con, $qry5);
    
    $product=[];
    $finalAgents=[];
    while($w1=mysqli_fetch_array($result5)){
        array_push($product,1);
        array_push($finalAgents,$w1["sendto"]);
    }

    foreach($Set as $S){
        $agents=[];
        $data=[];

        $qry="SELECT * from record";
        $result=mysqli_query($con, $qry);
        //all agents and their data
        while($w1=mysqli_fetch_array($result) ){
            $currentAgent=$w1["sendto"];
            if(!in_array($currentAgent,$agents)) {
                // echo $currentAgent."djiweufjwef<br>";
                array_push($agents, $currentAgent);
                $sub=$w1["subject"];
                $sql=mysqli_query($con,"SELECT * from presentation WHERE subject = '$sub'");
                $w=mysqli_fetch_array($sql);
                $key=$w["objNames"];
                array_push($data, $key);
            }
        }
        print_r($agents);echo"ehwiu<br>";
        $num=count($agents);
        //set data as null if obj not present
        for($i =0;$i<count($agents);$i++){
            $myArray = explode(',', $data[$i]);
            print_r($myArray);
            if(!in_array($S,$myArray)){
              $data[$i]="";
              $num--; 
            }
        }
        //calc product
        for($i =0;$i<count($agents);$i++) {
            if($data[$i]!==""){
                $product[$i]*=1-(1-$p)/$num;
            }
        }
        // print_r($product);
        // print_r($finalAgents);
        // echo "<br/>";
        $finalAgents=$agents;
    }

    for($i =0;$i<count($finalAgents);$i++){
        // echo $product[$i].$finalAgents[$i]."<br>";
        $prob=1-$product[$i];
        $sql6 = "UPDATE leaker SET probability='$prob' WHERE name='$finalAgents[$i]' ";
        $result6 = mysqli_query($con,$sql6);
        // echo "done ".$finalAgents[$i];
    }
  
    header("Location: leakfile.php");
}
?>