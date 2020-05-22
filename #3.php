<?php
    header("Content-Type:text/html; charset=utf-8");
    $product = new Publish();
    $product->addCompany("test");
    $product->removeCompany("pchome");
    $product->publish();

    class Publish {

        public function __construct() {
            $this->companyArray = array();//假設這是從db撈出來的資料
            array_push($this->companyArray, "pchome");
            array_push($this->companyArray, "yahoo");
            array_push($this->companyArray, "ruten");
            array_push($this->companyArray, "shopee");
        }

        /**
          * 新增拍賣平台到發佈清單
          *
          * @param string $companyName 拍賣平台的名稱
          * @return 回傳已修改之一維陣列
          */
        public function addCompany($companyName) {
            if(!in_array($this->companyArray,$companyName)){
                array_push($this->companyArray, $companyName);
            }
            return $this->companyArray;
        }

        /**
          * 從發佈清單移除拍賣平台
          *
          * @param string $companyName 拍賣平台的名稱
          * @return 回傳已修改之一維陣列
          */
        public function removeCompany($companyName) {
            if (($key = array_search($companyName, $this->companyArray)) !== false) {
                unset($this->companyArray[$key]);
            }
            return $this->companyArray;
        }

        /**
          * 發佈推播 顯示該發送的平台資訊
          */
        public function publish() {
            foreach ($this->companyArray as $key => $value) {
                echo $value." 已收到商品發佈通知<br>";
            }
        }
    }
?>