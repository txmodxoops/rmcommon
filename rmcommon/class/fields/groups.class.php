<?php
// $Id: groups.class.php 928 2012-01-15 06:56:56Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Clase para la creación de campos para el manejo de
 * grupos de usuarios XOOPS
 */
class RMFormGroups extends RMFormElement
{
	private $_multi = 0;
	private $_select = array();
	/**
	 * Posibles valores
	 * 0 = Select, 1 = Menu
	 */
	private $_showtype = 0;
	private $_showdesc = 0;
	private $_cols = 2;
	
	/**
	 * Constructor de la clase
	 * @param string $caption Texto de la etiqueta
	 * @param string $name Nombre del campo
	 */
	function __construct($caption, $name, $multi=0, $type=0, $cols=2, $select=array()){
		$this->setCaption($caption);
		$this->setName($multi ? str_replace('[]', '', $name).'[]' : $name);
		if (isset($_REQUEST[$name])) $this->_select = $_REQUEST[$name];
		$this->_multi = $multi;
		$this->_showtype = $type;
		$this->_cols = $cols;
		
		if (isset($_REQUEST[$this->getName()])){
			$this->_select = $_REQUEST[$this->getName()];
		} else {		
			$this->_select = $select;
		}
		
	}
	/**
	 * Establece el comportamiento de seleccion del campo groups.
	 * Si $_multi = 0 entonces olo se puede seleccionar un grupo a la vez. En caso contrario
	 * el campo permite la selección de múltiples grupos
	 * @param int $value 1 o 2
	 */
	public function setMulti($value){
		if ($value==0 || $value==1){
			$this->setName($value ? str_replace('[]','',$this->getName()).'[]' : str_replace('[]','',$this->getName()));
			$this->_multi = $value;
		}
	}
	/**
	 * Devuelve el valor multi del campo groups.
	 * @return int
	 */
	public function getMulti(){
		return $this->_multi;
	}
	/**
	 * Indica los elementos seleccionados por defecto.
	 * Este valor debe ser pasado como un array conteniendo los ideneitificadores
	 * de los grupos (ej. array(0,1,2,3)) o bien como una lista delimitada por comas
	 * conteniendo tambien los identificadores de grupos (ej, 1,2,3,4)
	 * @param array $value Identificadores de los grupos
	 * @param string $value Lista delimitada por comas con identificadores de los grupos
	 */
	public function setSelect($value){
		if (is_array($value)){
			$this->_select = $value;
		} else {
			$this->_select = explode(',',$value);
		}
	}
	/**
	 * Devuelve el array con los identificadores de los grupos
	 * seleccionado por defecto.
	 * @return array
	 */
	public function getSelect(){
		return $this->_select;
	}
	/**
	 * Establece la forma en que se mostrarán los grupos.
	 * Esto puede ser en forma de lista o en forma de menu
	 * @param int $value 0 ó 1
	 */
	public function setShowType($value){
		if ($value==0 || $value==1) $this->_showtype = $value;
	}
	/**
	 * Devuelve el identificador de la forma en que se muestran los elementos
	 * @return int
	 */ 
	public function getShowType(){
		return $this->_showtype;
	}
	/**
	 * Establece si se muestra la descripción de cada grupo o no.
	 * Esta valor solo puede afectar cuando lso grupos se muestran
	 * en forma de menu.
	 * @param int $value 0 ó 1
	 */
	public function showDesc($value){
		if ($value==0 || $value==1) $this->_showdesc = $value;
	}
	/**
	 * Devuelve si esta activa o no la opción para mostrar la descrpición de los grupos
	 * @return int
	 */
	public function getShowDesc(){
		return $this->_showdesc;
	}
	/**
	 * Establece el número de columnas para el menu.
	 * Cuando los grupos se mostrarán en forma de menú esta opción 
	 * permite especificar el número de columnas en las que se ordenarán.
	 * @param int $value Número de columnas
	 */
	public function setCols($value){
		if ($value>0) $this->_cols = $value;
	}
	/**
	 * Devuelve el número de columnas del menú.
	 * @return int
	 */
	public function getCols(){
		return $this->_cols;
	}
	/**
	 * Genera el código HTML para mostrar la lista o menú de grupos
	 * @return string
	 */
	public function render(){
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$result = $db->query("SELECT * FROM ".$db->prefix("groups")." ORDER BY `name`");
		$rtn = '';
		$col = 1;
		
		$typeinput = $this->_multi ? 'checkbox' : 'radio';
		$name = $this->getName();

		if ($this->_showtype){
			$rtn = "<ul class='groups_field_list ".$this->id()."_groups'>";
			$rtn .= "<li><label><input type='$typeinput' name='$name' id='".$this->id()."' value='0'";
			if (is_array($this->_select)){
				if (in_array(0, $this->_select)){
					$rtn .= " checked='checked'";
				}
			}
			$rtn .= " data-checkbox=\"$name-chks\"> ".__('All','rmcommon')."</label></li>";
			while ($row = $db->fetchArray($result)){
				
				$rtn .= "<li><label><input type='$typeinput' name='$name' id='".$this->id()."' value='$row[groupid]'";
				if (is_array($this->_select)){
					if (in_array($row['groupid'], $this->_select)){
						$rtn .= " checked='checked'";
					}
				}
				$rtn .= " data-oncheck=\"$name-chks\"> $row[name]</label>";
				
				if ($this->_showdesc){
					$rtn .= "<br /><small style='font-size: 10px;' class='description'>$row[description]</small>";
				}
				
				$rtn .= "</li>";
				
				$col++;
				
			}
			$rtn .= "</ul>";
		} else {
			
			$rtn = "<select name='$name'";
			$rtn .= $this->_multi ? " multiple='multiple' size='5'" : "";
			$rtn .= " class=\"form-control ".$this->getClass()."\"><option value='0'";
			if (is_array($this->_select)){
				if (in_array(0, $this->_select)){
					$rtn .= " selected='selected'";
				}
			} else {
				$rtn .= " selected='selected'";
			}
			
			$rtn .= ">".__('All','rmcommon')."</option>";
			
			while ($row = $db->fetchArray($result)){
				$rtn .= "<option value='$row[groupid]'";
				if (is_array($this->_select)){
					if (in_array($row['groupid'], $this->_select)){
						$rtn .= " selected='selected'";
					}
				}
				$rtn .= ">".$row['name']."</option>";
			}
			
			$rtn .= "</select>";
		}
		
		return $rtn;
	}
	
}
