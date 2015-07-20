<?php
require_once ('index_top.php');
// define variables and set to empty values
$json_obj = '{
          "num": "",
          "msg_date": "",
          "nsi_1": "",
          "to_who": "",
          "doc_id": "",
          "name_tbl":"'.DB_MSG.
          '"}';

//convert to stdclass object
$record = json_decode($json_obj);
$err = json_decode($json_obj);
$record->doc_id = $_SESSION['id_doc'];
$row = $db->selectMsg($record->doc_id); //Search doc_id
if (count($row) != 0) {
    foreach ($row as $item) {
        $record->num = $item['num'];
        $record->msg_date = $db->formatDate($item['msg_date'], $_SESSION['form_date']);
        $record->nsi_1 = $item['nsi_1'];
        $record->to_who = $item['to_who'];
    }
    //echo json_encode($row);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check if number of message 
    $btn = $_POST['delete'];
    if ($btn) {
        header('Location:' . $_SERVER["PHP_SELF"]); //Where the button <delete> is clicked reload page
        exit();
    }
    if (empty(trim($_POST["num"]))) {
        $err->num = "Номер документа не введен";
    } else {
        $record->num = test_input($_POST["num"]);
    }

// check if dete of message  is well-formed
    if (empty(trim($_POST["msg_date"]))) {
        $err->msg_date = "Необходимо ввести дату";
    } else {
        $record->msg_date = test_input($_POST["msg_date"]);
        //       list($day, $month, $year) = sscanf($date, "%02d.%02d.%04d");
        //       $date = "$year.$month.$day";
        // $inspectionErr = $selInspection;
    }

// check if the field FROM  is well-formed
    if (empty($_POST["nsi_1"])) {
        $err->nsi_1 = "Поле --От кого поступило-- не заполнено ";
    } else {
        $record->nsi_1 = test_input($_POST["nsi_1"]);
    }

    // check if the field FROM  is well-formed
    if (empty($_POST["to_who"])) {
        $err->to_who = "Поле --На кого поступило-- не заполнено ";
    } else {
        $record->to_who = test_input($_POST["to_who"]);
        if (!preg_match("/^[[а-яА-ЯёЁ. ]+$/u", $record->to_who)) {
            $err->to_who = "Можно вводить только буквы кириллицы";
        }
    }


    // check if PASS is well-formed
    $error = "";
    $error = $err->num . $err->msg_date . $err->nsi_1 . $err->to_who;

//Insert in database new msg
    if (empty($error)) {
        if (count($row) != 0)
            $db->deleteDoc($record->name_tbl, $record->doc_id); //doc_id  is  found in MySql database.
        $insert = $db->insertMsg($record); //Pass array as parameter
        if ($insert) {
            $err->num = $_SESSION['success'];
            //echo $str;
        } else {
            $err->num = $_SESSION['error'];
            // die($str);
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Сообщение об административном правонарушении</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/menu.css" rel="stylesheet" type="text/css">
        <link href="css/mystyle.css" rel="stylesheet" type="text/css" />
        <link href="calendar/css/tcal.css" rel="stylesheet" type="text/css"  />
        <script type="text/javascript" src="calendar/scripts/tcal.js"></script> 
        <script type="text/javascript" src="scripts/json2.js"></script>  
        <script type="text/javascript" src="scripts/myLib.js"></script>  
    </head>
    <body onload="doRequestHttpServer('disabled_enabled',
                    '<?= @$record->doc_id; ?>',
                    '<?= $record->name_tbl; ?>',
                    '')">
        <div id="sitebranding">
            <h1 >Административные правонарушения</h1>
        </div>
        <?php
        echo "<div class='head_ins'><h5>" . "<dfn>Инспектор :</dfn>" . "      " . $_SESSION['fio'] . "<br />" .
        "<dfn> Инспекция :</dfn>" . "      " . $_SESSION['inspection_name'] . "</h5></div>";
        ?>
        <div id="mainmenu">
            <ul>
                <li><a href="msg_input.php">Сообщение</a></li>
                <li><a href="act_input.php">Акт</a></li>
                <?php
                $rowM = $db->selectMsg($record->doc_id);
                $rowA = $db->selectAct($record->doc_id);
                if (count($rowA) != 0 || count($rowM) != 0) {
                    echo '<li><a href="prepare_input.php">Подготовка </a></li>';
                    $row = $db->selectPrepare($record->doc_id); //Search doc_id
                    if (count($row) != 0) {
                        foreach ($row as $item) {
                            $statute_num = $item['statute_num'];
                        }
                        if ($statute_num == '0') {
                            echo '<li><a href="review_input.php">Направление на рассм.</a></li>';
                            $row = $db->selectReview($record->doc_id);
                            if (count($row) != 0) {
                                $row = $db->selectPenalty($record->doc_id);
                                if (count($row) != 0) {
                                    echo '<li><a href="penalty_input.php">Взыскание</a></li>';
                                    echo '<li><a href = "inform_input.php">Исполнение</a></li>';
                                } else {
                                    $row = $db->selectTermination($record->doc_id);
                                    if (count($row) != 0) {
                                        echo '<li><a href = "termination_input.php">Прекращение</a></li>';
                                    } else {
                                        echo '<li><a href="#">Рассмотрение</a>';
                                        echo '<ul><li><a href="penalty_input.php">Взыскание</a></li>';
                                        echo '<li><a href = "termination_input.php">Прекращение</a></li></ul></li>';
                                    }
                                }
                            }
                        } else
                            echo '<li><a href = "inform_input.php">Исполнение</a></li>';
                    }
                }
                ?>
                <li><a href="inspector_work.php">Назад</a></li>
                <li><a href="logout.php">Выход</a></li>
            </ul><!-- Конец списка -->
        </div><!-- Конец блока #mainmenu -->    
        <?php
        echo $_SESSION['id_doc'];
        if (!empty($_SESSION['reg_num'])) {
            echo "<div class='head_ins'><h3>" .
            "<dfn>Регистрационный номер дела :</dfn>" . "      " . $_SESSION['reg_num'] . "</h3></div>";
        } else {
            echo "<div class='head_ins'><h3><dfn>Дело не зарегистрировано</dfn></h3></div>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <fieldset>
                <legend class="txtFormLegend">Сообщение об административном правонарушении</legend>
                <br />
                <table border="0" cellpadding="2" class="datatable">
                    <tr class="altrow">
                        <th align="right">Входящий №</th>
                        <td align="left"><input name="num" value="<?= @$record->num; ?>" type="text"></td>
                        <td> <span class="error">* <?php echo $err->num; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">Дата</th>
                        <td align="left">
                            <input  type="text" name="msg_date" class="tcal" value="<?= @$record->msg_date; ?>">
                        </td>
                        <td> 
                            <span class="error">* <?php echo $err->msg_date; ?></span>
                        </td>
                    </tr>
                    <tr class="altrow">
                        <th align="right">От кого поступило</th>
                        <td align="left" class="newselect">
                            <select name="nsi_1"  title="Выбрать из списка"> 
                                <?php
                                $db->selectedList('1', $record->nsi_1);
                                ?>
                            </select>
                        </td>
                        <td> <span class="error">* <?php echo $err->nsi_1; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th align="right">На кого поступило (ФИО)</th>
                        <td align="left">
                            <input name="to_who" value="<?= @$record->to_who; ?>" type="text">
                        </td>
                        <td> 
                            <span class="error">* <?php echo $err->to_who; ?></span>
                        </td>
                    </tr>
                </table>
                <span class="txtSmall">Примечание: Необходимо заполнить все поля.</span>
                <div align="center">
                 <br /><br /><input  class="button button-blue"   id="save"   type="submit"  value="Сохранить данные">
                <input   class="button button-blue"  id="delete" type="submit" name="delete" value="Удалить данные" 
                       onclick="doRequestHttpServer('delete',
                                       '<?= @$record->doc_id; ?>',
                                       '<?= $record->name_tbl; ?>',
                                       'Удалить запись?')">
                   
                </div>
            </fieldset> 
        </form>
        <script type="text/javascript">shineLinks('mainmenu');</script>
    </body>

</html>
