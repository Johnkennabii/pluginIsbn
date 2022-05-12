<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>ISBN Scan</title>
</head>

<body class="p-5">
    <form class="row g-3" method="POST" action="#">
        <div class="col-6">
            <label for="inputIsbn" class="form-label">ISBN :</label>
            <input type="text" class="form-control" id="isbn" placeholder="Scannez ISBN ..">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Vérifier</button>
        </div>
    </form>

    <?php

$url = "https://www.googleapis.com/books/v1/volumes?q=ISBN:";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // collect value of input field
    $isbn = $_POST['isbn'];

    if (empty($isbn)){
      echo "ISBN non renseigné";
      return;
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
        
    $id = $obj->items[0]->id ;
    $titre = $obj->items[0]->volumeInfo->title ;
    $description = $obj->items[0]->volumeInfo->description ;
    $auteur = $obj->items[0]->volumeInfo->authors[0] ;
    $publisher = $obj->items[0]->volumeInfo->publisher ;
    $publishdate = $obj->items[0]->volumeInfo->publishedDate ;
    $categories = $obj->items[0]->volumeInfo->categories[0] ;
    $nbpage= $obj->items[0]->volumeInfo->pageCount ;
    $isbn1 = $obj->items[0]->volumeInfo->industryIdentifiers[0]->identifier ; 
    $isbn2 = $obj->items[0]->volumeInfo->industryIdentifiers[0]->identifier ;  
    $isbnname1 = $obj->items[0]->volumeInfo->industryIdentifiers[0]->type ; 
    $isbnname2 = $obj->items[0]->volumeInfo->industryIdentifiers[1]->type ;
    $cover = $obj->items[0]->volumeInfo->imageLinks->thumbnail ;
    $cover= str_replace("http", "https", $cover);
    // $cover= str_replace("zoom=1", "zoom=2", $cover);

        echo '<table class="table table-striped">
        <thead>
          <tr>
            <th scope="col"># iD</th>
            <th scope="col">Auteur</th>
            <th scope="col">Titre</th>
            <th scope="col">Description</th>
            <th scope="col">Editeur</th>
            <th scope="col">Date de publ.</th>
            <th scope="col">Catégorie</th>
            <th scope="col">Nbre de page</th>
            <th scope="col">'.$isbnname1.'</th>
            <th scope="col">'.$isbnname2.'</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">'.$id.'</th>
            <td>'.$auteur.'</td>
            <td>'.$titre.'</td>
            <td>'.$description.'</td>
            <td>'.$publisher.'</td>
            <td>'.$publishdate.'</td>
            <td>'.$categories.'</td>
            <td>'.$nbpage.'</td>
            <td><img class="p-2" src='.$cover.'></td>
          </tr>
        </tbody>
        </table>
        '

  
    echo '<form method="POST" action="#">
        <div class="form-check">
        <input class="form-check-input" type="checkbox"  name="addbook" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">Ajouter Livre aux Produits</label>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Ajouter</button>
        </div>
        </form>'
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
                
                echo '<div class="alert alert-success" role="alert">
                Le livre '.$titre.' a été ajouté avec succès !
                </div>;'
            }
        }
      } 
?>
</body>

</html>
