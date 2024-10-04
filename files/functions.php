
<?php

require_once 'Zebra_Image.php';

if (session_status() == PHP_SESSION_NONE){
    session_start();

}

define('BASE_URL','http://localhost');

$conn = new mysqli ("localhost", "root","","shop");

function fake_products_generator()
{
    return;
    $names = [
        'WiWi Bamboo Soft Pajamas Sets for Women Long Sleeve Sleepwear Loose Comfy Pjs Set with Pants Plus Size',
        'COOFANDY Mens Casual Button Down Shirt Long Sleeve Untucked Shirt Stretch Denim Dress Shirt with Pockets',
        'COOFANDY Mens Wrinkle Free Shirt Casual Button Down Long Sleeve Dress Shirts',
        'Mens Wrinkle Free Shirt Casual Button Down Long Sleeve Dress '
    ];
    $desc = "Lorem ipsum dolor sit, amet consectetur adipisicing elit. A quo doloremque ea temporibus quae consequuntur sapiente minima voluptate sit commodi. Minima, dolore voluptatibus nesciunt quia temporibus voluptatem! Nisi, atque provident!";

    $photos = [];
    for ($i=1; $i <20 ; $i++) { 
        $pic["thumb"] = $i .".jpg";
        $pic["src"] = $i .".jpg";
        $photos[] = $pic;
    }
    $categories = [3,4,5];

for ($i=0; $i < 20; $i++) { 
    shuffle($names);
    shuffle($photos);
    shuffle($categories);
    $pro["name"] = $names[1];
    $pro["buying_price"] = rand(1000,50000);
    $pro["price"] = rand(1000,50000);
    $pro["description"] = $desc;
    $pro["photos"] = json_encode($photos);
    $pro["category_id"] = $categories[0];
    $pro["user_id"] = 1;
    db_insert("products",$pro);
}
}

function get_product($id){
    $sql = "SELECT * FROM products WHERE id = $id ";
    global $conn;
    $data["pro"] = $conn->query($sql)->fetch_assoc();
    $data["cat"] = null;

    if ($data["pro"] != null) {
        $cat_id = $data["pro"] ["category_id"];
        $sql = "SELECT * FROM categories WHERE id = $cat_id";
        $data["cat"] = $conn->query($sql)->fetch_assoc();
    }
    return $data;
}

function get_product_photos($json)
{
    $img["src"] = "assets/no_image.jpg";
    $img["thumb"] = "assets/no_image.jpg";
    $photos[] = $img;

    if ($json == null) {
        return $photos;
    }
    if (strlen($json) < 4) {
        return $photos;
    }
    $_objects = json_decode($json);

    $objects = [];
    $i = 0;
    foreach ($_objects as $key => $value) {
        if ($i>4) {
            break;
        }
        $i++;
        $objects[] = $value;
    }


    if (empty($objects)) {
        return $photos;
    }

    return $objects;

}

function get_product_thumb($json)
{
    $img = "assets/no_image.jpg";
    if ($json == null) {
        return $img;
    }
    if (strlen($img) < 4) {
        return $img;
    }
    $objects = json_decode($json);
    if (empty($objects)) {
        return $img;
    }
    if (!isset($objects[0]->thumb)) {
        return $img;
    }

    return $objects[0]->thumb;

}

function db_select($table,$condition = null)
{
    $sql = "SELECT * FROM $table";
    if ($condition != null) {
        $sql .= " WHERE $condition";
    }
    global $conn;
    $res = $conn->query($sql);
    $rows = [];
    while ($row = $res->fetch_assoc()){
        $rows[] = $row;
    }
    return $rows;
}

function db_insert($table_name,$data){
    $sql = "INSERT INTO $table_name";

    $column_names="(";
    $column_values = "(";
    echo "<pre>";

    $is_first = true;
    global  $conn;

    foreach ($data as $key => $value) {
        if ($is_first) {
            $is_first = false;
        }else{
            $column_names .=",";
            $column_values .= ",";
        }
        $column_names .= $key;
        $gettype = gettype($value);
        if ($gettype == 'string') {
            $value = $conn->real_escape_string($value);
            $column_values .= "'$value'";
        }else{
            $value = $conn->real_escape_string($value);
            $column_values .= $value;
        }
        
        
    }
    $column_names .=")";
    $column_values .= ")";
    $sql .= $column_names." VALUES".$column_values;

    if ($conn ->query($sql)) {
        return true;
    }else{
        return false;
    }

}

