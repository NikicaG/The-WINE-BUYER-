<?php
require_once("include/config.php");

if($_SERVER['REQUEST_METHOD']=="POST"){
	
	if(isset($_POST['event']) && !empty($_POST['event'])){
		
		$event = $_POST['event'];
		
		if($event == "saveNew"){
			if((isset($_POST['productName']) && !empty($_POST['productName'])) && (isset($_POST['madeBy']) && !empty($_POST['madeBy'])) && (isset($_POST['productCode']) && !empty($_POST['productCode']))  && (isset($_POST['productVolume']) && !empty($_POST['productVolume']))  && (isset($_POST['productHarvest']) && !empty($_POST['productHarvest']))  && (isset($_POST['productAlchohol']) && !empty($_POST['productAlchohol']))  && (isset($_POST['productAncestry']) && !empty($_POST['productAncestry']))  && (isset($_POST['productPrice']) && !empty($_POST['productPrice']))  && isset($_FILES['productImg']['tmp_name'])){
				
				$imageFolderPath = $_SERVER['DOCUMENT_ROOT'].'/simka/productImages';
				
				$fileName = $_FILES['productImg']['name'];
				
				if(move_uploaded_file($_FILES['productImg']['tmp_name'], $imageFolderPath.'/'.$fileName)){
					$imageFolderPath = '/simka/productImages/'.$fileName;
					$stmt = $mysqli->stmt_init();
					if ($stmt->prepare("INSERT INTO `Products`(`ID`, `Name`, `MadeBy`, `Code`, `Volume`, `Harvest`, `Alchohol`, `Ancestry`, `Price`, `Category`, `ImgURL`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?)")){
						$stmt->bind_param("ssssssssis",$_POST['productName'], $_POST['madeBy'], $_POST['productCode'], $_POST['productVolume'], $_POST['productHarvest'], $_POST['productAlchohol'], $_POST['productAncestry'], $_POST['productPrice'], $_POST['productCategory'], $imageFolderPath);
						$stmt->execute();
						$stmt->close();
					}
					
				}
			}
		}else if($event == "delete"){
			$product = $_POST['product'];	
			
			$stmt = $mysqli->stmt_init();
			if ($stmt->prepare("DELETE FROM Products WHERE ID = '".$product."'")){
				$stmt->execute();
				$stmt->close();
			}
		}
	
		header("Location: http://www.ng-development.com/simka/add.php");
	}
}

?>
<!DOCUMENT>
<html>
<head>
	<title>Додади производ | Винарија</title>
    <?php include("include/htmlHeader.php"); ?>
</head>
<body>
	<?php include("include/nav.php"); ?>
    <div class="container content add">
    <form id="addNewForm" name="addNewForm" method="POST" enctype="multipart/form-data" action="add.php">
		<div class="addNew">
        	<div class="form-row">
                <label>Име на производ</label>
                <input type="text" id="productName" name="productName"  />
            </div>
            <div class="form-row">
                <label>Производител</label>
                <input type="text" id="madeBy" name="madeBy"  />
            </div>
            <div class="form-row">
                <label>Шифра</label>
                <input type="text" id="productCode" name="productCode"  />
            </div>
            <div class="form-row">
                <label>Волумен</label>
                <input type="text" id="productVolume" name="productVolume"  />
            </div>
            <div class="form-row">
                <label>Берба</label>
                <input type="text" id="productHarvest" name="productHarvest"  />
            </div>
        </div>
        <div class="addNew">
            <div class="form-row">
                <label>Алкохол (%)</label>
                <input type="text" id="productAlchohol" name="productAlchohol"  />
            </div>
            <div class="form-row">
                <label>Потекло</label>
                <input type="text" id="productAncestry" name="productAncestry"  />
            </div>
            <div class="form-row">
                <label>Цена</label>
                <input type="text" id="productPrice" name="productPrice" placeholder ="МКД" />
            </div>
            <div class="form-row">
                <label>Категорија</label>
                <select id="productCategory" name="productCategory">
                	<?php 
					$stmt = $mysqli->stmt_init();
					if ($stmt->prepare("SELECT ID,Name FROM Categories ORDER BY ID")){
						$stmt->execute();
						$stmt->bind_result($id,$name);
					}
					while($stmt -> fetch()){?>
					<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
					<?php }
					$stmt->close();
					?>
                </select>
            </div>
            <div class="form-row">
                <label>Слика</label>
                <input type="file" id="productImg" name="productImg" />
            </div>
            <div class="form-row">
                <input type="submit" class="btn-vine" value="Зачувај"  />
            </div>
            <input type="hidden" id="event" name="event" value="saveNew" />
        </div>
        </form>
        <div>
        	<?php 
			$stmt = $mysqli->stmt_init();
			if ($stmt->prepare("SELECT Products.ID, Products.Name, Products.MadeBy, Products.Code, Products.Volume, Products.Harvest, Products.Alchohol, Products.Ancestry, Products.Price, Products.ImgURL, Categories.Name FROM Products JOIN Categories ON Products.Category = Categories.ID ORDER BY Products.Category")){
				$stmt->execute();
				$stmt->store_result();
				$count=$stmt->num_rows;
				$stmt->bind_result($id,$name, $madeBy, $code, $volume, $harvest, $alcho, $ancestry, $price, $img, $category);
			}
			?>
            <?php if($count > 0){?>
        	<table width="1200" cellpadding="4" cellspacing="2" align="left">
            	<thead>
                    <tr>
                        <th align="left">Име</th>
                        <th align="left">Производител</th>
                        <th align="left">Шифра</th>
                        <th align="left">Волумен</th>
                        <th align="left">Берба</th>
                        <th align="left">Алкохол</th>
                        <th align="left">Потекло</th>
                        <th align="left">Цена</th>
                        <th align="left">Категорија</th>
                        <th align="left">Слика</th>
                        <th align="left">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                	<?php while($stmt -> fetch()){?>
                    <tr>
                    	<td><?php echo $name; ?></td>
                        <td><?php echo $madeBy; ?></td>
                        <td><?php echo $code; ?></td>
                        <td><?php echo $volume; ?></td>
                        <td><?php echo $harvest; ?></td>
                        <td><?php echo $alcho; ?></td>
                        <td><?php echo $ancestry; ?></td>
                        <td><?php echo $price; ?></td>
                        <td><?php echo $category; ?></td>
                        <td><a href="<?php echo 'http://www.ng-development.com/'.$img; ?>" target="_blank">слика</a></td>
                        <td><a href="#" onClick="document.deleteForm.product.value='<?php echo $id; ?>';document.deleteForm.submit();">избриши</a></td>
					</tr>
					<?php }
					$stmt->close();
					?>
                </tbody>
            </table>
            <form id="deleteForm" name="deleteForm" method="POST" action="add.php">
            	<input type="hidden" id="event" name="event" value="delete" />
                <input type="hidden" id="product" name="product" value="" />
			</form>
            <?php } ?>
        </div>
    </div>
</body>
</html>