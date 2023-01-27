<?php 

    include_once 'dbcontroller.php';

    include_once 'submitted_file.controller.php';
    include_once 'report.controller.php';


    class Notifications {

        public function renderFacultyNotifications($id){
            $submissions = new SubmittedFileController();
            $reports = new ReportController();


            $notifs = $submissions->getUnpassedDocuments($id);

            $unpassed_reports = $reports->getUnpassedReports($id);

            $no_notifs = count($notifs) + count($unpassed_reports);
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
                <ul class="dropdown-menu dropdown-menu-end max-h-80 overflow-auto" aria-labelledby="notification">
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

            foreach ($unpassed_reports as &$row) {
                $dt = strtotime($row['activity_date']);
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
                                Activity Report
                                <span class="text-regular">
                                {$row['activity_name']}
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


        
        public function renderAdminNotifications(){
            $submissions = new SubmittedFileController();
            $reports = new ReportController();


            $notifs = $submissions->getUnattended();
            $unpassed_reports = $reports->getUnreviewedReports();

            $no_notifs = count($notifs) + count($unpassed_reports);
            $ping = "<span class='bg-red'>
                        {$no_notifs}
                    </span>";

            if ($no_notifs > 0){
                $ping = <<<EOL
                    <span class="">
                        <span class="animate-ping absolute inline-flex h-full bg-red-900 w-full rounded-full mr-1.5 mt-1.5"></span>
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
                <ul class="dropdown-menu dropdown-menu-end max-h-80 overflow-auto" aria-labelledby="notification">
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

            foreach ($unpassed_reports as &$row) {
                $dt = strtotime($row['date']);
                $deadline = date("m/d/Y", $dt);
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
                                Activity Report
                                <span class="text-regular">
                                {$row['activity_name']}
                                </span>
                            </h6>
                            <p>
                                {$row['description']}
                            </p>
                            <span class="text-red-500"></span>
                        </div>
                    </a>
                    </li>
                EOL;
            }
            echo "</ul>";
        } 




    }