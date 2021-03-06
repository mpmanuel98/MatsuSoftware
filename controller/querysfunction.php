<?php

//**********************************************************************OTHERS FUNCTIONS*************************************************************//
function replace($replace){
    return str_replace("'", "''", "$replace");
}

function rejectComment($mysqli, $id){
    $resultado = $mysqli->query("DELETE FROM comment WHERE idComment='".$id."'");
    return $resultado;
}

function validateComment($mysqli, $id){
    $resultado = NULL;

    $resultado1 = $mysqli->query("SELECT product.idProduct, sumRating, numComments FROM product inner join comment on product.idProduct = comment.idProduct WHERE comment.idComment = $id");
    $product = $resultado1->fetch_assoc();

    $resultado2 = $mysqli->query("SELECT rating FROM comment WHERE idComment='".$id."'");
    $rating = $resultado2->fetch_assoc();

    $resultado3 = $mysqli->query("SELECT validated FROM comment WHERE idComment='".$id."'");
    $validated = $resultado3->fetch_assoc();

    if($validated['validated'] == 0){
        $sumRating = $product['sumRating'] + $rating['rating'];
        $numComments = $product['numComments'] + 1;
        $idProduct = $product['idProduct'];

        $resultado4 = $mysqli->query("UPDATE product SET sumRating = '".$sumRating."', numComments = '".$numComments."' WHERE idProduct = '".$idProduct."'");

        if($resultado4){
            $resultado = $mysqli->query("UPDATE comment SET validated = 1 WHERE idComment='".$id."'");
        }
    }
    return $resultado;
}

function modifyComment($mysqli, $idUser, $opinion, $rating, $validated, $idProduct){
    $opinion = replace($opinion);
    $resultado = getComment($mysqli, $idUser, $idProduct);
    $resultado = $resultado->fetch_assoc();
    $oldRating = $resultado['rating'];

    $resultado2 = getProductUsingId($mysqli, $idProduct);
    $resultado2 = $resultado2->fetch_assoc();
    $oldNumComments = $resultado2['numComments'];
    $oldRatingProduct = $resultado2['sumRating'];

    $oldRatingProduct = $oldRatingProduct - $oldRating;
    $oldNumComments = $oldNumComments - 1;

    $resultado3 = $mysqli->query("UPDATE product SET sumRating = '".$oldRatingProduct."', numComments = '".$oldNumComments."' WHERE idProduct = '".$idProduct."' ");

    $resultado4 = $mysqli->query("UPDATE comment SET opinion = '".$opinion."', rating = '".$rating."', validated = '".$validated."' WHERE idProduct = '".$idProduct."' AND idUser = '".$idUser."' ");
    return $resultado4;
}

//**********************************************************************GETTERS*********************************************************************//
function getUsersUsingEmail($mysqli, $email){
    $resultado = $mysqli->query("SELECT * FROM user WHERE email = '".$email."'");
    return $resultado;
}

function getUsersUsingId($mysqli, $id){
    $resultado = $mysqli->query("SELECT * FROM user WHERE idUser= '".$id."'");
    return $resultado;
}

function getUsersUsingNick($mysqli, $nick){
    $resultado = $mysqli->query("SELECT * FROM user WHERE nick = '".$nick."'");
    return $resultado;
}

function getVideogames($mysqli){
    $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'game'");
    return $resultado;
}

function getVideogamesPag($mysqli, $pag){
    $num = ($pag-1) *6;
    $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'game' LIMIT $num, 6");
    return $resultado;
}

function getVideogamesTotal($mysqli){
    $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'game'");
    $resultado = $resultado->num_rows;
    return $resultado;
}

function getVideogamesUsingId($mysqli, $id){
    $resultado = $mysqli->query("SELECT * FROM product WHERE idProduct = $id");
    return $resultado;
}

function getMerchandising($mysqli, $kind){
    if($kind == "ALL"){
        $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'merchandising'");
    }else{
        $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'merchandising' AND kind = '".$kind."'");
    }
    
    return $resultado;
}