function create_thumb($source,$target ){

    $image = new Zebra_Image();
    $image -> auto_handle_exif_orientation = true;
    $image -> source_path = $source;
    $image -> target_path = $target;
    $image -> preserve_aspect_ratio = true;
    $image -> enlarge_smaller_images = true;
    $image -> preserve_time = true;

    $image->jpeg_quality = get_jpeg_quality(filesize($image -> source_path));

    $width = 1000;
    $height = 1000;

    if (!$image->resize($width, $height, ZEBRA_IMAGE_CROP_CENTER)) {
        return $image->source_path;
    } else {
        return $image->target_path;
    }
    
}

function get_jpeg_quality($_size){
    $size = ($_size /1000000);

    $qt = 50;
    if($size > 5){
        $qt = 10;
    }else if($size > 4){
        $qt = 13;
    }
    else if($size > 2){
        $qt = 15;
    }
    else if($size > 1){
        $qt = 17;
    }
    else if($size > 0.8){
        $qt = 50;
    }
    else if($size > .5){
        $qt = 80;
    }else{
        $qt = 90;
    }
    return $qt;
}

function upload_images($files)
{
ini_set('memory_limit','512M');
if ($files == null || empty($files)){
    return [];
}
$uploaded_images = array();

foreach ($files as $file){
    if(
        isset($file['name']) &&
        isset($file['type']) &&
        isset($file['tmp_name']) &&
        isset($file['error']) &&
        isset($file['size'])
    ){
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name= time() . "-" . rand(100000,100000) . "." . $ext;
        $destination = "uploads/" . $file_name;
        $thumb_destination = "uploads/thumb_" . $file_name;

        $res = move_uploaded_file($file["tmp_name"], $destination);
        if(!$res){
            continue;
        }
        
        $thumb_destination = create_thumb($destination,$thumb_destination);
        $img['src'] = $destination;
        $img['thumb'] = $thumb_destination;
        $uploaded_images[] = $img;

    }   
}
return $uploaded_images;
}


function url($path = "/"){
     return BASE_URL . $path;
}

function protected_area(){
    if(!isset($_SESSION['user'])){
    alert('warning','Jogosulatlan hozzáférés, a folytatás előtt jelentkezzen be.');
    header('Location: login.php');
    die();
    }


}

 function logout(){
    if(isset($_SESSION["user"])){
        unset($_SESSION['user']);
    }
        alert('success','Sikeres kijelentkezés');
        header('Location: login.php');
        die();
 }

 function is_logged_in(){
    if(isset($_SESSION["user"])){
        return true;
    } else{
        return false;
    }
 }

function alert($type,$message){
    $_SESSION['alert'] ['type'] = $type;
    $_SESSION['alert'] ['message'] = $message;
}

function login_user($email,$password)
{

    global $conn;
    $sql = "SELECT * FROM users WHERE email = '{$email}'";
    $res = $conn->query($sql);

    if ($res->num_rows < 1)
    {
        return false;
    }
    $row = $res->fetch_assoc();

    if (!password_verify($password,$row['password'])) {
        return false;
    }


    $_SESSION["user"] = $row;

    return true;
}

function text_input($data)
{
    $name = (isset($data['name'])) ? $data['name'] : "";
    $attributes = (isset($data['attributes'])) ? $data['attributes'] : "";

    $value = "";
    $error = "";
    $error_text = "";
    if (isset($_SESSION['form'])){
        if(isset($_SESSION['form'] ['value'])){
            if(isset($_SESSION['form'] ['value'] [$name])){
                $value = $_SESSION['form'] ['value'] [$name];
            }

        }
    }

    if (isset($_SESSION['form'])){
        if(isset($_SESSION['form'] ['error'])){
            if(isset($_SESSION['form'] ['error'] [$name])){
                $error = $_SESSION['form'] ['error'] [$name];
                $error_text ='<div class="form-text text-danger">'.$error.'</div>';
            }

        }
    }
    
    $label = (isset($data['label'])) ? $data['label'] : $name;
    $value = (isset($data['value'])) ? $data['value'] : $value;
    $error = (isset($data['error'])) ? $data['error'] : $error;
    return 
    '<label class="form-label text-capitalize" for="'.$name.'">'. $label .'</label>
    <input name="'.$name.'" value="'.$value.'" class="form-control" type="text" id="'.$name.'" placeholder="'.$label.'" '.$attributes.'>' 
    . $error_text;

}

