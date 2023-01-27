<?php 

    include_once 'dbcontroller.php';


    class ReportController {
        private $table_name = 'reports';
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


        public function getUnpassedReports($id){
            $query = "
                SELECT * 
                FROM activities
                WHERE `id`  not in (SELECT {$this->table_name}.activity_id FROM reports WHERE faculty_id = :id);";

            $stmt = $this->connection->prepare($query);
            $stmt->execute([':id' => $id]);
    
            $data = [];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function getUnreviewedReports() {
            $query = "
                SELECT *
                FROM reports
                WHERE (remarks = \"\" OR remarks IS NULL)";

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


        public function getAllReportsWithSender($remarks='%'){
            $query = "
                    SELECT CONCAT_WS(', ', lastname, firstname) as faculty_name,
                    activity_name, activities.description as activity_description, activities.activity_date as date,
                    reports.id as id, reports.description as description, attachment, remarks
                FROM reports
                INNER JOIN activities
                ON activities.id = reports.activity_id
                INNER JOIN faculties
                ON faculties.user_id = reports.faculty_id
                WHERE remarks LIKE '$remarks';
            ";

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


        public function renderReportTable($id){
            foreach ($this->getUnpassedReports($id) as $row) {
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
                            <button class="btn bg-red-900 text-white w-full"  data-bs-toggle="modal" data-bs-target="#reportActivity-{$row['id']}">
                                Create Report
                            </button>
                        </div>
                    </td>


                    <div class="modal fade" id="reportActivity-{$row['id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <form action="/scripts/routes/report_add_report.route.php?id={$row['id']}" method="post" enctype="multipart/form-data">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Activity Report</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-span-full form-floating mb-3">
                                        <input type="file" class="form-control" id="name" name="name" placeholder="">
                                        <label for="name">Attachment</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" placeholder="1st" aria-label="1st" id="description" name="description" aria-describedby="basic-addon1" required>
                                        <label for="description">Activity Description</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger text-red-900" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn bg-red-900 text-white text-sky-900">Send Report</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                EOL;
                echo "</tr>";
            }
        }




        

        public function renderTable($remarks='%'){
            $origin = Env::$env['URL'];

            foreach ($this->getAllReportsWithSender($remarks) as &$row) {
                $date = strtotime($row['date']);
                $date = date("m-d-Y", $date);

                $ext = end(explode('.', $row['attachment']));

                echo "<tr class='my-2'>";
                echo "    <td>{$row['faculty_name']}</td>";
                echo "    <td>{$row['activity_name']}</td>";
                echo "    <td>{$row['activity_description']}</td>";
                echo "    <td>{$date}</td>";
                echo "    <td>{$row['description']}</td>";
                echo "    <td>{$row['remarks']}</td>";
                echo <<<EOL
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn bg-red-900 text-white text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn btn-success w-full">
                                        <a href="/view-file.php?filename={$row['attachment']}&ext={$ext}" target="_blank">
                                            Download
                                        </a>
                                    </button>
                                    <button class="btn bg-red-900 text-white w-full"  data-bs-toggle="modal" data-bs-target="#reviewReport-{$row['id']}">
                                        Review
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>

                    <div class="modal fade" id="reviewReport-{$row['id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <form action="/scripts/routes/report_review_report.route.php?id={$row['id']}" method="post">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Review Report</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" name="remarks" id="remarks">{$row['remarks']}</textarea>
                                            <label for="remarks">Remarks</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger text-red-900" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn bg-red-900 text-white text-sky-900">Review</button>
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