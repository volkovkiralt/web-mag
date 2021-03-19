<?php
session_start();
include "db.php";
include "korzina.php";

$db_connect = DBConnect();
$korzina = korzina_get($db_connect);

function itemRow($row) {
  return "
    <tr>
      <td id='nameukr{$row["id"]}'>{$row["name"]}</td>
      <td>{$row["opis"]}</td>
      <td><img class='foto' src='img/{$row["foto"]}/{$row["foto"]}1.jpg' onerror=\"this.src='img/soob.jpg';\"></td>
      <td><b>{$row["price"]}</b> руб.</td>
      <td><input type='number' class='count' value='0' min='0' data-relate='item-{$row["id"]}'></td>
      <td>
        <form action='/' method='post' class='add-to-korzina' onsubmit='handleAddKorzinaSubmit(event, {$row["id"]})'>
          <input type='hidden' name='method' value='add-to-korzina'>
          <input type='hidden' name='id' value='{$row["id"]}'>
          <input type='hidden' name='count' value='0'>
          <button type='submit'>Отложить</button>
        </form>
      </td>
    </tr>
  ";
}

if ($_POST["method"] === "add-to-korzina") {
    korzina_add($_POST["id"], $_POST["count"]);
    header("Location: /");
    die();
}

if ($_POST["method"] === "clear-korzina") {
    korzina_clear();
    header("Location: /");
    die();
}

if ($_POST["method"] === "zakaz") {
    if ($korzina['count'] === 0) {
        header("Location: /");
        die();
    }
    mysqli_begin_transaction($db_connect);

    $_POST["name"];
    $_POST["adr"];
    $_POST["email"];
    $_POST["tel"];

    $query = "INSERT INTO ZAKAZY (name, tel_kl, email, adr_dost) VALUES ('{$_POST["name"]}', '{$_POST["tel"]}', '{$_POST["email"]}', '{$_POST["adr"]}')";
    $result = mysqli_query($db_connect, $query);
    $zakazId = mysqli_insert_id($db_connect);

    $values = join(',', array_map(function($item) use ($zakazId) {
        return "('{$item["name"]}',{$item["price"]},{$item["count"]},{$zakazId})";
    }, $korzina['list']));


    $query = "insert into Det_Zakaza(name_t, price_t, kol_t, id_zak) Values {$values}";
    $result = mysqli_query($db_connect, $query);

    mysqli_commit($db_connect);
    $_SESSION['zakaz_ok'] = true;
    korzina_clear();
    header("Location: /");
    die();
}
?>
<html>
<head>
    <meta charset="UTF-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Аренда статусных вещей</title>
	<link rel="stylesheet" href="style.css">
</head>
<body id="telo">
<?
    if (isset($_SESSION['zakaz_ok']) && $_SESSION['zakaz_ok'] === true) {
        unset($_SESSION['zakaz_ok']);
        echo "<script>setTimeout(function(){ alert('Ваш заказ принят!!!'); }, 1000);</script>";
    }
