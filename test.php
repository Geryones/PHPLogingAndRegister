<?php
include 'includes/overall/header.php';
$user = new User();


$pagination = new Pagination('thumbnail','test.php');

$pageRows=4;//eingabe feld, um zu dem user die wahl zu lassen wieviele beiträge er pro seite sehen will
$lastPage=$pagination->lastPage($pageRows);

$actualPage=1;

if(isset($_GET['page'])){
    $actualPage=preg_replace('#[^0-9]#', '', $_GET['page']);
}

if($actualPage<1){
    $actualPage=1;
}else if($actualPage>$lastPage){
    $actualPage=$lastPage;
}

$content=$pagination->getContent($actualPage,$pageRows);
?>
    <h1>This is Test</h1>
<?php



$controls= $pagination->createPaginationControls($actualPage,$pageRows);

if($user->isLoggedIn()) {


    foreach($content as $picture){
    echo '<img src="'.Config::get('pictures/dirThumbnail').$picture->name .'" ><br>';
    }
    ?>

<div id="bottomNavigation">
    <div id = "pagination_controls">
        <?php echo $controls?>
    </div>
</div>


<?php
}else{
    echo '<p> You need to  <a href="login.php"> log in </a> or <a href="register.php"> register</a> before you can use this functionality </p>';
}

include 'includes/overall/footer.php';

