<?php
	fwrite(STDOUT,'请输入階層數量：');
	echo '排列組方法有：'.floorTest2(fgets(STDIN))."種";

	function floorTest($n){
		$count = 0;
		$count = floor($n/2)+1; //n層樓梯的攀爬方法為 (n/2)+1種 +1是因為有全用爬一層階梯的方式，剩下的次數則看2層可以爬幾次
		return $count;
	}

	function floorTest2($n){
		$x = 0; //兩層的方法的最大數量
		$x = floor($n/2); //如果有排列組合的要求的話則用 max 取 y的方式  從x的最大值取到0為止 max!/(y!*(max-y)!)
		
		$count = 0 ;
		for($i=$x;$i>=0;$i--){
			$y=$n-($i*2);  // y就是兩階走完後如果剩多少一階
			$max = $i+$y;	 // 有前後的順序問題 所以max值為x+y
			
			//echo factoryTest($max)/(factoryTest($y)*factoryTest($max-$y))."<br>";
			
			$count += factoryTest($max)/(factoryTest($y)*factoryTest($max-$y));
		}
		return $count;
	}

	function factoryTest($n){
		//判定是否大於零
		if($n>0){
			if($n>=2){
				$temp=$n ;
				$n=$n-1;
				//遞迴
				$total=$temp * factoryTest($n);
				return $total;
			}else{
				return 1;
			}	
		}else{
		    return 1;
		}
	}

?>