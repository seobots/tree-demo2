<?
	class Test2 {
		var $_database;
		var $_host;
		var $_user;
		var $_password;
		var $_db;
		var $tree;
#-------------------------------------------------------------------------------------------------#  
		function __construct(){
			require_once('config.php');
			$this->_database	= $database;
			$this->_host		= $host;
			$this->_user		= $user;
			$this->_password	= $password;
			if (!empty($this->_database) &&
			    !empty($this->_host) &&
			    !empty($this->_user)/* &&
			    !empty($this->_password)*/) {
					$database  = $this->_database;
					$host      = $this->_host;
					$user      = $this->_user;
					$password  = $this->_password;
					try{
						$this->_db = new PDO('mysql:host='.$host.';dbname='.$database,$user,$password,array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
					}
					catch(PDOException $e){
						die("Error: ".$e->getMessage());
					}
			    }
		}
#-------------------------------------------------------------------------------------------------#  
		function getNodeTree($parent=0, $all=0) {
			$sql = "SELECT *, inheritance AS inh, (SELECT count( id_node ) FROM node WHERE inheritance LIKE concat( inh, '|%' )) AS sum FROM `node` WHERE `parent` = :parent";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':parent', $parent);
			$query->execute();
			$data = $query->fetchAll(PDO::FETCH_ASSOC);

			foreach ($data as $key=>$value) {
				$value['state'] = ($value['sum']>0) ? '+' : '-';
				$this->tree[] = $value;
				if ($all!=0) $this->getNodeTree($value['id_node'], $all);
			}
			return $this->tree;
		}
#-------------------------------------------------------------------------------------------------#  
		function deleteNode($id=0) {			
			$sql = "delete from `node` where `inheritance` like concat( (SELECT `inheritance` FROM (SELECT `inheritance` FROM `node` WHERE `id_node` = :id) as `tmp`), '%' )";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':id', $id);
			$query->execute();
		}
#-------------------------------------------------------------------------------------------------#  
		function addNode($parent=0, $name='') {		
			$sql = "insert into node (`name`,`parent`,`inheritance`) values (:name, :parent, (select concat(inheritance,'|', sum) from (select inheritance, (select (`id_node` +1) from node as n ORDER BY `n`.`id_node` DESC LIMIT 1) as sum from node where id_node = :parent) as tmp limit 1))";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':name', $name);
			$query->bindParam(':parent', $parent);
			$query->execute();
		}
#-------------------------------------------------------------------------------------------------#  
	}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$ob = new Test2();

if (!isset($_GET['all'])) echo json_encode($ob->getNodeTree($id));
else echo json_encode($ob->getNodeTree($id,1));
?>