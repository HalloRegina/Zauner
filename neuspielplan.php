<?php
include 'config.php';

if (!isset($_POST['erfassen']) && !isset($_POST['save']))
{
    ?>
    <H1>Spielplan erfassen</H1>
    <form method="post">
        <div class="table">
            <div class="tr">
                    Drama:
                    <?php
                    try
                    {

                        $query = 'select d.dra_name 
from drama d
where exists (select 1 from event e where e.dra_id = d.dra_id);';
                        $stmt = $con->prepare($query);
                        $stmt->execute();


                        echo '<div class="td">';
                        echo '<select name = "drama">';

                        while($row = $stmt->fetch(PDO::FETCH_NUM))
                        {
                            foreach ($row as $r)
                            {
                                echo '<option value="'.$r.'">'.$r.'</option>';
                            }
                        }
                        echo '</select>';
                        echo '</div>';
                    }
                    catch (Exception $e)
                    {
                        echo $e->getMessage();
                    }
                    ?>
                <br>
                <div class="td">
                    Anzahl der Aufführungen
                    <input  type="text" name="anzahl">
                </div>
            </div>
            <div class="tr">
                <div class="td">
                    <input type="submit" name="erfassen" value="auswählen">
                </div>
            </div>
        </div>
    </form>
    <?php
}
else
{
    if (!isset($_POST['save']))
    {
        session_start();
        $anzahl = $_POST['anzahl'];
        $drama = $_POST['drama'];
        //echo $drama;

        $_SESSION['anzahl'] = $anzahl;
        $_SESSION['drama'] = $drama;

        ?>
        <H1>Spielplan für <?php echo $drama;?> erfassen</H1>
        <form method="post">
            <div class="table">
                Datum:
                <?php
                include "config.php";
                if ($anzahl == null or $anzahl == 0)
                {
                    $anzahl = 1;
                }
                for ($i = 0; $i < $anzahl; $i++)
                {
                    ?>
                    <div class="tr">
                        <div class="td">
                            <input type="date" id="date" name="date<?php echo $i; ?>">
                            <input type="time" id="time" name="time<?php echo $i; ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="tr">
                    <div class="td">
                        <input type="submit" name="save" value="speichern">
                    </div>
                </div>
            </div>
        </form>
        <?php
    }
    else {
        // Daten speichern

        session_start();
        $anzahl = $_SESSION['anzahl'];
        $drama = $_SESSION['drama'];
        
        echo 'anzahl'.$anzahl;

        if ($anzahl == null)
        {
            $anzahl = 1;
        }


        //echo 'anzahl'.$anzahl;


        //echo $drama;

        include 'config.php';

        // todo datumscheck!!!!!
        $array = array();
        $array = getdate();

        //echo 'local'.date($array[0]);

        $localTime = date($array[0]);

        $date = $_POST['date0'];
        $time = $_POST['time0'];

        //echo 'code'.strtotime($date.' '.$time);

        try {
            $query = 'select distinct dra_id from drama where dra_name = ?';
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $drama);
            $stmt->execute();
            $dra_id = null;

            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                //echo 'in while';
                foreach ($row as $r) {
                    $dra_id = $r;
                }
            }

            //echo $dra_id;


            for ($i = 0; $i < $anzahl; $i++) {
                //echo 'in loop';
                //echo 'dra_id'.$dra_id;
                $date = $_POST['date' . $i];
                $time = $_POST['time' . $i];

                $myTime = strtotime($date.' '.$time);

                if ($myTime < $localTime)
                {
                    echo 'Datum ist kleiner als Erstellungsdatum';
                    ?>
                    <a href="http://localhost/uebungen/theater_mak/?seite=neuspielplan">Neu machen</a>
                    <?php
                    return;
                }

                $datetime = $date . ' ' . $time;
                //echo $datetime;

                $query = 'insert into event (eve_termin, dra_id) values (?, ?)';
                $stmt = $con->prepare($query);
                $stmt->bindParam(1, $datetime);
                $stmt->bindParam(2, $dra_id);
                $stmt->execute();
                //echo 'inserted';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        try {
            include 'functions.php';
            $query = 'select e.eve_termin as "Aufführung"
                  from event e, drama d
                  where e.dra_id = d.dra_id
                    and d.dra_name = ?;';
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $drama);
            $stmt->execute();

            ?>
            <h1><?php echo $drama;?></h1>
            <?php

            showTableFromQuery($stmt);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

        ?>
        <?php

}
?>
<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 15.01.2018
 * Time: 08:08
 */
