
<?php
class Position
{
    // d�claration d'une propri�t�
    public $_id = NULL;
	public $_user =NULL;
	public $_datept = NULL;
        public $_timestamp = NULL;
	public $_lat = NULL;
	public $_long = NULL;
	public $_alt = NULL;
	public $_bat = NULL;
        
        // Todo : need to add acc and networkstrength
	
	public function __construct($id, $user, $datept, $timestamp, $lat, $long, $alt, $bat )
	{
		$this->_id = $id;
		$this->_user = $user;
		$this->_datept = $datept;
                $this->_timestamp = $timestamp;
		$this->_lat = $lat;
		$this->_long = $long;
		$this->_alt = $alt;
		$this->_bat = $bat;
	}

	public function getid()
    {
        return $this->_id;   
    }
	
	
    // d�claration des m�thodes
    public function displayid() {
        echo $this->_id."<br>";
    }
	// d�claration des m�thodes
    public function displayPosition() {
        echo "Pos:".$this->_id." ; ".$this->_user." ; ".$this->_datept." ; ".$this->_timestamp." ; ".$this->_lat." ; ".$this->_long." ; ".$this->_alt." ; ".$this->_bat."<br>";
    }
    
    public function displayNicelyPosition() {
        echo "ID:".$this->_id."<br> User: ".$this->_user."<br>Date: ".$this->_datept."<br> Latitude: ".$this->_lat."<br>Longitude: ".$this->_long."<br>Altitude: ".$this->_alt."<br>Battery: ".$this->_bat."<br>";
    }
}
?>
