<?php
class DonneesShopping{
   
	private $rs_lampe;
	private $rs_lampe_OI;
	private $rs_lampe_plus;
	private $rs_lampe_prix;
	private $rs_lampe_stock;
	private $rs_vp;
	private $rs_batterie;
	private $rs_chargeur;
	private $rs_produit_associe;
	private $rs_price;
	public  $type_p;
	public static $tauxDeChange;
	public static $tva;
	public static $product_id;
	public  $rsBattParam;
	private $rs_puissance;
	
	public function getRsLampe(){
	    return $this->rs_lampe;
	}
	public function getRsLampeOI(){
	    return $this->rs_lampe_OI;
	}
	public function getRsLampePlus(){
	    return $this->rs_lampe_plus;
	}
	public function getRsLampePrix(){
	    return $this->rs_lampe_prix;
	}
	public function getRsLampeStock(){
	    return $this->rs_lampe_stock;
	}
	public function getRsVp(){
	    return $this->rs_vp;
	}
	public function getRsBatterie(){
	    return $this->rs_batterie;
	}
	public function getRsChargeur(){
	    return $this->rs_chargeur;
	}
	public function getRsProduitAssocie(){
	    return $this->rs_produit_associe;
	}
	public function getRsPrice(){
	     return $this->rs_price;
	}
	public function getRsBattParam(){
	    return $this->rsBattParam;
	}
	public function getRsPuissance(){
	    return $this->rs_puissance;
	}
	public function setRsLampe($rs_lampe){
	    $this->rs_lampe = $rs_lampe;
	}
	public function setRsLampeOI($rs_lampe_OI){
	    $this->rs_lampe_OI = $rs_lampe_OI;
	}
	public function setRsLampePlus($rs_lampe_plus){
	    $this->rs_lampe_plus = $rs_lampe_plus;
	}
	public function setRsLampePrix($rs_lampe_prix){
	    $this->rs_lampe_prix = $rs_lampe_prix;
	}
	public function setRsLampeStock($rs_lampe_stock){
	    $this->rs_lampe_stock = $rs_lampe_stock;
	}
	public function setRsVp($rs_vp){
	    $this->rs_vp = $rs_vp;
	}
	public function setRsBatterie($rs_batterie){
	    $this->rs_batterie = $rs_batterie;
	}
	public function setRsChargeur($rs_chargeur){
	    $this->rs_chargeur = $rs_chargeur;
	}
	public function setRsProduitAssocie($rs_produit_associe){
	    $this->rs_produit_associe = $rs_produit_associe;
	}
	public function setRsPrice($rs_price){
        $this->rs_price = $rs_price; 
	}
	public function setRsBattParam($rsBattParam){
	    $this->rsBattParam = $rsBattParam;
	}
	public function setRsPuissance($rs_puissance){
	    $this->rs_puissance = $rs_puissance;
	}
	
	public function __construct($type_p){
	    $this->type_p = $type_p;
		switch ($type_p){
		    case 'vproj':
			case 'lamp':
			case 'lamp_vproj':
			case 'originale_inside':
		        $type_p ="lamp";
			    include('defines_flux.php');
			    break;
			case 'batt':
			case 'batt_charge':
			    $type_p ="batt";
			    include('defines_flux.php');
				break;
			case 'charge':
			    $type_p ="charge";
			    include('defines_flux.php');
    		    break;	
            default:
                break;			
		}

	}
	
