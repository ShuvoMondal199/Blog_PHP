<?php

session_start();

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
| Make sure your connection file creates $conn.
*/
require_once "../config/db.php";


/*
|--------------------------------------------------------------------------
| User ID
|--------------------------------------------------------------------------
| Change this according to your login/session system.
*/
$user_id = $_SESSION['user_id'] ?? null;


/*
|--------------------------------------------------------------------------
| Messages
|--------------------------------------------------------------------------
*/
$message = "";
$error = "";


/*
|--------------------------------------------------------------------------
| Form Submit
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form values safely
    $title   = trim($_POST['title'] ?? '');
    $cat     = trim($_POST['categorie'] ?? '');
    $desc    = trim($_POST['desc'] ?? '');
    $content = trim($_POST['content'] ?? '');

    $thumbnail = "";


    /*
    |--------------------------------------------------------------------------
    | Validate Text Fields
    |--------------------------------------------------------------------------
    */
    if (
        empty($title) ||
        empty($cat) ||
        empty($desc) ||
        empty($content)
    ) {

        $error = "All fields are required.";

    } elseif (empty($user_id)) {

        $error = "User is not logged in.";

    } elseif (
        !isset($_FILES['thumbnail']) ||
        $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK
    ) {

        $error = "Please upload a thumbnail.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Thumbnail Upload
        |--------------------------------------------------------------------------
        */

        $upload_dir = "../uploads/";

        // Create uploads directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }


        $original_name = $_FILES['thumbnail']['name'];
        $tmp_name      = $_FILES['thumbnail']['tmp_name'];
        $file_size     = $_FILES['thumbnail']['size'];

        // Get extension
        $extension = strtolower(
            pathinfo($original_name, PATHINFO_EXTENSION)
        );


        // Allowed image extensions
        $allowed_extensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];


        // Maximum file size = 5 MB
        $max_size = 5 * 1024 * 1024;


        if (!in_array($extension, $allowed_extensions, true)) {

            $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

        } elseif ($file_size > $max_size) {

            $error = "Image size must be less than 5 MB.";

        } elseif (!getimagesize($tmp_name)) {

            $error = "The uploaded file is not a valid image.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | Generate Unique File Name
            |--------------------------------------------------------------------------
            */

            $thumbnail = uniqid('blog_', true) . '.' . $extension;

            $upload_path = $upload_dir . $thumbnail;


            /*
            |--------------------------------------------------------------------------
            | Move Image
            |--------------------------------------------------------------------------
            */

            if (!move_uploaded_file($tmp_name, $upload_path)) {

                $error = "Failed to upload thumbnail.";

            } else {

                /*
                |--------------------------------------------------------------------------
                | Insert Blog
                |--------------------------------------------------------------------------
                */

                $sql = "
                    INSERT INTO blogs
                    (
                        `title`,
                        `cat`,
                        `desc`,
                        `content`,
                        `thumbnail`,
                        `user-id`
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ";


                $stmt = $conn->prepare($sql);


                if (!$stmt) {

                    $error = "Database prepare failed: " . $conn->error;

                    // Delete uploaded image if database preparation fails
                    if (file_exists($upload_path)) {
                        unlink($upload_path);
                    }

                } else {

                    $stmt->bind_param(
                        "sssssi",
                        $title,
                        $cat,
                        $desc,
                        $content,
                        $thumbnail,
                        $user_id
                    );


                    if ($stmt->execute()) {

                        $message = "Blog added successfully.";

                        // Clear form values
                        $title = "";
                        $cat = "";
                        $desc = "";
                        $content = "";

                    } else {

                        $error = "Failed to add blog: " . $stmt->error;

                        // Delete image if database insert fails
                        if (file_exists($upload_path)) {
                            unlink($upload_path);
                        }
                    }

                    $stmt->close();
                }
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>BLOG || SHUVO</title>


    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0z1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"
    >


    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="./Assets/CSS/Add_blog.css"
    >

</head>


<body>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">


            <!-- Card -->
            <div class="card shadow border-0">


                <!-- Header -->
                <div class="card-header bg-dark text-white py-3">

                    <h3 class="mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        Create New Blog
                    </h3>

                </div>


                <!-- Body -->
                <div class="card-body p-4">


                    <!-- Success Message -->
                    <?php if (!empty($message)): ?>

                        <div
                            class="alert alert-success alert-dismissible fade show"
                            role="alert"
                        >

                            <i class="bi bi-check-circle-fill me-2"></i>

                            <?= htmlspecialchars($message) ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                            ></button>

                        </div>

                    <?php endif; ?>


                    <!-- Error Message -->
                    <?php if (!empty($error)): ?>

                        <div
                            class="alert alert-danger alert-dismissible fade show"
                            role="alert"
                        >

                            <i class="bi bi-exclamation-triangle-fill me-2"></i>

                            <?= htmlspecialchars($error) ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                            ></button>

                        </div>

                    <?php endif; ?>


                    <!-- Blog Form -->
                    <form
                        action=""
                        method="POST"
                        enctype="multipart/form-data"
                    >


                        <!-- Title -->
                        <div class="mb-4">

                            <label
                                for="title"
                                class="form-label fw-semibold"
                            >
                                Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control"
                                placeholder="Enter Your Blog Title"
                                value="<?= htmlspecialchars($title ?? '') ?>"
                                required
                            >

                        </div>


                        <!-- Category -->
                        <div class="mb-4">

                            <label
                                for="categorie"
                                class="form-label fw-semibold"
                            >
                                Category
                            </label>

                            <select
                                name="categorie"
                                id="categorie"
                                class="form-select"
                                required
                            >

                                <option
                                    value=""
                                    disabled
                                    <?= empty($cat) ? 'selected' : '' ?>
                                >
                                    Select Category
                                </option>


                                <option
                                    value="Technology"
                                    <?= ($cat ?? '') === 'Technology' ? 'selected' : '' ?>
                                >
                                    Technology
                                </option>


                                <option
                                    value="Medical"
                                    <?= ($cat ?? '') === 'Medical' ? 'selected' : '' ?>
                                >
                                    Medical
                                </option>


                                <option
                                    value="Travelling"
                                    <?= ($cat ?? '') === 'Travelling' ? 'selected' : '' ?>
                                >
                                    Travelling
                                </option>


                                <option
                                    value="Fooding"
                                    <?= ($cat ?? '') === 'Fooding' ? 'selected' : '' ?>
                                >
                                    Fooding
                                </option>

                            </select>

                        </div>


                        <!-- Short Description -->
                        <div class="mb-4">

                            <label
                                for="desc"
                                class="form-label fw-semibold"
                            >
                                Short Description
                            </label>

                            <input
                                type="text"
                                name="desc"
                                id="desc"
                                class="form-control"
                                placeholder="Enter Your Blog Short Description"
                                value="<?= htmlspecialchars($desc ?? '') ?>"
                                required
                            >

                        </div>


                        <!-- Content -->
                        <div class="mb-4">

                            <label
                                for="content"
                                class="form-label fw-semibold"
                            >
                                Content
                            </label>

                            <textarea
                                name="content"
                                id="content"
                                class="form-control"
                                rows="8"
                                placeholder="Enter Your Blog Content"
                                required
                            ><?= htmlspecialchars($content ?? '') ?></textarea>

                        </div>


                        <!-- Thumbnail -->
                        <div class="mb-4">

                            <label
                                for="thumbnail"
                                class="form-label fw-semibold"
                            >
                                Thumbnail
                            </label>

                            <input
                                type="file"
                                name="thumbnail"
                                id="thumbnail"
                                class="form-control"
                                accept=".jpg,.jpeg,.png,.webp,image/*"
                                required
                            >

                            <div class="form-text">

                                <i class="bi bi-info-circle me-1"></i>

                                JPG, JPEG, PNG or WEBP.
                                Maximum size: 5 MB.

                            </div>

                        </div>


                        <!-- Submit Button -->
                        <div class="d-grid">

                            <button
                                type="submit"
                                class="btn btn-dark btn-lg"
                            >

                                <i class="bi bi-send me-2"></i>

                                Publish Blog

                            </button>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8NlF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"
></script>


<!-- Custom JS -->
<script src="./Assets/JS/script.js"></script>


</body>

</html>
