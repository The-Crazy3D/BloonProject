<?php
/*
 * BloonCrypto
 * Habbo R63 Post-Shuffle
 * Based on the work of Burak, edited by BloonCrypto Git Community. (skype: burak.karamahmut)
 * 
 * https://github.com/BurakDev/BloonProject/tree/BloonCrypto
 * Thanks to https://github.com/krakjoe/pthreads/ for pthreads and class !
 */
class Async extends Thread {
    /**
    * Provide a passthrough to call_user_func_array
    **/
    public function __construct($method, $params){
        $this->method = $method;
        $this->params = $params;
        $this->result = null;
        $this->joined = false;
    }

    /**
    * The smallest thread in the world
    **/
    public function run(){
        if (($this->result=call_user_func_array($this->method, $this->params))) {
            return true;
        } else return false;
    }

    /**
    * Static method to create your threads from functions ...
    **/
    public static function call($method, $params){
        $thread = new Async($method, $params);
        if($thread->start()){
            return $thread;
        } /** else throw Nastyness **/
    }

    /**
    * Do whatever, result stored in $this->result, don't try to join twice
    **/
    public function __toString(){ 
        if(!$this->joined) {
            $this->joined = true;
            $this->join();
        }

        return $this->result;
    }
}
?>