<?php 

    include_once 'dbcontroller.php';


    class ActivityController {
        private $table_name = 'activities';
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
        

        public function renderTable() {

            foreach ($this->getAll() as $row) {
                $dt = strtotime($row['activity_date']);
                $act_date = date("m/d/Y", $dt);
                $str_date = date('Y-m-d', $dt);

                echo "<tr class='my-2'>";
                echo "    <td>{$row['activity_name']}</td>";
                echo "    <td>{$row['description']}</td>";
                echo "    <td>{$act_date}</td>";
                echo <<<EOL
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn bg-red-900 text-white text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn bg-red-900 text-white w-full"  data-bs-toggle="modal" data-bs-target="#editActivity-{$row['id']}">
                                        Edit
                                    </button>
                                    <a href="scripts/routes/activity_delete_activity.route.php?id={$row['id']}&activity_name={$row['activity_name']}" class="btn btn-danger w-full" onclick="return confirm('Are you sure you want to delete {$row['semester']}?');">
                                        Remove
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>


                    <div class="modal fade" id="editActivity-{$row['id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <form action="/scripts/routes/activity_edit_activity.route.php?id={$row['id']}" method="post">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Semester</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" value="{$row['activity_name']}" placeholder="1st" aria-label="1st" id="activity_name" name="activity_name" aria-describedby="basic-addon1" required>
                                        <label for="activity_name">Activity Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Leave a comment here" name="description" rows="4" id="description">{$row['description']}</textarea>
                                        <label for="description">Activity Description</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="date" class="form-control" value="$str_date" placeholder="1st" aria-label="1st" id="activity_date" name="activity_date" aria-describedby="basic-addon1" required>
                                        <label for="activity_date">Activity Date</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-red-900" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn bg-red-900 text-white text-sky-900">Edit</button>
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