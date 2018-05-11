<?php

 class DB{ 
 //INSERT query   
      public static function insert($con, $table_name, $data)  
      {  
           $string = "INSERT INTO ".$table_name." (";            
           $string .= implode(",", array_keys($data)) . ') VALUES (';            
           $string .= "'" . implode("','", array_values($data)) . "')";  
           if(mysqli_query($con, $string))
                return true; 
           else  
           {  
              echo "Error: " . $string . "<br>" . $con->error;
              return false;  
           }  
      }  
//SELECT query
      public static function select($con, $table_name, $data=array(), $where)  
      {  
           $array = array();  
           $query = "SELECT ".implode(",", $data)." FROM ".$table_name." WHERE ".$where;
           $result = mysqli_query($con, $query);  
           if($result->num_rows > 0)
           {
               while($row = $result->fetch_object())
                    $array[]= $row;
               return $array;   
           }
           else
           {
              return false; 
           }      
      }  
// DELETE query
      public function delete($con, $table_name, $where_condition)  
      {  
         $condition = '';  
         foreach($where_condition as $key => $value)  
              $condition .= $key . " = '".$value."' AND ";

         $condition = substr($condition, 0, -5);  
         $query = "DELETE FROM ".$table_name." WHERE ".$condition."";
         if(mysqli_query($con, $query))
            return true;
        else 
            return false;
      }
// UPDATE query
      public static function update($con, $table_name, $fields, $where_condition)  
      {  
           $query = '';  
           $condition = '';  

           foreach($fields as $key => $value)  
                $query .= $key . "='".$value."', "; 
           $query = substr($query, 0, -2);

           foreach($where_condition as $key => $value)
                $condition .= $key . "='".$value."' AND ";
           $condition = substr($condition, 0, -5);

           $query = "UPDATE ".$table_name." SET ".$query." WHERE ".$condition."";  
           if(mysqli_query($con, $query)) 
                return true;  
      }
}  

?>