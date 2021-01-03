<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
  header('Location: login.php');
}
include('header.html');
 ?>


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Listings</h3>
              </div>
              <?php

              if (!empty($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
              }else {
                $pageno = 1;
              }
              $numOfrecs = 5;
              $offset = ($pageno - 1) * $numOfrecs;

              if (empty($_POST['search'])) {
                $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                $stmt->execute();
                $rawResult = $stmt->fetchAll();
                $total_pages = ceil(count($rawResult) / $numOfrecs);

                $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                $stmt->execute();
                $result = $stmt->fetchAll();
              }else{
                $searchKey = $_POST['search'];
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
                $stmt->execute();
                $rawResult = $stmt->fetchAll();
                $total_pages = ceil(count($rawResult) / $numOfrecs);

                $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs ");
                $stmt->execute();
                $result = $stmt->fetchAll();
              }

               ?>
              <!-- /.card-header -->
              <div class="card-body">
                  <a href="add.php" type="button" class="btn btn-success mb-4">New Blog Posts</a>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th style="width:17%">Title</th>
                      <th>Content</th>
                      <th style="width: 17%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result) {
                      $i = 1;
                      foreach ($result as $value) { ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo $value['title']; ?></td>
                          <td><?php echo substr($value['content'],0,100); ?></td>
                          <td>
                            <a href="edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning mr-1">Edit</a>
                            <a href="delete.php?id=<?php echo $value['id'] ?>"
                              onclick="return confirm('Are you sure you want to delete this item?');"
                              type="button" class="btn btn-danger">Delete</a>
                          </td>
                        </tr>
                      <?php
                      $i++;
                      }
                    }
                     ?>

                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float:right;">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno <= 1) {echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno >= $total_pages) {echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a></li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->


            </div>
            <!-- /.card -->


          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
<?php include('footer.html'); ?>