function select_input($data, $options)
{
    $name = (isset($data['name'])) ? $data['name'] : "";
    $attributes = (isset($data['attributes'])) ? $data['attributes'] : "";

    $value = "";
    $error = "";
    $error_text = "";
    if (isset($_SESSION['form'])){
        if(isset($_SESSION['form']['value'])){
            if(isset($_SESSION['form']['value'][$name])){
                $value = $_SESSION['form']['value'][$name];
            }
        }
    }

    if (isset($_SESSION['form'])){
        if(isset($_SESSION['form']['error'])){
            if(isset($_SESSION['form']['error'][$name])){
                $error = $_SESSION['form']['error'][$name];
                $error_text = '<div class="form-text text-danger">'.$error.'</div>';
            }
        }
    }
    
    $label = (isset($data['label'])) ? $data['label'] : $name;
    $value = (isset($data['value'])) ? $data['value'] : $value;

    $select_options = "";
    $selected = "";
    foreach ($options as $key => $val) {
        $selected = "";
        if ($key == $value) {
            $selected = "selected";
        }
        $select_options .= '<option value="' .$key.'"'.$selected.'>'.$val.'</option>';
    }

    $select_tag = '<select name="'.$name.'" class="form-select" id="'.$name.'" '.$attributes.'>'.$select_options.'</select>';

    return 
    '<label class="form-label text-capitalize" for="'.$name.'">'. $label .'</label>'
    .$select_tag
    . $error_text;
}

function product_item_ui_1($pro)
{
    $thumb = get_product_thumb($pro["photos"]);
    $str = <<<EOF

                  <div class="col-md-4 col-sm-6 px-2 mb-4">
                <div class="card product-card">
                  <button class="btn-wishlist btn-sm" type="button" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to wishlist"><i class="ci-heart"></i></button><a class="card-img-top d-block overflow-hidden" href="product.php?id={$pro["id"]}">
                    <img src="{$thumb}" alt="Product"></a>
                  <div class="card-body py-2"><a class="product-meta d-block fs-xs pb-1" href="javascript:;">Sneakers &amp; Keds</a>
                    <h3 class="product-title fs-sm"><a href="product.php?id={$pro["id"]}">{$pro["name"]}</a></h3>
                    <div class="d-flex justify-content-between">
                      <div class="product-price"><span class="text-accent">{$pro["price"]}.<small>00</small></span></div>
                      <div class="star-rating"><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body card-body-hidden">
                    <div class="text-center pb-2">
                      <div class="form-check form-option form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="size1" id="s-75">
                        <label class="form-option-label" for="s-75">7.5</label>
                      </div>
                      <div class="form-check form-option form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="size1" id="s-80" checked>
                        <label class="form-option-label" for="s-80">8</label>
                      </div>
                      <div class="form-check form-option form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="size1" id="s-85">
                        <label class="form-option-label" for="s-85">8.5</label>
                      </div>
                      <div class="form-check form-option form-check-inline mb-2">
                        <input class="form-check-input" type="radio" name="size1" id="s-90">
                        <label class="form-option-label" for="s-90">9</label>
                      </div>
                    </div>
                    <button class="btn btn-primary btn-sm d-block w-100 mb-2" type="button"><i class="ci-cart fs-sm me-1"></i>Add to Cart</button>
                    <div class="text-center"><a class="nav-link-style fs-ms" href="#quick-view" data-bs-toggle="modal"><i class="ci-eye align-middle me-1"></i>Quick view</a></div>
                  </div>
                </div>
                <hr class="d-sm-none">
              </div>

    EOF;
    return $str;
}