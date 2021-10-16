<?php 

class Application {
    protected $controller = "HomeController";
    protected $action = "IndexAction";
    protected $parameters = array();


    public function __construct()
    {
        $this->ParseURL(); 
        //Eğer Controller dosyası varsa dosyayı dahil et
        if(file_exists(CONTROLLER.$this->controller.".php")){
            require_once (CONTROLLER.$this->controller.".php"); 
            //Dahil edilen Controller sınıfından yeni bir controller sınıfı oluştur.
            $this->controller = new $this->controller;
            //Oluşturulan Controller içerisinde Action varsa Action çağır.
            if(method_exists($this->controller, $this->action)){
                call_user_func_array([$this->controller, $this->action], $this->parameters);
            }else{
                echo "There is no such Action.";
            }
        }else{
                echo "There is no such Controller";
        }
    }

    /**
     * ParseURL methodu genel mantığı şu işlemleri yapar;
     *
     * $_SERVER["REQUEST_URI"] yardımı ile istemci tarafından gönderilen Url yakalanır.
     * 
     * trim() fonksitonu ile URL sonunda eğer varsa "/" karakteri temizlenir.
     * 
     * explode() fonksiyonu ile URL "/" karakterine göre dizinleştirilir.
     * 
     * $url değişkeni bir dizi olur. [0] => Controller Adı, [1] => Action Adı, [2] ve sonrası => parametreler.
     * 
     * unset() fonksiyonu ike $url değişkeninde varsa [0] ve [1] indis numaralı elemanlar temizlenir.
     * 
     * Geriye kalan değerler parametrelerdir.
     */


    protected function ParseURL(){
        $request = trim($_SERVER["REQUEST_URI"],"/");
        if(!empty($request)){
            $url = explode("/", $request);
            $this->controller = isset($url[0]) ? $url[0]."Controller" : "HomeController";
            $this->action = isset($url[1]) ? $url[1]."Action" : "IndexAction";
            $this->parameters = !empty($url) ? array_values($url) : array();
        } else{
            $this->controller = "HomeController";
            $this->action = "IndexAction";
            $this->parameters = array();
        }
    }
}




?>