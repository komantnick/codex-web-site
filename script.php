<?php
require_once __DIR__ . '/vendor/autoload.php';
use Google\Cloud\Core\ServiceBuilder;

set_error_handler('exceptions_error_handler');
function exceptions_error_handler($severity, $message, $filename, $lineno)
{
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
#upload a photo by random address to google cloud services
function upload($image_info)
{
    $cloud = new ServiceBuilder([
    'keyFilePath' => #'creditals-path'//__DIR__ .'/codex-site-komantnick-5264d6f5074b.json',
    'projectId' =>'codex-site-komantnick'
]);
    $storage = $cloud->storage();
    $bucket = $storage->bucket('codex-storage-by-komantnick');
    $object = $bucket->object('image.jpg');
    $path_parts = pathinfo($image_info['name']);
    $extension=$path_parts['extension'];
    $name_file=$image_info['tmp_name'];
    $filePath=$name_file;
    $objectName=generateRandomString().".".$extension;
    echo $objectName."<br>";
    $object = $bucket->upload(file_get_contents($filePath), [
    'name' => $objectName,
    'predefinedAcl' => 'publicRead'
]);
}
//Функции обработки

  function cropImage($image_path, $x, $y, $cropWidth, $cropHeight)
  {
      list($width, $height) = getimagesize($image_path);
      $file = @file_get_contents($image_path);
      $file = imagecreatefromstring($file);
      $file_new = imagecrop($file, ['x' => $x, 'y' => $y, 'width' => min($cropWidth, $width), 'height' => min($cropHeight, $height)]);
      $image_path=pathinfo($image_path);
      $path='cache/'.$image_path['filename']."&cw=".$cropWidth."&ch=".$cropHeight.".".$image_path['extension'];
      imagejpeg($file_new, $path);
      header("Location: $path");
  }
  
  function resizeImage($image_path, $resizeWidth, $resizeHeight)
  {
      #$img = $image_create_func($originalFile);
      list($width, $height) = getimagesize($image_path);
      $file = @file_get_contents($image_path);
      $file = imagecreatefromstring($file);
      #$resizeHeight = ($height / $width) * $resizeWidth;
      $tmp = imagecreatetruecolor($resizeWidth, $resizeHeight);
      imagecopyresampled($tmp, $file, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $width, $height);
      $image_path=pathinfo($image_path);
      $path='cache/'.$image_path['filename']."&rw=".$resizeWidth."&rh=".$resizeHeight.".".$image_path['extension'];
      imagejpeg($tmp, $path);
      header("Location: $path");
  }
#getting info about post
if (isset($_FILES)) {
    try {
        $z=$_FILES['image'];
        upload($z);
        echo "Файл залит!";
    } catch (Exception $e) {
        echo "Файл не залит через форму!";
        echo "<a href='index.html'>"."Вернитесь"."</a>";
    }
}
#getting info about get
if (isset($_GET['address'])) {
    $address = $_GET['address'];
    $url = "https://storage.googleapis.com/codex-storage-by-komantnick/$address";
    $data = @file_get_contents($url);
    $image_path='cache/'.$address;
    file_put_contents("$image_path", $data);
    if (isset($_GET['crop'])) {
        $image_path='cache/'.$_GET['address'];
        $pieces = explode("x", $_GET['crop']);
        $cropWidth=(int)$pieces[0];
        $cropHeight=(int)$pieces[1];
        #$data = @file_get_contents($image_path);
        cropImage($image_path, 0, 0, $cropWidth, $cropHeight);
    } elseif (isset($_GET['resize'])) {
        $image_path='cache/'.$_GET['address'];
        $pieces = explode("x", $_GET['resize']);
        $resizeWidth=(int)$pieces[0];
        $resizeHeight=(int)$pieces[1];
        resizeImage($image_path, $resizeWidth, $resizeHeight);
    } else {
        header("Location: $image_path");
    }
}


?>