function getMerchandisingPag($mysqli, $kind, $pag){
    $num = ($pag-1) *6;
    if($kind == "ALL"){
        $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'merchandising' LIMIT $num, 6");
    }else{
        $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'merchandising' AND kind = '".$kind."' LIMIT $num, 6");
    }
    
    return $resultado;
}

function getMerchandisingTotal($mysqli, $kind){
    if($kind == "ALL"){
        $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'merchandising'");
    }else{
        $resultado = $mysqli->query("SELECT * FROM product WHERE category = 'merchandising' AND kind = '".$kind."'");
    }
    
    $resultado = $resultado->num_rows;
    return $resultado;
}

function getProduct($mysqli){
    $resultado = $mysqli->query("SELECT * FROM product");
    return $resultado;
}

function getProjects($mysqli){
    $resultado = $mysqli->query("SELECT * FROM project");
    return $resultado;
}

function getProjectsTotal($mysqli){
    $resultado = $mysqli->query("SELECT * FROM project");
    $resultado = $resultado->num_rows;
    return $resultado;
}

function getProductUsingId($mysqli, $id){
    $resultado = $mysqli->query("SELECT * FROM product WHERE idProduct = $id");
    return $resultado;
}

function getCategory($mysqli){
    $resultado = $mysqli->query("SELECT * FROM category");
    return $resultado;
}

function getEmails($mysqli){
    $resultado = $mysqli->query("SELECT email FROM user");
    return $resultado;
}

function getCommentsNotValidated($mysqli){
    $resultado = $mysqli->query("SELECT * FROM comment WHERE validated = 0");
    return $resultado;
}

function getUserOfComment($mysqli, $comment){
    $comment = replace($comment);
    $resultado = $mysqli->query("SELECT user.nick FROM user inner join comment on user.idUser = comment.idUser WHERE idComment = $comment");
    return $resultado;
}

function getProductOfComment($mysqli, $comment){
    $comment = replace($comment);
    $resultado = $mysqli->query("SELECT product.title FROM product inner join comment on product.idProduct = comment.idProduct WHERE idComment = $comment");
    return $resultado;
}

function getNewsletter($mysqli, $orden){
    $resultado = $mysqli->query("SELECT * FROM newsletter ORDER BY publishDate $orden");
    return $resultado;
}

function getShoppinghistory($mysqli, $id, $orden){
    $resultado = $mysqli->query("SELECT * FROM shoppinghistory WHERE idUser = $id ORDER BY purchaseDate $orden");
    return $resultado;
}

function getCommentsByProduct($mysqli, $idProduct){
    $resultado = $mysqli->query("SELECT * FROM comment WHERE idProduct = $idProduct AND validated = 1");
    return $resultado;
}

function getComment($mysqli, $idUser, $idProduct){
    $resultado = $mysqli->query("SELECT opinion, rating FROM comment WHERE idUser = $idUser AND idProduct = $idProduct");
    return $resultado;
}

//**********************************************************************SETTERS & INSERT*****************************************************************//
function setRequest($mysqli, $request, $id){
    $resultado = $mysqli->query("UPDATE user SET request = ".$request." WHERE idUser = '".$id."'");
    return $resultado;
}

function setToken($mysqli, $token, $id){
    $resultado = $mysqli->query("UPDATE user SET token = '".$token."' WHERE idUser = '".$id."'");
    return $resultado;
}

function setPassword($mysqli, $password, $id){
    $resultado = $mysqli->query("UPDATE user SET pass = '".$password."' WHERE idUser = '".$id."'");
    return $resultado;
}

function setRol($mysqli, $rol, $id){
    $resultado = $mysqli->query("UPDATE user SET rol = '".$rol."' WHERE idUser = '".$id."'");
    return $resultado;
}

