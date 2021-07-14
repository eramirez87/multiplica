<?php
class Connect{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'multiplica';
    private $myConnect;

    public function createConnect(){
        $this->myConnect = new mysqli($this->host,$this->user,$this->pass,$this->dbname);
    }

    public function getUser($u,$p){
        $sql = "SELECT * FROM user WHERE username='{$u}' AND password='{$p}';";
        $q = $this->myConnect->query($sql);
        return $q->fetch_object();
    }
    public function insert($sql){
        $this->myConnect->query($sql);
        return $this->myConnect->insert_id;
    }
    public function update($sql){
        return $this->myConnect->query($sql);
    }
    public function get_all($page,$items=6){
        $sql = "SELECT * FROM color";
        if( $page != false ){
            $from = ($page-1) * $items ;
            $sql .= " LIMIT {$from}, {$items}";
        }
        $q = $this->myConnect->query($sql);
        $odata = $this->getOtherData($items);
        $r['response'] = $q->fetch_all(MYSQLI_ASSOC);
        $r['query'] = [
            'num_items' => $items,
            'page' => $page,
            'num_pages'=>$odata->pages,
            'total_elems'=>$odata->total
        ];
        return $r;
    }
    public function get_color($id){
        $sql = "SELECT * FROM color where id = {$id}";
        $q = $this->myConnect->query($sql);
        $odata = $this->getOtherData(1);
        $r['response'] = $q->fetch_object();
        $r['query'] = [
            'num_items' => null,
            'page' => null, 
            'num_pages'=>null,
            'total_elems'=>$odata->total
        ];
        return $r;
    }

    private function getOtherData($items=false){
        $sql = "SELECT COUNT(*) total, ROUND( COUNT(*) / {$items} ) pages FROM color";
        $q = $this->myConnect->query($sql);
        $r = $q->fetch_object();
        return $r;
    }

}

/**
 * Otra clase
 */
class Colores extends Connect{

    public $user;
    public $userType = 'U';
    public $pass;
    public $method;

    private $pageItems = 6;

    public function __construct($user,$pass,$method){
        parent::createConnect();
        $this->method = ( isset($method) ) ? $method : false;
        $this->user = ( isset($user) ) ? $user : false;
        $this->pass = ( isset($pass) ) ? $pass : false;
        if( !$this->checkUser() ){
            echo json_encode(['success'=>false,'error'=>'Not authorized']);
            return;
        }
        switch($method){
            case 'GET':
                $page = (isset($_GET['page'])) ? $_GET['page'] : false;
                $items = (isset($_GET['items'])) ? $_GET['items'] : false;
                $id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : false;
                $type = ( isset( $_GET['type'] ) ) ? $_GET['type'] : 'JSON';
                $this->{"method_".$method}($id,$page,$items,$type);
                break;
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
                if( isset( $_GET['id'] ) ){
                    $this->{"method_".$method}($_GET['id']);
                }else{
                    echo json_encode(['success'=>false,'error'=>'Data missed']);
                }
                break;
            case 'POST':
                $this->{"method_".$method}();
                break;
        }
    }

    private function checkUser(){
        $u = $this->getUser($this->user,$this->pass);
        if( isset($u->create_time) ){
            $this->userType = $u->usertype;
            return true;
        }
        return false;
    }

    public function method_GET($id=false,$page=false,$items=false,$type=false){
        $items = ( $items == false ) ? $this->pageItems : $items;
        if($id == false){
            //echo json_encode(parent::get_all($page,$items),JSON_PRETTY_PRINT);
            $this->response(parent::get_all($page,$items),$type);
            return;
        }
        //echo json_encode(parent::get_color($id),JSON_PRETTY_PRINT);
        $this->response(parent::get_color($id),$type);
    }

    public function method_POST(){
        if( $this->userType != 'A' ){
            echo json_encode(['success'=>false,'error'=>'Not privilegies']);
            return;
        }
        $fieldcheck = ['name','color','pantone','year'];
        foreach($fieldcheck as $field){
            if( isset($_POST[$field]) ){
                $fields[] = "{$field}";
                $values[] = "'".$_POST[$field]."'";
            }else{
                echo json_encode(['success'=>false,'error'=>'Missed data']);
                return;
            }
        }
        
        $ifields = implode(',',$fields);
        $ivalues = implode(',',$values);
        $sql = "INSERT INTO color({$ifields}) VALUES($ivalues)";
        $lid = parent::insert($sql);
        echo json_encode(['success'=>true,'id'=>$lid]);
        return;
    }

    public function method_PUT($id){
        if( $this->userType != 'A' ){
            echo json_encode(['success'=>false,'error'=>'Not privilegies']);
            return;
        }
        $fieldcheck = ['name','color','pantone','year'];
        $putData  = fopen("php://input", "r");
        $post = $this->urldecode(stream_get_contents($putData));
        $fv = [];
        foreach($fieldcheck as $field){
            if( isset($post[$field]) ){
                $fv[] = "{$field} = " . "'".$post[$field]."'" ;
            }
        }
        
        $ifv = implode(', ',$fv);
        $sql = "UPDATE color SET {$ifv} WHERE id = {$id}";
        $lid = parent::update($sql);
        echo json_encode(['success'=>true,'id'=>$id]);
        return;
    }

    public function method_PATCH($id){
        return $this->method_PUT($id);
    }

    public function method_DELETE($id){
        if( $this->userType != 'A' ){
            echo json_encode(['success'=>false,'error'=>'Not privilegies']);
            return;
        }
        $sql = "DELETE FROM color WHERE id = {$id}";
        $lid = parent::update($sql);
        echo json_encode(['success'=>true,'deleted_id'=>$id]);
        return;
    }

    public function response($data,$type='JSON'){
        switch($type){
            case 'XML':
                $xml = new SimpleXMLElement('<api/>');
                $response = $xml->addChild('response');

                $response->addAttribute('num_items',$data['query']['num_items']);
                $xml->addAttribute('num_pages',$data['query']['num_pages']);
                $xml->addAttribute('total_elems',$data['query']['total_elems']);
                $response->addAttribute('page',$data['query']['page']);

                if( is_array($data['response'])){
                    foreach($data['response'] as $arr){
                        $color = $response->addChild('color');
                        $color->addChild('id',$arr['id']);
                        $color->addChild('name',$arr['name']);
                        $color->addChild('color',$arr['color']);
                        $color->addChild('pantone',$arr['pantone']);
                        $color->addChild('year',$arr['year']);
                    }
                }
                if( is_object($data['response'])){
                    $arr = $data['response'];
                    $color = $response->addChild('color');
                    $color->addChild('id',$arr->id);
                    $color->addChild('name',$arr->name);
                    $color->addChild('color',$arr->color);
                    $color->addChild('pantone',$arr->pantone);
                    $color->addChild('year',$arr->year);
                }

                $query = $xml->addChild('query');
                print $xml->asXML();
                break;
            case 'JSON':
            default:
                echo json_encode( $data, JSON_PRETTY_PRINT );
                break;
        }
        return;
    }

    private function urldecode($str){
        $aux = explode('&',$str);
        $r=[];
        foreach($aux as $var){
            $var = urldecode($var);
            $var = explode("=",$var);
            $r[$var[0]] = $var[1];
        }
        return($r);
    }
}