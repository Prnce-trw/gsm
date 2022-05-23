<?php
    define('DB_HOST', 'localhost');
    define('DB_Username', 'root');
    define('DB_Password', '');
    define('DB_Name', 'GSM');

    class DB_con {
        function __construct() {
            $conn = mysqli_connect(DB_HOST, DB_Username, DB_Password, DB_Name);
            $this->dbcon = $conn;

            if (mysqli_connect_errno()) {
                echo "Fail to connect". mysqli_connect_error();
            }
        }

        public function insert($gas_filling)
        {
            $result = mysqli_query($this->dbcon, "INSERT INTO tb_head_preorder(head_po_fillstation) VALUES('$gas_filling')");
            return $result;
        }

        public function fetchdata()
        {
            $result = mysqli_query($this->dbcon, "SELECT * FROM tb_head_preorder");
            return $result;
        }
    }
?>