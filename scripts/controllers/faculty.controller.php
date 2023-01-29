<?php 

    include_once 'dbcontroller.php';
    include_once 'user.controller.php';

    class FacultyController {
        private $table_name = 'faculties';
        private $table_link_users = 'users';
        private $connection = null;

        public function __construct(){
            $db = new DBController();
            $this->connection = $db->getConnection();
        }

        public function check($key, $data){
            $query = "SELECT * FROM {$this->table_name} WHERE $key = :$key;";

            $stmt = $this->connection->prepare($query);
            $stmt->execute([":$key" => $data]);

            if ($stmt->rowCount() > 0) {
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

            $query = "UPDATE `{$this->table_name}` SET $set_query WHERE user_id=:id";
            $stmt = $this->connection->prepare($query);
            $prep[':id'] = $id;

            if ($stmt->execute($prep)) {
                return true;
            }
            return false;
        }

        public function delete($id): bool{
            $delete_submitted  = "DELETE FROM submitted_deliverables WHERE faculty_id = :id";
            $stmt = $this->connection->prepare($delete_submitted);
            $stmt->execute([':id' => $id]);

            $delete_reports  = "DELETE FROM reports WHERE faculty_id = :id";
            $stmt = $this->connection->prepare($delete_reports);
            $stmt->execute([':id' => $id]);

            $query = "DELETE FROM {$this->table_name} WHERE user_id=:id";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute([':id' => $id])) {
                $users = new UserController();
                $result = $users->delete($id);

                return $result;
            }
            return false;
        }

        public function getAll() {
            $query = "
                    SELECT  id, faculty_id, username, email, faculty_id, lastname, firstname, middlename, 
                            address, contact_no, profile_img
                    FROM {$this->table_name}
                    INNER JOIN {$this->table_link_users}
                    ON {$this->table_link_users}.id = {$this->table_name}.user_id
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

        public function renderTable() {
            foreach ($this->getAll() as $row) {
                echo "<tr class='my-2'>";
                echo "    <td>{$row['lastname']}, {$row['firstname']}</td>";
                echo "    <td>{$row['address']}</td>";
                echo "    <td>{$row['contact_no']}</td>";
                echo "    <td>{$row['username']}</td>";
                echo <<<EOL
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn bg-red-900 text-white text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Options
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="btn bg-red-900 text-white w-full"  data-bs-toggle="modal" data-bs-target="#editFaculty-{$row['id']}">
                                        Edit
                                    </button>
                                    <a href="scripts/routes/faculty_delete_faculty.route.php?id={$row['id']}&username={$row['username']}" class="btn btn-danger w-full" onclick="return confirm('Are you sure you want to delete {$row['username']}?');">
                                        Remove
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>


                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editFaculty-{$row['id']}" tabindex="-1" aria-labelledby="registerFaculty" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-xl relative w-auto pointer-events-none">
                            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                                <h5 class="text-xl font-medium leading-normal text-gray-800">
                                    Edit Faculty
                                </h5>
                                <button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class='' action="scripts/routes/faculty_edit_faculty.route.php?id={$row['id']}" method="post">
                                <div class="modal-body relative p-4 grid gap-2 grid-cols-1 md:grid-cols-2 w-full">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" value="{$row['lastname']}" required>
                                        <label for="lastname">Last Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="" value="{$row['firstname']}" required>
                                        <label for="firstname">First Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="middlename" name="middlename" placeholder="" value="{$row['middlename']}" required>
                                        <label for="middlename">Middle Name</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="" value="{$row['address']}" required>
                                        <label for="address">Address</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="" value="{$row['contact_no']}" required>
                                        <label for="contact_no">Contact Number</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="" value="{$row['username']}" required>
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="" value="{$row['email']}" required>
                                        <label for="email">Email Address</label>
                                    </div>
                                    <div class="col-span-full modal-footer">
                                        <button type="button" class="btn btn-secondary bg-slate-700" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn bg-red-900 text-white">Update Faculty</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                EOL;
                echo "</tr>";
            }
        }



    }