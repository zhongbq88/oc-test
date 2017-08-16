<?php
 
function main ()
	{
	 $list1 = array(0,1,2);
	  $list2 = array(3,4,5);
	   $list3 = array(7,8,9);
	   $allList = array($list1,$list2,$list3);
        $result=  calculateCombination($allList, 0, $arr=array(),$arr2= array());  
		print_r( $result);
	}
 
 function calculateCombination($inputList, $beginIndex, $arr,$arr2) {
	    global $arr2;
        if($beginIndex == count($inputList)){
			//print_r($beginIndex);
			$arr2[] = $arr;
			//print_r($arr);
			//print_r($arr2);
            return ;
        }
        foreach($inputList[$beginIndex] as $c){
            $arr[$beginIndex] = $c;
            calculateCombination($inputList, $beginIndex + 1,$arr,$arr2);
        }
		return $arr2;
   }
 main ();
?>