function setNewInformation($mysqli, $id, $nick, $email, $provincia, $municipio, $direccion){
    $municipio = replace($municipio);
    $direccion = replace($direccion);
    $resultado = $mysqli->query("UPDATE user SET nick = '".$nick."', email = '".$email."', province = '".$provincia."', city= '".$municipio."', direction = '".$direccion."' WHERE idUser = '".$id."' ");
    return $resultado;
}

function setNewInformationWithPassword($mysqli, $id, $nick, $email, $pass, $provincia, $municipio, $direccion){
    $municipio = replace($municipio);
    $direccion = replace($direccion);
    $resultado = $mysqli->query("UPDATE user SET nick = '".$nick."', email = '".$email."', pass = '".$pass."', province = '".$provincia."', city= '".$municipio."', direction = '".$direccion."' WHERE idUser = '".$id."' ");
    return $resultado;
}

function setNewInformationToProduct($mysqli, $id, $title, $descrip, $price, $photoLink, $purchaseLink, $trailerLink, $requirements, $kind){
    $title = replace($title);
    $descrip = replace($descrip);
    $photoLink = replace($photoLink);
    $purchaseLink = replace($purchaseLink);
    $trailerLink = replace($trailerLink);
    $requirements = replace($requirements);
    $resultado = $mysqli->query("UPDATE product SET title = '".$title."', descrip = '".$descrip."', price = '".$price."', photoLink= '".$photoLink."', purchaseLink = '".$purchaseLink."', trailerLink = '".$trailerLink."', requirements = '".$requirements."', kind = '".$kind."' WHERE idProduct = '".$id."' ");
    return $resultado;
}

function setNewRatingInformationToProduct($mysqli, $idProduct, $numComments, $sumRating){
    $resultado = $mysqli->query("UPDATE product SET numComments = '".$numComments."', sumRating = '".$sumRating."' WHERE idProduct = '".$idProduct."' ");
    return $resultado;
}

function setShoppinghistory($mysqli, $idUser, $title, $price, $purchaseDate){
    $title = replace($title);
    $resultado = $mysqli->query("INSERT INTO shoppinghistory(idUser,title, price, purchasedate) VALUES ('".$idUser."', '".$title."', '".$price."', '".$purchaseDate."')");
    return $resultado;
}

function setShoppingrelations($mysqli, $idUser, $idProduct){
    $resultado = $mysqli->query("INSERT INTO shoppingrelations(idUser, idProduct) VALUES ('".$idUser."', '".$idProduct."')");
    return $resultado;
}

function insertToUsers($mysqli, $nick, $email, $contrasena, $provincia, $municipio, $direccion, $rol, $request){
    $municipio = replace($municipio);
    $direccion = replace($direccion);
    $resultado = $mysqli->query("INSERT INTO user(nick,email,pass,province,city,direction,rol,request) VALUES ('".$nick."', '".$email."', '".$contrasena."','".$provincia."','".$municipio."','".$direccion."', '".$rol."', '".$request."')");
    return $resultado;
}

function insertToProduct($mysqli, $title, $descrip, $price, $sumRating, $numComments, $photoLink, $purchaseLink, $trailerLink, $requirements, $category, $kind){
    $title = replace($title);
    $descrip = replace($descrip);
    $photoLink = replace($photoLink);
    $purchaseLink = replace($purchaseLink);
    $trailerLink = replace($trailerLink);
    $requirements = replace($requirements);
    $resultado = $mysqli->query("INSERT INTO product(title,descrip,price,sumRating,numComments,photoLink,purchaseLink,trailerLink,requirements,category,kind) VALUES ('".$title."', '".$descrip."', '".$price."','".$sumRating."','".$numComments."','".$photoLink."', '".$purchaseLink."', '".$trailerLink."', '".$requirements."', '".$category."', '".$kind."')");
    return $resultado;
}

function insertToProject($mysqli, $title, $descrip, $photoLink){
    $title = replace($title);
    $descrip = replace($descrip);
    $photoLink = replace($photoLink);
    $resultado = $mysqli->query("INSERT INTO project(title,photoLink,descrip) VALUES ('".$title."', '".$photoLink."', '".$descrip."')");
    return $resultado;
}

