<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController {
  #[Route("/", name:"home")]
  public function index(): Response {
    $dotenv=new \Symfony\Component\Dotenv\Dotenv();
    $envPath=dirname(__DIR__,2)."/.env"; if(is_file($envPath)){ $dotenv->usePutenv(); $dotenv->load($envPath); }
    date_default_timezone_set(getenv("TIMEZONE") ?: "UTC");
    $pdo=new \PDO(sprintf("mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",getenv("DB_HOST"),getenv("DB_PORT"),getenv("DB_NAME")),getenv("DB_USER"),getenv("DB_PASSWORD"),[\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION]);
    $execs=$pdo->query("SELECT id, run_date FROM etl_execution ORDER BY id DESC")->fetchAll(\PDO::FETCH_ASSOC);
    $selected=$execs[0]["id"]??null; if(isset($_GET["exec"])&&ctype_digit($_GET["exec"])) $selected=(int)$_GET["exec"];
    $stmt=$pdo->prepare("SELECT section,k,male,female,other,total FROM etl_summary WHERE execution_id=?"); $stmt->execute([$selected]); $rows=$stmt->fetchAll(\PDO::FETCH_ASSOC);
    $gender=["male"=>0,"female"=>0,"other"=>0]; $ages=[]; $os=[];
    foreach($rows as $r){ if($r["section"]==="gender"){$gender[$r["k"]]=(int)$r["total"];} if($r["section"]==="age"){$ages[$r["k"]]=(int)$r["total"];} if($r["section"]==="os"){$os[$r["k"]]=(int)$r["total"];} }
    ksort($ages);
    return $this->render("home.html.twig",["execs"=>$execs,"selected"=>$selected,"gender"=>$gender,"ages"=>$ages,"os"=>$os]);
  }
}
