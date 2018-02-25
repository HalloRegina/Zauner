
<?php
if (!isset($_POST['erfassen']))
{
    ?>
    <form method="post">
        <div class="table">
            <div class="tr">
                <div class="td">
                    <input type="text" name="title" placeholder="z.B. romeo">
                </div>
            </div>
            <div class="tr">
                <div class="td">
                    <input type="submit" name="erfassen" value="speichern">
                </div>
            </div>
        </div>
    </form>
    <?php
}
else
{
    $title = $_POST['title'];

    if ($title == null)
    {
        echo 'Es wurde nicht eingegeben';
        ?>
        <a href="http://localhost/uebungen/theater_mak/?seite=suchetheaterstuck">Neu machen</a>
        <?php
        return;
    }

    $title = strtolower($title);

    include 'config.php';
    try
    {
        include 'functions.php';
        $like = '%'.$title.'%';
        $query = 'select d.dra_name as "Drama", g.gen_name as "Genre", concat_ws(\' \', p.per_vname, p.per_nname) as "Autor", (select min(eve_termin) from event where dra_id = d.dra_id) as "Erstaufführung"
from drama d, genre g, person p, rolle r
where p.rol_id = r.rol_id
    and r.rol_id = 4
    and d.gen_id = g.gen_id
    and d.autor_id = p.per_id
    and (d.dra_name) like ?;';
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $like);
        $stmt->execute();

        $rows = $stmt->rowCount();

        if ($rows == 0)
        {
            echo 'Keine Treffer gefunden';
            ?>
            <a href="http://localhost/uebungen/theater_mak/?seite=suchetheaterstuck">Neu machen</a>
            <?php
            return;
        }



        showTableFromQuery($stmt);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}
?>
<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 15.01.2018
 * Time: 08:08
 */
