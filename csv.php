<?php
class FichierExcel {
 
private 
	$csv = Null;
	/**
	 * Cette ligne permet de créer les colonnes du fichers Excel
	 * Cette fonction est totalement faculative, on peut faire la même chose avec la
	 * fonction insertion, c'est juste une clarté pour moi
	 */
	function Colonne($file) {
 
		$this->csv.=$file."\n";
		return $this->csv;
 
	}
 
	/**
	 * Insertion des lignes dans le fichiers Excel, il faut introduire les données sous formes de chaines
	 * de caractère.
	 * Attention a séparer avec une virgule.
	 */
	function Insertion($file){
 
		$this->csv.=$file."\n";
		return $this->csv;
	}
 
	/**
	 * fonction de sortie du fichier avec un nom spécifique.
	 *
	 */
	function output($NomFichier){ 
 
		//header("Content-type: application/vnd.ms-excel");
		//header("Content-disposition: attachment; filename=$NomFichier.csv");
		file_put_contents('tmpe/'.$NomFichier.'.csv', $this->csv);
	}
}
 //fin de classe FichierExcel
 
 $source="";
 $dest="";
 if(isset($_GET['affiche']) || isset($_GET['sub'])) $source=$_GET['fichier_source'];
 if(isset($_GET['affiche']) || isset($_GET['sub']))$dest=$_GET['fichier_destination'];
	echo'<pre>';
	echo'<form method ="get" action="#">
			 Mot à supprimer:       <input type="text" name="mot_cle" value=""/><input type="submit" name="sub" value="supprime"/>
		 Fichier soure:         <input type="text" name="fichier_source" value="'.$source.'"/>
		 Fichier destination:   <input type="text" name="fichier_destination" value="'.$dest.'"/>
		   <input type="submit" name="affiche" value="affiche"/>
			
		 </form>';
	echo'</pre>';

if(isset($_GET['affiche']) && (!empty($_GET['fichier_destination']) || !empty($_GET['fichier_source']))){
	
	$file = ""; 
	if(file_exists("tmpe/".$_GET['fichier_destination'].".csv")){
	 $file="tmpe/".$_GET['fichier_destination'].".csv";
	 $f = fopen($file,"a+");
	}
	elseif(file_exists("tmpe/".$_GET['fichier_source'].".csv")){
	 $file="tmpe/".$_GET['fichier_source'].".csv";
	 $f = fopen($file,"a+");
	}
    if(strlen($file)>0){
		 echo "<table align=\"center\">";
		 while($row =fgetcsv($f,4000,";" )){
		  $nb_col = count($row);
			echo"<tr>";
			    for($i=0; $i<$nb_col; $i++){
				 echo "<td style=\"background:#e1e1e1;\">". $row[$i]."</td>";
			    }
			echo"</tr>";
	
	    }
		echo "</table>";
		fclose($f);
    } 
}elseif(isset($_GET['affiche']) && (empty($_GET['fichier_destination']) && empty($_GET['fichier_source']))){ echo 'saisissez un nom de fichier csv';} 

$fichier = new FichierExcel();

if(isset($_GET['sub']) && !empty($_GET['mot_cle']) && !empty($_GET['fichier_destination']) && !empty($_GET['fichier_source'])){
	  $file="tmpe/".$_GET['fichier_source'].".csv";
	 $f = fopen($file,"a+");
	while($row =fgetcsv($f,4000,";" )){
	  $col =""; $nb_col = count($row);
	
	    for($i=0; $i<$nb_col; $i++){
		    $pos=strpos($row[$i],$_GET['mot_cle']); 
		    if($pos >=0 && $pos!==false){ 
				continue 2;    
			}else{$col.= $row[$i].';';}
		}
		   $col=substr($col,0,strlen($col)-1);
		   $fichier->Insertion($col);   
	}
	 $fichier->output($_GET['fichier_destination']);
}else{echo '<br/>R.A.S';}
?>
