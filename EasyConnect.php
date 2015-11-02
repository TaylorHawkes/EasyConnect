<?php

class EasyConnect 
{
    
    //defaulting to dev
    protected static $hostname;
    protected static $username;
    protected static $password; 
    protected static $db_name; 
    protected static $conn;
    protected static $instance; 

    protected function __construct($hostname,$username,$password,$db_name=null) { 
        
     
        self::$hostname=$hostname;
        self::$username=$username;
        self::$password=$password;
        self::$db_name=$db_name;
         
        self::$conn = mysqli_connect(self::$hostname, self::$username, self::$password);
        
        if (!self::$conn)
        { 
            die('Critical Stop Error: Database Error<br />' . mysqli_error(self::$conn));
        }
        
        if($db_name){
            $selected_db =  mysqli_select_db(self::$conn,$db_name);
            if(!$selected_db){
                die('Cant select database');
            }   
        }
    } 


    //no cloning aloud 
    private function __clone()
    {
    } 

    public static function getInstance($hostname,$username,$password,$db_name=null) 
    { 
        if (!self::$instance) 
        { 
        self::$instance = new EasyConnect($hostname,$username,$password,$db_name); 
        } 

        return self::$instance; 
    } 
    
    /*
     * $param string $sql
     * @param bool, $return_last_insert_id
     * return:
     * see php mysql_query,
     * note: if $return_last_insert_id is true - will return autoincrement if it exhists, 0 if it does not, false if query didnt work
     *
     */
    public function query($sql, $return_last_insert_id = false)
    {
        $query = mysqli_query(self::$conn,$sql) OR die("Error: could not run query:".mysqli_error(self::$conn));
        
        if($return_last_insert_id){
                return  mysqli_insert_id(self::$conn); 
        }

        return $query;
      
    }
    

    public function fetchRow($sql)
    {
        $query = mysqli_query(self::$conn,$sql) OR die("Error: could not run query:".mysqli_error(self::$conn));
        
        $row = mysqli_fetch_row($query);
        return $row;
    }

    
     
    public function fetchAssoc($sql)
    {
        $result = mysqli_query(self::$conn,$sql ) OR die("Error: could not run query:".mysqli_error(self::$conn));
        
        $all_results = array();
        while($row=mysqli_fetch_assoc($result))
        {
            $all_results[]=$row;
        
        } 
        return $all_results;
    }

   
    public static function get_conn(){
        return self::$conn;

    }

}

?>
