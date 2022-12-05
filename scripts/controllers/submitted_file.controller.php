<?php 

    include_once 'dbcontroller.php';

    include_once 'semester.controller.php';
    include_once 'school_year.controller.php';

    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/env.core.php';


    class SubmittedFileController {
        private $table_name = 'submitted_deliverables';
        private $table_deliverables = 'deliverables';
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

        public function get($id){
            $query = "
                SELECT *
                FROM {$this->table_name}
                WHERE faculty_id = :id;";

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

        public function getUnattended() {
            $query = "
                SELECT *
                FROM {$this->table_name}
                WHERE status LIKE \"Pending\";";

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

        public function getAllSubmitted(){ 
            $query = "
                SELECT *
                FROM {$this->table_name};";

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

        public function getAllSubmittedBy($id){ 
            $query = "
                SELECT CONCAT_WS(\", \", faculties.lastname, faculties.firstname) as faculty_name,
                    semesters.semester as semester,
                    school_years.start_year as start_year, school_years.end_year as end_year,
                    deliverables.name as deliverable_name,
                    sd.id as id, sd.description as description, sd.date_uploaded as date_uploaded, 
                    sd.date_updated as date_updated, sd.status as status, coalesce(sd.remarks, \"\") as remarks,
                    sd.name as filename, sd.ext as ext
                FROM submitted_deliverables sd
                INNER JOIN faculties
                ON faculties.user_id = sd.faculty_id
                INNER JOIN semesters 
                ON semesters.id = sd.semester_id
                INNER JOIN school_years 
                ON school_years.id = sd.school_year_id
                INNER JOIN deliverables 
                ON deliverables.id = sd.deliverable_id
                WHERE sd.faculty_id = :id;";

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

        public function getAllSubmittedWithSender($status='%'){
            $query = "
                SELECT CONCAT_WS(\", \", faculties.lastname, faculties.firstname) as faculty_name,
                    semesters.semester as semester,
                    school_years.start_year as start_year, school_years.end_year as end_year,
                    deliverables.name as deliverable_name,
                    sd.id as id, sd.description as description, sd.date_uploaded as date_uploaded, 
                    sd.date_updated as date_updated, sd.status as status, coalesce(sd.remarks, \"\") as remarks,
                    sd.name as filename, sd.ext as ext
                FROM submitted_deliverables sd
                INNER JOIN faculties
                ON faculties.user_id = sd.faculty_id
                INNER JOIN semesters 
                ON semesters.id = sd.semester_id
                INNER JOIN school_years 
                ON school_years.id = sd.school_year_id
                INNER JOIN deliverables 
                ON deliverables.id = sd.deliverable_id
                WHERE status LIKE \"{$status}\"; ";

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

        public function getUnpassedDocuments($id){
            $query = "
                SELECT * 
                FROM {$this->table_deliverables}
                WHERE `id`  not in (SELECT {$this->table_name}.deliverable_id FROM {$this->table_name} WHERE faculty_id = :id)";

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

        public function renderUnattendedFilesNotification(){
            $notifs = $this->getUnattended();
            $no_notifs = count($notifs);
            $ping = "<span>
                        {$no_notifs}
                    </span>";

            if ($no_notifs > 0){
                $ping = <<<EOL
                    <span class="">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full mr-1.5 mt-1.5"></span>
                        {$no_notifs}
                    </span>
                EOL;
            }

            // buttons and container 
            echo <<<EOL
                <button class="dropdown-toggle" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="lni lni-alarm"></i>
                    $ping
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification">
            EOL;
            $i = 0;
            foreach ($notifs as &$row) {
                $dt = strtotime($row['date_uploaded']);
                $deadline = date("m/d/Y", $dt);
                $str_date = date('Y-m-d', $dt);
                if ($i++ >= 4){
                    break;
                }
                echo <<<EOL
                    <li>
                    <a href="#0">
                        <div class="image">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="content">
                            <h6>
                                Deliverables
                                <span class="text-regular">
                                </span>
                            </h6>
                            <p>
                                {$row['description']}
                            </p>
                            <span class="text-red-500">{$deadline}</span>
                        </div>
                    </a>
                    </li>
                EOL;
            }
            echo "</ul>";
        }

        public function renderUnpassedNotifications($id){
            $notifs = $this->getUnpassedDocuments($id);
            $no_notifs = count($notifs);
            $ping = "<span>
                        {$no_notifs}
                    </span>";

            if ($no_notifs > 0){
                $ping = <<<EOL
                    <span class="">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full mr-1.5 mt-1.5"></span>
                        {$no_notifs}
                    </span>
                EOL;
            }

            // buttons and contianer 
            echo <<<EOL
                <button class="dropdown-toggle" type="button" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="lni lni-alarm"></i>
                    $ping
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notification">
            EOL;
            $i = 0;
            foreach ($this->getUnpassedDocuments($id) as &$row) {
                $dt = strtotime($row['deadline']);
                $deadline = date("m/d/Y", $dt);
                $str_date = date('Y-m-d', $dt);
                if ($i++ >= 4){
                    break;
                }
                echo <<<EOL
                    <li>
                    <a href="#0">
                        <div class="image">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="content">
                            <h6>
                                Deliverables
                                <span class="text-regular">
                                {$row['name']}
                                </span>
                            </h6>
                            <p>
                                {$row['description']}
                            </p>
                            <span class="text-red-500">{$deadline}</span>
                        </div>
                    </a>
                    </li>
                EOL;
            }
            echo "</ul>";
        }

        public function renderSubmittedFilesTable($id){
            include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';

            $session = new Session();
            $current_id = $session->get('id');

            $school_year = new SchoolYearController();
            $semester = new SemesterController();

            $origin = Env::$env['URL'];

            foreach ($this->getAllSubmittedBy($id) as &$row) {
                $upload_date = strtotime($row['date_uploaded']);
                $update_date = strtotime($row['date_updated']);
                $uploaded_date = date("m-d-Y", $upload_date);
                $updated_date = date("m-d-Y", $update_date);

                echo "<tr class='my-2'>";
                echo "    <td>{$row['faculty_name']}</td>";
                echo "    <td>{$row['deliverable_name']}</td>";
                echo "    <td>{$row['description']}</td>";
                echo "    <td>{$row['semester']}</td>";
                echo "    <td>{$row['start_year']}-{$row['end_year']}</td>";
                echo "    <td>{$uploaded_date}</td>";
                echo "    <td>{$updated_date}</td>";
                echo "    <td>{$row['status']}</td>";
                echo "    <td>{$row['remarks']}</td>";
                echo <<<EOL
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn btn-success w-full">
                                        <a href="/view-file.php?filename={$row['filename']}&ext={$row['ext']}" target="_blank">
                                            View
                                        </a>
                                    </button>
                                    <button class="btn btn-primary w-full"  data-bs-toggle="modal" data-bs-target="#editSubmitted-{$row['id']}">
                                        Edit
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>

                    <div class="modal fade" id="editSubmitted-{$row['id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <form action="/scripts/routes/submitted_edit_submission.route.php?id={$row['id']}&filename={$row['name']}" method="post">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Review Deliverable</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-span-full form-floating mb-3">
                                            <input type="file" class="form-control" id="name" name="name" placeholder="">
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="description" name="description" placeholder="Status" value="{$row['description']}" required>
                                            <label for="description">Description</label>
                                        </div>
                EOL;
                                        $school_year->renderSelect();
                                        $semester->renderSelect();
                echo <<<EOL
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger text-red-900" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary text-sky-900">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                EOL;
                echo "</tr>";
            }
        }


        public function renderTable($status='%'){
            $origin = Env::$env['URL'];

            foreach ($this->getAllSubmittedWithSender($status) as &$row) {
                $upload_date = strtotime($row['date_uploaded']);
                $update_date = strtotime($row['date_updated']);
                $uploaded_date = date("m-d-Y", $upload_date);
                $updated_date = date("m-d-Y", $update_date);

                echo "<tr class='my-2'>";
                echo "    <td>{$row['faculty_name']}</td>";
                echo "    <td>{$row['deliverable_name']}</td>";
                echo "    <td>{$row['description']}</td>";
                echo "    <td>{$row['semester']}</td>";
                echo "    <td>{$row['start_year']}-{$row['end_year']}</td>";
                echo "    <td>{$uploaded_date}</td>";
                echo "    <td>{$updated_date}</td>";
                echo "    <td>{$row['status']}</td>";
                echo "    <td>{$row['remarks']}</td>";
                echo <<<EOL
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn btn-success w-full">
                                        <a href="/view-file.php?filename={$row['filename']}&ext={$row['ext']}" target="_blank">
                                            View
                                        </a>
                                    </button>
                                    <button class="btn btn-primary w-full"  data-bs-toggle="modal" data-bs-target="#reviewDeliverable-{$row['id']}">
                                        Review
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>

                    <div class="modal fade" id="reviewDeliverable-{$row['id']}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <form action="/scripts/routes/deliverable_review_deliverable.route.php?id={$row['id']}" method="post">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Review Deliverable</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="status" name="status" placeholder="Status" value="{$row['status']}">
                                            <label for="status">Status</label>
                                        </div>
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" name="remarks" id="remarks">{$row['remarks']}</textarea>
                                            <label for="remarks">Remarks</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger text-red-900" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary text-sky-900">Review</button>
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