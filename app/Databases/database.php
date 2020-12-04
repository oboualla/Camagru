<?php
    class Database extends PDO{
        public function __construct() {
            try {
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                parent::__construct('mysql:host=' . __DBHOST__ . ';dbname=' . __DBNAME__, __DBUSER__ , __DBPASSWD__, $options);
            }
            catch (PDOException $e) {
                $_SESSION['Message'] = $e->getMessage();
                return (['success' => false, 'error' => true, 'data' => NULL, 'message' => $e->getMessage()]);
            }
        }
        public function exec_query($sql, $vars) {
            try {
                if (!($stm = parent::prepare($sql)))
                    return (['success' => false, 'error' => true, 'data' => NULL, 'message' => 'fail to connect with database']);
                $stm->execute($vars);
                return (['success' => true, 'error' => false, 'data' => $stm->fetchAll(), 'message' => NULL]);
            }
            catch (Exception $e) {
                return (['success' => false, 'error' => true, 'data' => NULL, 'message' => $e->getMessage()]);
            }
        }
    }
?>