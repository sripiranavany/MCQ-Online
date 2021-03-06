<?php
require_once '../../../config/init.php';
include_once("../../../includes/partials/htmlheader.php");
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../../../login.php');
}

$data = $_GET['question'];
$questionId = openssl_decrypt(base64_decode($data), Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));
$question = new Question();
$question->find($questionId);

$error = null;
if (Session::exists('errors')) {
    $error = Session::get('errors');
    Session::delete('errors');
}
if (Session::exists('validation')) {
    $validation = Session::get('validation');
    Session::delete('validation');
}

?>

<head>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="../../../public/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Online Exam</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="w-100"></span>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?php echo Config::get('urls/root_url') ?>functions/auth/logout.php"><i class="bi bi-power"></i> Sign out</a>
            </li>
        </ul>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="../home.php">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <hr />
                        <li class="nav-item">
                            <a class="nav-link" href="../subjects.php">
                                <i class="bi bi-journals"></i>
                                Subjects
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active">
                                <i class="bi bi-journal-code"></i>
                                Answers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../history.php">
                                <i class="bi bi-clock-history"></i>
                                History
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $question->data()->question; ?>&nbsp;'s Answers</h1>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="questionTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Answer</th>
                                <th>Is the answer is correct</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <?php
    include_once("../../../includes/partials/script.php");
    ?>
    <script>
        function deleteAnswer(id) {

        }
        $(document).ready(function() {
            $('#questionTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '../../../functions/admin/question/ajaxAnswer.php',
                    'type': 'post',
                    'data': {
                        'subjectId': '<?php echo $data; ?>'
                    }
                },
                'columns': [{
                        data: 'answerId'
                    },
                    {
                        data: 'answer'
                    },
                    {
                        data: 'isCorrect'
                    },
                    {
                        data: 'actions'
                    }
                ]
            });
        });
    </script>