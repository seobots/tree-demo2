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
				$value['state'] = $this->hasNode($value['id_node']);
				$this->tree[] = $value;
				if ($all!=0) $this->getNodeTree($value['id_node'], $all);
			}
			return $this->tree;
		}
#-------------------------------------------------------------------------------------------------#  
		function hasNode($parent=0) {
			$sql = "SELECT count(id_node) AS sum FROM `node` WHERE `parent` = :parent";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':parent', $parent);
			$query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);

			if ($data['sum']>0) return '+';
			else return '-';
		}
#-------------------------------------------------------------------------------------------------#  
		function deleteNode($id=0) {
			$sql = "select inheritance from node where id_node = :id";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':id', $id);
			$query->execute();
			$get = $query->fetch(PDO::FETCH_ASSOC);
			
			$sql = "delete from node where inheritance like concat( :inheritance, '%' )";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':inheritance', $get['inheritance']);
			$query->execute();
		}
#-------------------------------------------------------------------------------------------------#  
		function addNode($parent=0, $name='') {
			$sql = "select inheritance, (select count(id_node) from node as n where n.parent = node.id_node) as sum from node where id_node = :id";
			$query = $this->_db->prepare($sql);
			$query->bindParam(':id', $parent);
			$query->execute();
			$get = $query->fetch(PDO::FETCH_ASSOC);
		
			$sql = "insert into node (`name`,`parent`,`inheritance`) values (:name, :parent, :inheritance)";
			$query = $this->_db->prepare($sql);
			$inheritance = $get['inheritance']."|".($get['sum']+1);
			$query->bindParam(':name', $name);
			$query->bindParam(':parent', $parent);
			$query->bindParam(':inheritance', $inheritance);
			$query->execute();
		}
#-------------------------------------------------------------------------------------------------#  
	}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$ob = new Test2();


if (!isset($_GET['all'])) echo json_encode($ob->getNodeTree($id));
else echo json_encode($ob->getNodeTree($id,1));
?>