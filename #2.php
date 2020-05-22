<?php
    $cart = new Cart();
    $cart->addItem("0","alcohol","100","5");
    // $cart->addItem("1","mask","5","20");
    // $cart->subItem("1","10");
    // $cart->cartList();
    $cart->cartList();

    class Cart {

        /**
          * 新增商品到購物車
          *
          * @param int $id 商品的編號
          * @param string $name 商品名稱
          * @param int $price 商品單價
          * @param int $count 商品數量
          * @return 如果商品存在，則在原來的數量上加$count 若原先不存在則新增
          */
        public function addItem($id,$name,$price,$count) {
            $this->itemArray = $this->itemList(); // 把資料讀取並寫入陣列
            if ($this->checkItem($id)) { // 檢測商品是否存在
                $this->updateItem($id,$count,0); // 原本的商品數量加$count
                $this->save();
            }else{
                $this->itemArray[0][$id] = $id;
                $this->itemArray[1][$id] = $name;
                $this->itemArray[2][$id] = $price;
                $this->itemArray[3][$id] = $count;
                $this->save();
            }
        }

        /**
          * 從購物車減少商品
          *
          * @param int $id 商品的編號
          * @param int $count 商品數量
          * @return 如果商品存在，則在原來的數量上減上$count 如果不存在回傳false
          */
        public function subItem($id,$count) {
            $this->itemArray = $this->itemList(); // 把資料讀取並寫入陣列
            if ($this->checkItem($id)) { // 檢測商品是否存在
                $this->updateItem($id,$count,1); // 原本的商品數量減$count
            }else{
                return false;
            }
        }

        /**
          * 檢查商品是否存在在購物車內
          *
          * @param int $id
          * @return bool 如果商品存在則回傳 true 否則回傳 false
          */
        private function checkItem($id) {
            $array = $this->itemArray;
            foreach ($array[0] as $item) {
                

                if ($item == $id){
                    return true;
                } 
            }
            return false;
        }


        /**
          * 更新商品數量
          *
          * @param int $id 商品編號
          * @param int $count 商品數量
          * @param int $status 修改型別 0：加 1:減 2:刪除
          */
        public function updateItem($id, $count, $status = "") {
            $this->itemArray = $this->itemList(); // 把資料讀取並寫入陣列
            $array = $this->itemArray;

            foreach ($array[0] as $item) {
                if ($item == $id) {
                    switch ($status) {
                        case 0: // 新增數量 一般$count為1
                            $array[3][$id] += $count;
                            break;
                        case 1: // 減少數量 若低於等於0則移除
                            $array[3][$id] -= $count;
                            if($array[3][$id] <= 0){
                                unset($array[0][$id]);
                                unset($array[1][$id]);
                                unset($array[2][$id]);
                                unset($array[3][$id]);
                            }
                            break;

                        case 2: // 刪除商品
                            unset($array[0][$id]);
                            unset($array[1][$id]);
                            unset($array[2][$id]);
                            unset($array[3][$id]);
                        break;
                        default:
                            break;
                    }
                }
            }
            $this->itemArray = $array;
            $this->save();
        }

        /**
          * 檢視購物車內容物
          *
          * @return array 返回一個二維陣列
          */
        public function itemList() {
            $cookie = stripslashes($_COOKIE['Cart']); //取出之前存在cookie的資料
            if (!isset($cookie)){ //如果之前還沒有儲存過則回傳false
                return false;
            } 
            $data = unserialize($cookie); //取出資料並回傳
            return $data;
        }

        /**
          * 儲存商品到購物車
          *
          */
        public function save() {

            $data = serialize($this->itemArray);
            // ob_start();
            setcookie('Cart',$data,time()+86400,"/");//86400是cookie過期時間(秒)
            $cookie = stripslashes($_COOKIE['Cart']); //取出之前存在cookie的資料
        }

        /**
          * 移除商品
          *
          * @param int $id 商品的編號
          * @return 如果商品存在，則移除
          */
        public function removeItem($id) {
            $this->itemArray = $this->itemList(); // 把資料讀取並寫入陣列
            if ($this->checkItem($id)) { // 檢測商品是否存在
                $this->updateItem($id,0,2); // 移除商品
            }
            $this->save();
        }

        /**
          * 取得購物車總共價格
          *
          * @return int 返回購物車總共價格
          */
        public function sum() {
            $array = $this->itemArray = $this->itemList();
            $total = 0;
            if (is_array($array[0])) {
                foreach ($array[0] as $key=>$val) {
                    $total += $array[2][$key] * $array[3][$key];
                }
            }
          return $total;
        }

        /**
          * 取得購物車內項目清單列表(顯示品名、數量、單價、總價格)
          *
          * @return cartArray 返回一個一維陣列 $cartArray[0]:品名 $cartArray[1]:單價  $cartArray[2]:數量 $cartArray[3]:總價格(該品項) $cartArray[3]:總價格(全品項)
          */
        public function cartList() {
            $array = $this->itemArray = $this->itemList();
            $cartArray = array(); //一維陣列
            $count = 0;
            $total = 0;
            if (is_array($array[0])) {
                foreach ($array[0] as $key=>$val) {
                    $cartArray[0] = $array[1][$key];
                    $cartArray[1] = $array[2][$key];
                    $cartArray[2] = $array[3][$key];
                    $cartArray[3] = $array[2][$key] * $array[3][$key];

                    echo "<br>name    ->".$cartArray[0]."<br>";
                    echo "price   ->".$cartArray[1]."<br>";
                    echo "number  ->".$cartArray[2]."<br>";
                    echo "sum     ->".$cartArray[3]."<br>";
                    
                }
                $cartArray[4] = $this->sum();
                echo "total    ->".$cartArray[4]."<br><br>";
            }
            return $cartArray;
        }
    }

    
    
?>