function insertToNewsletter($mysqli, $comment, $publishDate){
    $comment = replace($comment);
    $resultado = $mysqli->query("INSERT INTO newsletter(comment, publishDate) VALUES ('".$comment."', '".$publishDate."')");
    return $resultado;
}

function addComment($mysqli, $idUser, $opinion, $rating, $validated, $idProduct){
    $resultado = $mysqli->query("INSERT INTO comment(idUser, opinion, rating, validated, idProduct) VALUES ('".$idUser."', '".$opinion."', '".$rating."', '".$validated."', '".$idProduct."')");
    return $resultado;
}

//**********************************************************************DELETE*********************************************************************//
function deleteUsers($mysqli, $id){
    $resultado2 = $mysqli->query("DELETE FROM shoppinghistory WHERE idUser='".$id."'");
    $resultado3 = deleteCommentsWrittenByUser($mysqli, $id);
    $resultado4 = $mysqli->query("DELETE FROM shoppingrelations WHERE idUser='".$id."'");

    $resultado = $mysqli->query("DELETE FROM user WHERE idUser='".$id."'");
    return $resultado;
}

function deleteProduct($mysqli, $id){
    $resultado2 = $mysqli->query("DELETE FROM comment WHERE idProduct='".$id."'");
    $resultado3 = $mysqli->query("DELETE FROM shoppingrelations WHERE idProduct='".$id."'");

    $resultado = $mysqli->query("DELETE FROM product WHERE idProduct='".$id."'");
    return $resultado;
}

function deleteProject($mysqli, $id){
    $resultado = $mysqli->query("DELETE FROM project WHERE idProject='".$id."'");
    return $resultado;
}

function deleteNewsletter($mysqli, $id){
    $resultado = $mysqli->query("DELETE FROM newsletter WHERE idNewsletter = $id");
    return $resultado;
}

function deleteCommentsWrittenByUser($mysqli, $id){
    $resultado1 = $mysqli->query("SELECT * FROM comment WHERE idUser = $id");

    while($comment = $resultado1->fetch_assoc()){
        $resultado2 = $mysqli->query("SELECT * FROM product WHERE idProduct = ".$comment['idProduct']."");
        $product = $resultado2->fetch_assoc();

        $rating = $product['sumRating'] - $comment['rating'];
        $numComments = $product['numComments'] - 1;

        $resultado3 = $mysqli->query("UPDATE product SET sumRating = ".$rating.", numComments = ".$numComments." WHERE idProduct = ".$comment['idProduct']."");
        $resultado4 = $mysqli->query("DELETE FROM comment WHERE idComment = ".$comment['idComment']."");
    }

    return TRUE;
}

function deleteComment($mysqli, $idUser, $idProduct){
    $resultado = $mysqli->query("DELETE FROM comment WHERE idUser='".$idUser."' AND idProduct ='".$idProduct."'");
    return $resultado;
}


//**********************************************************************ISELSE*********************************************************************//
function isBought($mysqli, $idUser, $idProduct){
    $resultado = $mysqli->query("SELECT * FROM shoppingrelations WHERE idUser = $idUser AND idProduct = $idProduct");

    if($resultado->num_rows == 1){
        return TRUE;
    }else{
        return FALSE;
    }
}

function isCommented($mysqli, $idUser, $idProduct){
    $resultado = $mysqli->query("SELECT * FROM comment WHERE idUser = $idUser AND idProduct = $idProduct");

    if($resultado->num_rows == 1){
        return TRUE;
    }else{
        return FALSE;
    }
}

function isCommentedValidated($mysqli, $idUser, $idProduct){
    $resultado = $mysqli->query("SELECT * FROM comment WHERE idUser = $idUser AND idProduct = $idProduct AND validated = 1");

    if($resultado->num_rows == 1){
        return TRUE;
    }else{
        return FALSE;
    }
}

?>