	//FONCTION DE CONNEXION A LA BASE DE DONNEES
	private static function connexion(){
	    try{
		    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$pdo = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_DATABASE_STK.'', DB_SERVER_USERNAME_STK , DB_SERVER_PASSWORD_STK, $pdo_options);
			return $pdo;
		}catch(Exception $e){
		    echo 'problème de connexion: '. $e->getMessage();
		}
	}
	
	// SQL LAMPES OI
    public function sqlLampe_OI($agregat){
	    $pdo = self::connexion();
		switch($agregat){
		    case 'amazon':
			case 'google':
		        $sql="SELECT DISTINCT p.value, c.id_constructeur, c.libelle_constructeur,
                       				c.libelle_produit, c.code_type_produit, c.id_produit,
									c.id_composant, c.sous_type_composant, c.ref_interne_composant, 
									c.ref_constructeur_composant
							FROM el_products_techdata AS p
							INNER JOIN el_v_composants_hpl AS c ON p.ctr_code = c.libelle_constructeur
							AND p.lamp_code = c.ref_interne_composant

							WHERE c.code_type_produit
							IN (
							'OI'
							)
							AND p.`datatype_code` = 'ean_code'
							GROUP BY c.ref_interne_composant
							ORDER BY `c`.`libelle_constructeur` ASC";
			    break;
			default:
			    break;       
		}
		$rs = $pdo->query($sql);	
        $this->setRsLampeOI($rs);	
	}	
	//SQL LAMPES
    public function sqlLampe($agregat){ 
	    $pdo = self::connexion();
		
		switch($agregat){
		    case "google":  //produits uniquement avec ean
			case "pixmania":
			case "amazon":
			case "fnac":
			case "sellermania":
                $sql="SELECT DISTINCT p.value, c.id_constructeur, c.libelle_constructeur,
                       				c.libelle_produit, c.code_type_produit, c.id_produit,
									c.id_composant, c.sous_type_composant, c.ref_interne_composant, 
									c.ref_constructeur_composant
							FROM el_products_techdata AS p
							INNER JOIN el_v_composants AS c ON p.ctr_code = c.libelle_constructeur
							AND p.lamp_code = c.ref_interne_composant

							WHERE c.code_type_produit
							IN (
							'LC', 'LO'
							)

							AND p.`datatype_code` = 'ean_code'
							GROUP BY c.ref_interne_composant
							ORDER BY `c`.`libelle_constructeur` ASC";				
				break;
            case "catsync": // union des produits avec ou sans ean
            case "priceminister":
            case "shopzilla":			
            case "idealo":			
            case "twenga":			
            case "achetez_facile":			
            case "leguide":			
            case "billiger":			
            case "comparer":			
            case "touslesprix":			
            case "trovaprezzi":			
            case "shopping":			
            case "nextag":			
            case "preisroboter":			
                    $sql="SELECT DISTINCT p.value, c.id_constructeur, c.libelle_constructeur, 
					                 c.libelle_produit, c.code_type_produit, c.id_produit,
									 c.id_composant, c.sous_type_composant, c.ref_interne_composant,
									 c.ref_constructeur_composant
								FROM el_products_techdata AS p
								RIGHT OUTER JOIN el_v_composants AS c ON p.ctr_code = c.libelle_constructeur
								AND p.lamp_code = c.ref_interne_composant
								AND p.`datatype_code` = 'ean_code'
								WHERE c.code_type_produit
								IN (
								'LC', 'LO', 'OI'
								)
								GROUP BY c.ref_interne_composant
								ORDER BY `c`.`libelle_constructeur` ASC"; 					

                break;
            default: // produit sans ean
                break;			
		}		
        $rs = $pdo->query($sql);	
        $this->setRsLampe($rs);		
    }
	
	
	// SQL VIDEOPROJ
	public function sqlVp($agregat){
	    $pdo = self::connexion();
		
		switch($agregat){
		    case "google":  //produits uniquement avec ean
			case "pixmania":
			case "amazon":
			case "fnac":
			case "sellermania":
                $sql="SELECT DISTINCT p.ean_video, c.id_constructeur, c.libelle_constructeur, 
				                 c.libelle_produit, c.code_type_produit, c.id_produit, 
								 c.id_composant, c.sous_type_composant, c.ref_interne_composant, 
								 c.ref_constructeur_composant
							FROM el_ean_video1 AS p
							INNER JOIN el_v_composants AS c ON p.ctr_code = c.libelle_constructeur
							AND p.lamp_code = c.ref_interne_composant
							AND p.ref_video = c.libelle_produit
							WHERE c.code_type_produit
							IN (
							'LC', 'LO'
							)
							ORDER BY `c`.`libelle_constructeur` ASC";				
				break;
            case "catsync": // union des produits avec et sans ean
			case "priceminister":
			case "shopzilla":
			case "idealo":
			case "twenga":
			case "achetez_facile":
			case "leguide":
			case "billiger":
			case "comparer":
			case "touslesprix":
			case "trovaprezzi":
			case "shopping":
			case "nextag":
			case "preisroboter":
			    switch($this->type_p){
				    case 'vproj': 
					case 'lamp_vproj':
                        $sql="SELECT DISTINCT p.ean_video, c.id_constructeur, c.libelle_constructeur,
                   						c.libelle_produit, c.code_type_produit, c.id_produit, 
										c.id_composant, c.sous_type_composant, c.ref_interne_composant,
										c.ref_constructeur_composant
									FROM el_ean_video1 AS p
									RIGHT OUTER JOIN el_v_composants AS c ON p.ctr_code = c.libelle_constructeur
									AND p.lamp_code = c.ref_interne_composant
									AND p.ref_video = c.libelle_produit
									WHERE c.code_type_produit
									IN (
									'LC', 'LO', 'OI'
									)
									ORDER BY `c`.`libelle_constructeur` ASC";						
						break;
                   
                       
                    default:
                        break;					
				}		
                break;
            default: // produit sans ean
                break;			
		}		
		
	    $rs = $pdo->query($sql);
        $this->setRsVp($rs);
	}
	
	//SQL LAMPES PLUS
    public function sqlLampe_param($libelle_constructeur, $ref_interne_composant){
        	
	    $pdo = self::connexion();
        $sql="  select code_type_produit, libelle_produit ref_videoprojecteur, id_constructeur,
               		   id_composant, id_produit, sous_type_composant,
					   le_prix, le_prix_uk_de, ref_constructeur_composant
				FROM el_v_composants 
				WHERE libelle_constructeur ='".$libelle_constructeur."' and ref_interne_composant = '".$ref_interne_composant."'";
        echo '<br/>'.$sql.'<br/>';				
        $rs = $pdo->query($sql);	
        $this->setRsLampePlus($rs);	
        		
    }
	
	//SQL BATTERIES
	public function sqlBatterie($agregateur){ 
	  //  $type_p='batt';
		//include("defines_flux.php");
	    $pdo = self::connexion();
	    switch($agregateur){
		    case "google":  //---produits avec ean--// 
			case "pixmania":
			case "amazon":
			case "fnac":
			case "sellermania":
				$sql = " SELECT DISTINCT el_v_composants. * , ean_code
							FROM `el_v_composants` , el_ean_batteries
							WHERE el_v_composants.libelle_constructeur <> 'PANASONIC'
							AND code_type_produit = 'RB'
								AND el_ean_batteries.sous_type_composant LIKE 'RB%'
							AND el_v_composants.libelle_produit = el_ean_batteries.libelle_produit
							AND el_v_composants.libelle_constructeur = el_ean_batteries.libelle_constructeur
								AND el_v_composants.ref_interne_composant = el_ean_batteries.ref_interne_composant
							AND el_ean_batteries.active =1 
							group by ean_code";	
			    break;
			case "catsync": //union des produits avec et sans ean	
            case "priceminister":			
            case "shopzilla":			
            case "idealo":
			case "twenga":
			case "achetez_facile":		
			case "leguide":		
			case "billiger":		
			case "comparer":		
			case "touslesprix":		
			case "trovaprezzi":		
			case "shopping":		
			case "nextag":		
			case "preisroboter":		
						$sql="SELECT DISTINCT b.ean_code, c.id_constructeur, c.libelle_constructeur,
									 c.libelle_produit, c.code_type_produit, c.id_produit, c.id_composant,
									 c.sous_type_composant, c.ref_interne_composant, c.ref_constructeur_composant,
									(SELECT price 
									        FROM el_price 
									        WHERE ctr_code=c.libelle_constructeur 
											and  price_list_id = 11 
											and  lamp_code=c.ref_interne_composant LIMIT 1) as le_prix, 
									(SELECT qty 
									        FROM el_stock
											WHERE ctr_code=c.libelle_constructeur 
									              and  lamp_code = c.ref_interne_composant LIMIT 1)as le_stock,
									(SELECT value 	
											FROM el_products_techdata 
											WHERE ctr_code=c.libelle_constructeur 
											and   datatype_code='voltage'
											and   lamp_code=c.ref_interne_composant LIMIT 1) as le_voltage,
									(SELECT value 	
											FROM el_products_techdata 
											WHERE ctr_code=c.libelle_constructeur 
											and   datatype_code='capacity'
											and   lamp_code=c.ref_interne_composant LIMIT 1) as la_capacite,
									(SELECT value 	
											FROM el_products_techdata 
											WHERE ctr_code=c.libelle_constructeur 
											and   datatype_code='dimensions'
											and   lamp_code=c.ref_interne_composant LIMIT 1) as dimensions
									FROM el_ean_batteries AS b
									RIGHT OUTER JOIN el_v_composants AS c ON b.libelle_constructeur = c.libelle_constructeur
									AND b.libelle_produit = c.libelle_produit
									AND b.ref_interne_composant = c.ref_interne_composant
									AND b.active =1
									WHERE c.code_type_produit = 'RB'
									ORDER BY `c`.`libelle_constructeur` ASC 
									";
				break;
            default: //produits sans ean
                break;			
		}			
        $rs = $pdo->query($sql);
        $this->setRsBatterie($rs);		
    }
	//SQL PUISSANCE CHARGEUR
	public function sqlPuissanceChargeur($constructeur, $ref_interne_composant){
	    $pdo = self::connexion();
	   	$sql ='SELECT value 
					FROM el_products_techdata 
					WHERE datatype_code ="power" and ctr_code="'.$constructeur.'" 
					 AND  lamp_code = "'.$ref_interne_composant.'"';
	    $rs = $pdo->query($sql);	
        $this->setRsPuissance($rs);		

	}
	//SQL CHARGEURS
	 public function sqlChargeur($agregateur){ 
	    //$type_p='charge';
		//include("defines_flux.php");
	    $pdo = self::connexion();

		 switch($agregateur){
		 //---produits avec ean---//
		    case "google":
			case "pixmania":
			case "amazon":
			case "fnac":
			case "sellermania":
			case "cdiscount":
				$sql = " SELECT DISTINCT el_v_composants. * , ean_code
							FROM `el_v_composants` , el_ean_batteries
							WHERE el_v_composants.libelle_constructeur <> 'PANASONIC'
							AND code_type_produit = 'CG'
								AND el_ean_batteries.sous_type_composant = 'CG'
							AND el_v_composants.libelle_produit = el_ean_batteries.libelle_produit
							AND el_v_composants.libelle_constructeur = el_ean_batteries.libelle_constructeur
								AND el_v_composants.ref_interne_composant = el_ean_batteries.ref_interne_composant
							AND el_ean_batteries.active =1 ";	
			    break;
			case "catsync":       //union des produits avec et sans ean
            case "priceminister":
            case "shopzilla":
            case "idealo":
            case "twenga":
            case "achetez_facile":
            case "leguide":
            case "billiger":
            case "comparer":
            case "touslesprix":
            case "trovaprezzi":
            case "shopping":
            case "nextag":
            case "preisroboter":
					$sql="SELECT DISTINCT b.ean_code, c.id_constructeur, c.libelle_constructeur,
									c.libelle_produit, c.code_type_produit, c.id_produit, c.id_composant,
									c.sous_type_composant, c.ref_interne_composant, c.ref_constructeur_composant,
									(SELECT price 
									        FROM el_price 
									        WHERE ctr_code=c.libelle_constructeur 
											and   price_list_id = 11 
											and lamp_code=c.ref_interne_composant LIMIT 1) as le_prix, 
									(SELECT qty 
									        FROM el_stock
											WHERE ctr_code = c.libelle_constructeur 
									        and  lamp_code = c.ref_interne_composant LIMIT 1) as le_stock,
									(SELECT value 	
											FROM el_products_techdata 
											WHERE ctr_code=c.libelle_constructeur 
											and   datatype_code='voltage'
											and   lamp_code=c.ref_interne_composant LIMIT 1) as le_voltage,
									(SELECT value 	
											FROM el_products_techdata 
											WHERE ctr_code=c.libelle_constructeur 
											and   datatype_code='power'
											and   lamp_code=c.ref_interne_composant LIMIT 1) as la_capacite,
									(SELECT value 	
											FROM el_products_techdata 
											WHERE ctr_code=c.libelle_constructeur 
											and   datatype_code='dimensions'
											and   lamp_code=c.ref_interne_composant LIMIT 1) as dimensions
									FROM el_ean_batteries AS b
									RIGHT OUTER JOIN el_v_composants AS c ON b.libelle_constructeur = c.libelle_constructeur
									AND b.libelle_produit = c.libelle_produit
									AND b.ref_interne_composant = c.ref_interne_composant
									AND b.active =1
									WHERE c.code_type_produit = 'CG'
									ORDER BY `c`.`libelle_constructeur` ASC
									";	
				break;
            default: //produits sans ean
                break;			
		}						
        $rs = $pdo->query($sql);
        $this->setRsChargeur($rs); 		
    }
        //SQL BATTERIE & CHARGEURS selon un EAN
	public function sqlBatterie_Param($ean){
	    $pdo = self::connexion();
	    $sql = "SELECT libelle_produit, libelle_constructeur, ref_interne_composant,active FROM el_ean_batteries WHERE ean_code = '".$ean."'";
	    $rs  = $pdo->query($sql);
        $this->setRsBattParam($rs); 
	}
	
	//SQL PRODUIT ASSOCIE
	public function sqlProduitAssocie($libelle_produit, $type_prod){ 
	    $type_p = $type_prod;
		$type_produit="";
		include("defines_flux.php");
		$pdo = self::connexion();
		switch($type_prod){
		    case 'batt':   $type_produit ="CG"; break;
			case 'charge': $type_produit ="RB"; break;
			default:break;
		}
	    $sql= "SELECT distinct el_v_composant.*, ean_code 
					FROM   el_v_composants, el_ean_batteries
					WHERE  libelle_produit   =  '".$libelle_produit."'
					el_v_composants.libelle_constructeur <> 'PANASONIC' 
                    AND    code_type_produit =  '".$type_produit."' 
                                       AND el_ean_batteries.sous_type_composant = '".$type_produit."'
					AND el_v_composants.libelle_produit = el_ean_batteries.libelle_produit
					AND el_v_composants.libelle_constructeur = el_ean_batteries.libelle_constructeur
					AND el_ean_batteries.active=1";
		$rs = $pdo->query($sql);
        $this->setRsProduitAssocie($rs); 		
					
	}
	//SQL PRODUCT_ID pour PIX
	public function sqlProduct_id($ref_lampe){
	    $type = 'lamp';
		$pdo  = self::connexion();
		$sql  = "select min(products.products_id) as products_id
					  from categories_description, categories, products_description, products
					  where products_name in ( '" . $ref_lampe . "' )
					  and categories.categories_id = categories_description.categories_id
					  and products.products_id = products_description.products_id
					  and categories.categories_id = products.master_categories_id";
		$rs = $pdo->query($sql);		  
	    self::$product_id = $rs;
	}
	//SQL PRICE LAMPES
	public function sqlPriceLampe($price_list_id, $ref_interne_composant, $libelle_constructeur){ 
	    $type_p='lamp';
		//include("defines_flux.php");
	    $pdo = self::connexion();
        $sql="  SELECT price FROM el_price WHERE price_list_id =".$price_list_id.
								                " and lamp_code ='".$ref_interne_composant."'".
											    " and ctr_code  ='".$libelle_constructeur."'";	
											
        $rs = $pdo->query($sql); 
        $this->setRsLampePrix($rs);		
    }
	
	//SQL STOCK
	public function sqlLampeStock($ref_interne_composant){ 
	$type_p ='lamp';
	    $pdo = self::connexion();
	    $sql= "SELECT qty FROM el_stock WHERE lamp_code = '".$ref_interne_composant."'";
	    $rs = $pdo->query($sql); 
        $this->setRsLampeStock($rs);
	}
	
	//LE TAUX DE CHANGE euro<--->livre
	public function sqlTauxDeChange(){
	    $type_p = "lamp";
	    $pdo = self::connexion();
	    $sql="SELECT  `value`
				FROM lampe_en.currencies
				WHERE  `code` =  'GBP' ";
		$rs = $pdo->query($sql); 
        self::$tauxDeChange = $rs;
	}
	
	//LA TVA 
	public function sqlTva(){
		$type_p = "lamp";
		$pdo = self::connexion();
		$sql=" SELECT tax_rate
				FROM lampe_en.tax_rates
				WHERE tax_rates_id = 4";
	    $rs = $pdo->query($sql); 
        self::$tva = $rs;
	}

}

?>