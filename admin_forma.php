<?php
include "db.php";
$connect = DBConnect();
?>
<!doctype html>
<html lang="ru">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Администратор (Заказы)</title>
	<link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="app">
        <table id="tab_zak">
          <tr>
              <th> № </th>
              <th>Дата заказа</th>
              <th> ФИО </th>
              <th>Телефон</th>
              <th>Email</th>
              <th>Адрес доставки</th>
              <th>Наименование товара</th>
              <th>Стоимость</th>
              <th>Кол-во</th>
              <th>Сумма</th>
            </tr>
         <?php
            $query = "SELECT ZAKAZY.id_zak AS id_z, data_zak, tel_kl, name, email, adr_dost, COUNT(Det_Zakaza.id_zak) AS coun_zak FROM `ZAKAZY`, `Det_Zakaza` WHERE Det_Zakaza.id_zak=ZAKAZY.id_zak GROUP BY ZAKAZY.id_zak, data_zak, tel_kl, name, email, adr_dost";
            $result = mysqli_query($connect, $query);
            if(!$result)
            {
               echo 'Ошибка запроса: ' . '<br>';
            }
            while($row = $result->fetch_assoc()) {
                  echo "<tr>
                    <td rowspan='{$row["coun_zak"]}'>{$row["id_z"]}</td>
                    <td rowspan='{$row["coun_zak"]}'>{$row["data_zak"]}</td>
                    <td rowspan='{$row["coun_zak"]}'>{$row["name"]}</td>
                    <td rowspan='{$row["coun_zak"]}'>{$row["tel_kl"]}</td>
                    <td rowspan='{$row["coun_zak"]}'>{$row["email"]}</td>
                    <td rowspan='{$row["coun_zak"]}'>{$row["adr_dost"]}</td>";
                        $queryd = "SELECT * FROM `Det_Zakaza` WHERE id_zak={$row["id_z"]}";
                        $resultd = mysqli_query($connect, $queryd);
                        if(!$resultd)
                        {
                                echo 'Ошибка запроса: ' . '<br>';
                        }
                        while($rowd = $resultd->fetch_assoc()) {
                            $sum = $rowd["price_t"] * $rowd["kol_t"];
                           echo "<td>{$rowd["name_t"]}</td>
                            <td>{$rowd["price_t"]}</td>
                            <td>{$rowd["kol_t"]}</td>
                            <td>$sum</td></tr>";
                 }
            }
          ?>


    </table>
     </div>
    </body>
</html>
