<?php 

    include_once 'dbcontroller.php';

    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/env.core.php';


    class DownloadableController {
        private $table_name = 'downloadable_contents';
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
            $query = "
                    SELECT * FROM {$this->table_name}
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


        public function renderTable($is_editable=true) {
            $origin = Env::$env['URL'];

            foreach ($this->getAll() as $row) {
                echo "<tr class='my-2'>";
                echo "    <td>{$row['description']}</td>";

                if ($is_editable === true){
                    echo <<<EOL
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary text-blue-900 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Options
                                </button>
                                <ul class="dropdown-menu space-y-1 px-2">
                                    <li>
                                        <a href="/view-file.php?filename={$row['filename']}&ext={$row['ext']}" target="_blank" class="btn btn-primary  w-full">
                                            Download
                                        </a>
                                    </li>
                                    <li>
                                        <button class="btn btn-primary w-full" data-bs-toggle="modal" data-bs-target="#editDownloadable-{$row['id']}">
                                            Edit
                                        </button>
                                    </li>
                                    <li>
                                        <a href="scripts/routes/downloadable_delete_file.route.php?id={$row['id']}&filename={$row['filename']}" class="btn btn-danger w-full" onclick="return confirm('Are you sure you want to delete {$row['name']}?');">
                                            Remove
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    EOL;
                } else {
                    echo <<<EOL
                        <td>
                            <div class="btn-group">
                                <a href="/view-file.php?filename={$row['filename']}&ext={$row['ext']}" target="_blank">
                                    <button type="button" class="btn btn-primary text-blue-900">
                                        Download
                                    </button>
                                </a>
                            </div>
                        </td>
                    EOL;
                }
                

                echo <<<EOL
                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editDownloadable-{$row['id']}" tabindex="-1" aria-labelledby="registerFaculty" aria-modal="true" role="dialog">
                        <div class="modal-dialog modal-dialog-centered modal-xl relative w-auto pointer-events-none">
                            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                                <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                                    <h5 class="text-xl font-medium leading-normal text-gray-800">
                                        Edit Downloadable Content
                                    </h5>
                                    <button type="button" class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form class='' action="scripts/routes/downloadable_edit_file.route.php?id={$row['id']}&filename={$row['filename']}" method="post" enctype="multipart/form-data">
                                    <div class="modal-body relative p-4 grid gap-2 grid-cols-1 md:grid-cols-2 w-full">
                                        <div class="col-span-full form-floating mb-3">
                                            <input type="file" class="form-control" id="name" name="name" placeholder="">
                                        </div>
                                        <div class="col-span-full form-floating mb-3">
                                            <input type="text" class="form-control" id="description" name="description" placeholder="" value="{$row['description']}" required>
                                            <label for="description">Description</label>
                                        </div>
                                        <div class="col-span-full modal-footer">
                                            <button type="button" class="btn btn-secondary bg-slate-700" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary bg-blue-900">Update</button>
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