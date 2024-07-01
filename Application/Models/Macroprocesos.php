<?php 

use MVC\Model;

class ModelsMacroprocesos extends Model {

    public function macroprocesos($activo) {
        // sql statement
        $sql = "SELECT * FROM " . DB_PREFIX . "macroproceso WHERE activo = $activo";
    
        // exec query
        $query = $this->db->query($sql);
    
        // Initialize data as an empty array
        $data = [];
    
        // Check if there are any rows
        if ($query->num_rows) {
            foreach($query->rows as $value) {
                $data['data'][] = $value;
            }
        } else {
            $data['data'] = [];
        }
    
        // Return the data array
        return $data;
    }
}