?>
	<div id="main_wrapper">
		<div id="header">
			<img src="img/rama0.png" height="200px" width="1100px">
			<span id="head_text">Аренда статусных вещей <br></span>
				<span id="head_text1">для праздников и фотосессий</span><br><span id="head_text2">тел. +7-987-654-32-10</span>
		</div>
		<div id="menu_wrapper">
			<div class="menu" style="margin-left:20px">
			<a href="#ukr">Украшения</a>
			</div>
			<div class="menu">
			<a href="#odejda">Одежда</a>
			</div>
			<div class="menu">
			<a href="#buket">Букеты</a>
			</div>
			<div class="menu">
			<a href="#footer">Контакты</a>
			</div>
			<div class="menu">
			<a href="JavaScript:basket()"><img src="icons/basket.ico">Корзина</a>
			</div>
			<div class="menu">
                <span id="baskets">
                    <? if ($korzina['count'] === 0): ?>
                        Корзина пуста
                    <? else: ?>
                        <?= $korzina['sum'] ?> руб.
                    <? endif ?>
                </span>
            </div>
		</div>
        <form action="/" method="post" onsubmit="handleClearSubmit(event)">
            <input type="hidden" name="method" value="clear-korzina">
		    <button class="btnClearBask" type="submit">Очисить корзину</button>
        </form>
		<div id="main_content">
		    <div class="container">
		    <div id="ukr">
		        <h1 class="zbl">Украшения</h1>
		    <table class="shopp_list">
		   <tr>
		       <th class="td1">Наименование</th>
		       <th class="td3">Описание</th>
		       <th  class="td1">Фото</th>
		       <th class="td2">Стоимость</th>
		       <th class="td2">Кол-во</th>
		       <th class="td2">В корзину</th>
		  </tr>
		  <?php
            $query = "SELECT * FROM `TOVARY` WHere tip_id = 1";
            $result = mysqli_query($db_connect, $query);
            if(!$result)
            {
               echo 'Ошибка запроса: ' . '<br>';
            }
            while($row = $result->fetch_assoc()) {
                echo itemRow($row);
            }
          ?>
           </table>
           </div>
           <div class="menu"><a href="#header">На верх</a></div>
           <button class='btnsuc' type='button' onClick='basket()'>Перейти в корзину</button>
           </div>

            <?php
        $query = "SELECT * FROM `TOVARY` WHere tip_id = 2";
        $result = mysqli_query($db_connect, $query);
        if(!$result)
            {
               echo 'Ошибка запроса: ' . '<br>';
            }
        ?>
        <div class="container">
		    <div id="odejda">
		        <h1 class="zbl">Одежда</h1>
		    <table class="shopp_list">
		   <tr>
		       <th class="td1">Наименование</th>
		       <th class="td3">Описание</th>
		       <th  class="td1">Фото</th>
		       <th class="td2">Стоимость</th>
		       <th class="td2">Кол-во</th>
		       <th class="td2">В корзину</th>
		  </tr>
		  <?php
		     while($row = $result->fetch_assoc()) {
                 echo itemRow($row);
             }	?>
           </table>
           </div>
           <div class="menu"><a href="#header">На верх</a></div>
           <button class='btnsuc' type='button' onClick='basket()'>Перейти в корзину</button>
           </div>
           <?php
        $query = "SELECT * FROM `TOVARY` WHere tip_id = 3";
        $result = mysqli_query($db_connect, $query);
        if(!$result)
            {
               echo 'Ошибка запроса: ' . '<br>';
            }
        ?>
        <div class="container">
		    <div id="buket">
		        <h1 class="zbl">Букеты</h1>
		    <table class="shopp_list">
		   <tr>
		       <th class="td1">Наименование</th>
		       <th class="td3">Описание</th>
		       <th  class="td1">Фото</th>
		       <th class="td2">Стоимость</th>
		       <th class="td2">Кол-во</th>
		       <th class="td2">В корзину</th>
		  </tr>
		  <?php
		     while($row = $result->fetch_assoc()) {
                 echo itemRow($row);
             }	?>
           </table>
           </div>
           <div class="menu"><a href="#header">На верх</a></div>
           <button class='btnsuc' type='button' onClick='basket()'>Перейти в корзину</button>
           </div>
		</div>




		<div id="footer">
			<span>Адрес: г.Ростова-на-Дону, пер. Братский 44
			<br>Телефон: +7-987-654-32-10
			<br>Режим работы: с 8:00 до 19:00<br><br>
				<div class="menu" style="margin-left:20px">
			<a href="#ukr">Украшения</a></div>
			<div class="menu">
			<a href="#odejda">Одежда</a></div>
			<div class="menu">
			<a href="#buket">Букеты</a></div>
			</span>
			<div id="karta">
				<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A5cce11ff04404216c84ef02f9fa57c2f38b5f48bf72283bacca634fd9a53c3a9&amp;width=455&amp;height=280&amp;lang=ru_RU&amp;scroll=true"></script>
			</div>
		</div>
	</div>
	<div id="fonClick">

		<div id="windowOtpr">Благодарим, Вас, за оставленные данные!<br>В самое ближайшее время с Вами свяжется наш сотрудник!</div>

		<div id="windowClick">
			<div id="closeStr" onclick="poClose();">ЗАКРЫТЬ</div>

			<div id="basket">
				<h1 class="korz_title">Ваша корзина</h1>
				<div class="basket-body">
                    <div id="cart_content">
                        <? if ($korzina['count'] === 0) { ?>
                            <h1 class="korz_title">пуста</h1>
                        <?
                            } else {
                        ?>
                            <table class="basket-list">
                                <tr><th>Наименование</th><th>Цена</th><th>Кол-во</th><th>Сумма</th></tr>
                        <?
                            for($key=0; $key<$korzina['count']; $key++) {
                                $row = $korzina['list'][$key];
                        ?>
                                <tr>
                                    <td><?=$row["name"]?></td>
                                    <td><?=$row["price"]?></td>
                                    <td><?=$row["count"]?></td>
                                    <td><?=$row["sum"]?> руб.</td>
                                </tr>
                        <?
                                }
                        ?>
                                <tr><td colspan="3" id="itogo">Итого</td><td><?=$korzina['sum']?> руб.</td></tr>
                            </table>
                        <?
                            }
                        ?>
                    </div>

                    <div id="wrapperZvonok">

                        <span id="zvonokText">Заполните форму и мы доставим Ваш заказ</span>

                        <form name="zForm" id="zvonokForm" method="POST" action="/" onsubmit="handleZakazSubmit(event)">
                            <input type="hidden" name="method" value="zakaz">
                            <span>ФИО<em>*</em></span><br>
                            <input id="zvonokFormText" type="text" name="name" placeholder="" onblur="nameForm = this.value;"><br>
                            <span>Адрес<em>*</em></span><br>
                            <input id="zvonokFormText" type="text" name="adr" placeholder="Ваш адрес" onblur="adrForm = this.value;"><br>
                            <span>E-mail</span><br>
                            <input id="zvonokFormEmail" type="email" name="email" placeholder="E-mail" onblur="emailForm = this.value;"><br>
                            <span>Телефон<em>*</em></span><br>
                            <input id="zvonokFormTel" type="tel" name="tel" placeholder="+7..." onblur="telForm = this.value;"><br>
                            <input id="otprBut1" class="otpr" type="submit" value="ЗАКАЗАТЬ">
                        </form>

                    </div>
                </div>

            </div>
			<div id="textClickWrapper"><div id="textClick"></div></div>

		</div>

	</div>
		<script src="scripts/scripts.js"></script>
</body>

</html>
