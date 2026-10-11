<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../modelos/conexion.php";

class TableData
{
    private $_db;

    public function __construct()
    {
        try {
            $this->_db = Conexion::conectar();
        } catch (PDOException $e) {
            error_log("Failed to connect to database: " . $e->getMessage());
        }
    }

    public function get($table, $index_column, $columns)
    {
        // Validar sesión activa para proteger los endpoints Server-Side
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
            echo json_encode(array(
                "sEcho" => intval($_GET['sEcho'] ?? 0),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => array()
            ));
            return;
        }

        // Validar identificadores SQL contra inyección de nombres de tabla/columna
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table) || !preg_match('/^[a-zA-Z0-9_]+$/', $index_column)) {
            echo json_encode(array(
                "sEcho" => intval($_GET['sEcho'] ?? 0),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => array()
            ));
            return;
        }

        foreach ($columns as $col) {
            if ($col !== ' ' && !preg_match('/^[a-zA-Z0-9_]+$/', $col)) {
                echo json_encode(array(
                    "sEcho" => intval($_GET['sEcho'] ?? 0),
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => array()
                ));
                return;
            }
        }

        // Paging
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && isset($_GET['iDisplayLength']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        // Ordering
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            $sortingCols = intval($_GET['iSortingCols'] ?? 0);
            for ($i = 0; $i < $sortingCols; $i++) {
                $sortColIdx = intval($_GET['iSortCol_' . $i] ?? -1);
                if (isset($columns[$sortColIdx]) && ($_GET['bSortable_' . $sortColIdx] ?? '') == "true") {
                    $sortDir = (strcasecmp($_GET['sSortDir_' . $i] ?? 'ASC', 'ASC') == 0) ? 'ASC' : 'DESC';
                    $sOrder .= "`" . $columns[$sortColIdx] . "` " . $sortDir . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if (trim($sOrder) == "ORDER BY") {
                $sOrder = "";
            }
        }

        // Filtering (Global search)
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $searchableCols = array();
            for ($i = 0; $i < count($columns); $i++) {
                if (! isset($_GET['bSearchable_' . $i]) || $_GET['bSearchable_' . $i] == "true") {
                    $searchableCols[] = "`" . $columns[$i] . "` LIKE :search";
                }
            }
            if (!empty($searchableCols)) {
                $sWhere = "WHERE (" . implode(" OR ", $searchableCols) . ")";
            }
        }

        // Individual column filtering
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "`" . $columns[$i] . "` LIKE :search" . $i . " ";
            }
        }

        // SQL query to get data to display
        $sQuery = "SELECT `" . str_replace(" , ", " ", implode("`, `", $columns)) . "` FROM `" . $table . "` " . $sWhere . " " . $sOrder . " " . $sLimit;
        $statement = $this->_db->prepare($sQuery);

        // Bind parameters
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $sWhere !== "") {
            $statement->bindValue(':search', '%' . $_GET['sSearch'] . '%', PDO::PARAM_STR);
        }
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != '') {
                $statement->bindValue(':search' . $i, '%' . $_GET['sSearch_' . $i] . '%', PDO::PARAM_STR);
            }
        }

        $statement->execute();
        $rResult = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Filtered total count (compatible with MySQL 8+ and MariaDB)
        $sCountFiltered = "SELECT COUNT(`" . $index_column . "`) FROM `" . $table . "` " . $sWhere;
        $stmtFiltered = $this->_db->prepare($sCountFiltered);
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $sWhere !== "") {
            $stmtFiltered->bindValue(':search', '%' . $_GET['sSearch'] . '%', PDO::PARAM_STR);
        }
        for ($i = 0; $i < count($columns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && isset($_GET['sSearch_' . $i]) && $_GET['sSearch_' . $i] != '') {
                $stmtFiltered->bindValue(':search' . $i, '%' . $_GET['sSearch_' . $i] . '%', PDO::PARAM_STR);
            }
        }
        $stmtFiltered->execute();
        $iFilteredTotal = (int)$stmtFiltered->fetchColumn();

        // Get total number of rows in table/view
        $sQueryTotal = "SELECT COUNT(`" . $index_column . "`) FROM `" . $table . "`";
        $iTotal = (int)$this->_db->query($sQueryTotal)->fetchColumn();

        // Output
        $output = array(
            "sEcho" => intval($_GET['sEcho'] ?? 1),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        // Return array of values
        foreach ($rResult as $aRow) {
            $row = array();
            for ($i = 0; $i < count($columns); $i++) {
                if ($columns[$i] == "version") {
                    $row[] = ($aRow[$columns[$i]] == "0") ? '-' : $aRow[$columns[$i]];
                } else if ($columns[$i] != ' ') {
                    $row[] = $aRow[$columns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }
}

if (!headers_sent()) {
    header('Pragma: no-cache');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Content-Type: application/json; charset=utf-8');
}

// Create instance of TableData class
$table_data = new TableData();
