<?php
require_once("include/config.php");
include("include/basket.php");
$productID = $_GET['item'];

$stmt = $mysqli->stmt_init();
if ($stmt->prepare("SELECT  Products.ID, Products.Name, Products.Category, Products.ImgURL, Products.Price, Products.Code, Products.MadeBy, Products.Volume, Products.Ancestry, Products.Harvest, Products.Alchohol, Categories.Name FROM Products JOIN  Categories ON  Products.Category = Categories.ID  WHERE Products.ID = ?")){
	$stmt->bind_param("s",$productID);
	$stmt->execute();
	$stmt->bind_result($productID, $productName, $productCategory, $productImg, $producPrice, $productCode, $productMadeBy, $productVolume, $productAncestry, $productHarvest, $productAlchohol, $categoryName);
	$stmt -> fetch();
	$stmt->close();
}

?>
<!DOCUMENT>
<html>
<head>
	<title>Почетна | Винарија</title>
    <?php include("include/htmlHeader.php"); ?>
</head>
<body>
	<?php include("include/nav.php"); ?>
    <div class="container content">
    	<div id="sidebar">
        	<ul>
            	<?php 
				$stmt = $mysqli->stmt_init();
				if ($stmt->prepare("SELECT ID,Name FROM Categories ORDER BY ID")){
					$stmt->execute();
					$stmt->bind_result($id,$name);
				}
				while($stmt -> fetch()){?>
            	<li <?php if($productCategory == $id){?>class="active"<?php } ?>><a href="index.php?category=<?php echo $id; ?>"><?php echo $name; ?></a></li>
                <?php }
                $stmt->close();
				?>
            </ul>
        </div>
        <div class="mainContent">
        	<div class="mainContentHeader">
            	<h4><a href="index.php">Вина</a> / <a href="index.php?category=<?php echo $productCategory; ?>"><?php echo $categoryName; ?></a> / <?php echo $productName; ?></h4>
            </div>
        	<div class="item">
            	<div class="item-img">
                	<img src="<?php echo $productImg; ?>" alt=".." />
                </div>
                <div class="item-data">
                	<h2><?php echo $productName; ?></h2>
                    <h4>Цена: <?php echo $producPrice.' MKD'; ?></h4>
                    <h4>Производител: <?php echo $productMadeBy; ?></h4>
                    <p>Шифра: <?php echo $productCode; ?></p>
                    <p>Волумен: <?php echo $productVolume.' ml'; ?></p>
                    <p>ALC: <?php echo $productAlchohol.' %'; ?></p>
                    <p>Берба: <?php echo $productHarvest; ?></p>
                    <p>Потекло: <?php echo $productAncestry; ?></p>
                    <form id="shopingCardForm" name="shopingCardForm" method="POST" action="product.php?item=<?php echo $productID; ?>">
          				<input type="hidden" name="productID" id="productID" value="<?php echo $productID; ?>" />
                		<input type="hidden" name="productCode" id="productCode" value="<?php echo $productCode; ?>" />
                		<input type="hidden" name="productName" id="productName" value="<?php echo $productName; ?>" />
                		<input type="hidden" name="productPrice" id="productPrice" value="<?php echo $producPrice; ?>" />
                    	<p>Количина:&nbsp;<input type="text" size="5" id="productQuantity" name="productQuantity" value="" /></p>
                    	<p><input type="submit" class="btn-vine" value="Во кошничка" /></p>
                    </form>
                </div>
        	</div>
        </div> 
    </div>
</body>
</html>