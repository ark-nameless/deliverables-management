<?php 

    include_once 'dbcontroller.php';


    class SchoolYearController {
        private $table_name = 'school_years';
        private $connection = null;

        public function __construct(){
            $db = new DBController();
            $this->connection = $db->getConnection();
        }

        public function add($data){
            $prep = array();
            foreach ($data as $k => $v) {
                $v = htmlspecialchars($v);
                $prep[':' . $k] = $v;
            }

            $query = "INSERT INTO `{$this->table_name}` ( " . implode(', ', array_keys($data)) . ") VALUES (" . implode(', ', array_keys($prep)) . ")";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute($prep)) {
                return true;
            }
            return false;
        }

        public function edit($id, $data){
            $set_query = '';
            $prep = [];
            $i = 0;
            foreach ($data as $k => $v) {
                $v = htmlspecialchars($v);
                $prep[':' . $k] = $v;
                $set_query .= "$k=:$k";
                if (++$i < count($data)){
                    $set_query .= ', ';
                }
            }

            $query = "UPDATE `{$this->table_name}` SET $set_query WHERE id=:id";
            $stmt = $this->connection->prepare($query);
            $prep[':id'] = $id;

            if ($stmt->execute($prep)) {
                return true;
            }
            return false;
        }

        public function delete($id){
            $query = "DELETE FROM {$this->table_name} WHERE id=:id";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute([':id' => $id])) {
                return true;
            }
            return false;
        }

        public function getAll() {
            $query = "SELECT * FROM {$this->table_name}";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
    
            $data = [];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function renderSelect() {
            echo <<<EOL
                <select name="school_year_id" class="form-select" aria-label="Default select example">
            EOL;
            foreach($this->getAll() as &$row){
                echo <<<EOL
                    <option value="{$row['id']}">{$row['start_year']}-{$row['end_year']}</option>
                EOL;
            }
            echo "</select>";
        }

        public function renderTable() {
            foreach ($this->getAll() as $row) {
                echo "<tr class='my-2'>";
                echo "    <td>{$row['start_year']}</td>";
                echo "    <td>{$row['end_year']}</td>";
                echo <<<EOL
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn btn-primary w-full"  data-bs-toggle="modal" data-bs-target="#editSchoolYear-{$row['id']}">
                                        Edit
                                    </button>
                                    <a href="scripts/routes/schoolyear_delete_sy.route.php?id={$row['id']}" class="btn btn-danger w-full" onclick="return confirm('Are you sure you want to delete {$row['semester']}?');">
                                        Remove
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>


                    <div class="modal fade" id="editSchoolYear-{$row['id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <form action="/scripts/routes/schoolyear_edit_sy.route.php?id={$row['id']}" method="post">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit School Year</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Starting Year</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" placeholder="2020" aria-label="2020" name="start_year"  value="{$row['start_year']}" aria-describedby="basic-addon1">
                                    </div>
                                    <label class="form-label">Ending Year</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" placeholder="2021" aria-label="2021" name="end_year"  value="{$row['end_year']}" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-red-900" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary text-sky-900">Edit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                EOL;
                echo "</tr>";
            }
        }




    }