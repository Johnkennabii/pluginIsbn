<!doctype html>
    <html lang="fr">
        <head>
        <meta charset="utf-8">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <title>recherche par ISBN</title>
        </head>
    <body>
        <br>
        <form method="POST" action="#">
            <input class="input" type="text" name="isbn" placeholder="ISBN"> 
            <input  class="btn btn-primary "type="submit" name="" > v3
        </form>
 


<?php

$url = "https://www.googleapis.com/books/v1/volumes?q=ISBN:";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $isbn = $_POST['isbn'];
  if (empty($isbn)) {
    $isbn="";
    echo "ISBN is empty";
  } else {
   $fullurl = $url.$isbn;
    echo $fullurl ;
$ch = curl_init();
// IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
// in most cases, you should set it to true
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $fullurl);
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);

$titre = $obj->items[0]->volumeInfo->title ;
$auteur = $obj->items[0]->volumeInfo->authors[0] ;
$publisher = $obj->items[0]->volumeInfo->publisher ;
$publishdate = $obj->items[0]->volumeInfo->publishedDate ;

$nbpage= $obj->items[0]->volumeInfo->pageCount ;
$isbn1 =   $obj->items[0]->volumeInfo->industryIdentifiers[0]->identifier ; 
$isbn2 =   $obj->items[0]->volumeInfo->industryIdentifiers[0]->identifier ;  
$isbnname1 =  $obj->items[0]->volumeInfo->industryIdentifiers[0]->type; 
$isbnname2 =  $obj->items[0]->volumeInfo->industryIdentifiers[1]->type;


$cover = $obj->items[0]->volumeInfo->imageLinks->thumbnail ;
$cover= str_replace("http", "https", $cover);

 // $cover= str_replace("zoom=1", "zoom=2", $cover);
echo " <br> titre :".$titre;
echo " <br> auteur :".$auteur;
echo " <br> Editeur :".$publisher;
echo " <br> Date de publication :".$publishdate; 
echo " <br> nombre de pages :".$nbpage;
echo "<br> " .$isbnname1. " : ".$isbn1. "  || ". $isbnname2 . " : ".$isbn2   ;
echo  "<br>".$cover;
echo " <br> <img src=".$cover.">";

echo '
<form method="POST" action="#">
<div class="form-check">
  <input class="form-check-input" type="checkbox"  name="addbook" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Ajouter Livre aux Produits
  </label>
</div>
<input class="btn btn-primary" type="submit" name="" value ="Valider">
</form>';
  }
}


if (isset($_GET['addbook'])) {

   

function my_create_woo_product( $data = null ) {
    $post_args = array(
        'post_author' => intval( $data['author_id'] ), // The user's ID
        'post_title' => sanitize_text_field( $data['title'] ), // The product's Title
        'post_type' => 'product',
        'post_status' => 'publish' // This could also be $data['status'];
    );

    $post_id = wp_insert_post( $post_args );
   
    // If the post was created okay, let's try update the WooCommerce values.
    if ( ! empty( $post_id ) && function_exists( 'wc_get_product' ) ) {
        $product = wc_get_product( $post_id );
        $product->set_sku( 'pre-' . $post_id ); // Generate a SKU with a prefix. (i.e. 'pre-123') 
        $product->set_regular_price( '20.55' ); // Be sure to use the correct decimal price.
        $product->set_category_ids( array( 16, 17 ) ); // Set multiple category ID's.
        $product->save(); // Save/update the WooCommerce order object.
        echo 'produit ajoute';
    }
}

  } 

?>


    </body>
</html>